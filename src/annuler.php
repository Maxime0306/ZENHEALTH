<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Reservation;
use zenhealth\models\Service;

if (!isset($_SESSION['numhot']) || $_SESSION['grade'] !== 'gestionnaire') {
    header('Location: dashboard.php');
    exit;
}

$db = new DB();
$db->addConnection(parse_ini_file('../src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reservation'])) {
    $idResa = $_POST['id_reservation'];

    try {
        DB::transaction(function () use ($idResa) {
            $reservation = Reservation::with('services')->findOrFail($idResa);

            if ($reservation->datres !== null) {
                throw new \Exception("Impossible d'annuler une réservation déjà encaissée.");
            }

            foreach ($reservation->services as $service) {
                $service->increment('nb_interventions_jour');
            }

            $reservation->services()->detach();
            $reservation->delete();
        });
        $message = "La réservation n°$idResa a été annulée et les services ont été recrédités.";
    } catch (\Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

$reservations = Reservation::whereNull('datpaie')->get();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annulation - ZENHEALTH</title>
</head>
<body>
    <h1>Gestionnaire : Annuler une réservation</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

    <?php if ($reservations->isEmpty()): ?>
        <p>Aucune réservation en attente d'annulation.</p>
    <?php else: ?>
        <form method="POST">
            <label>Sélectionner la réservation à annuler :</label>
            <select name="id_reservation" required>
                <?php foreach ($reservations as $res): ?>
                    <option value="<?= $res->id ?>">
                        Résa n°<?= $res->id ?> - Cabine <?= $res->numcab ?> 
                        (Prévue le <?= $res->datres ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ?');">
                Confirmer l'annulation
            </button>
        </form>
    <?php endif; ?>
</body>
</html>