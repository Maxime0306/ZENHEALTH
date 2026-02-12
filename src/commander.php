<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Hotesse;
use zenhealth\models\Service;
use zenhealth\models\Reservation;

if (!isset($_SESSION['numhot'])) {
    header('Location: ../index.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_service'], $_POST['id_reservation'])) {
    $idService = $_POST['id_service'];
    $idResa = $_POST['id_reservation'];

    try {
        DB::transaction(function () use ($idService, $idResa) {
            $service = Service::lockForUpdate()->find($idService);
            if ($service->nbrinterventions > 0) {
                $reservation = Reservation::findOrFail($idResa);
                $reservation->services()->attach($idService);
                $service->nbrinterventions -= 1;
                $service->save();
            } else {
                throw new \Exception("plus disponibles pour ce service");
            }
        });
        $message = "Service ajouté";
    } catch (\Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

$services = Service::where('nbrinterventions', '>', 0)->get();
$reservations = Reservation::whereNull('datres')->get();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commander - ZENHEALTH</title>
</head>
<body>
    <h1>Commander un service</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>

    <form method="POST">
        <label>Choisir la réservation :</label>
        <select name="id_reservation" required>
            <?php foreach ($reservations as $res): ?>
                <option value="<?= $res->id ?>">Résa n°<?= $res->id ?> (Cabine <?= $res->numcab ?>)</option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Choisir le service à ajouter :</label>
        <select name="id_service" required>
            <?php foreach ($services as $serv): ?>
                <option value="<?= $serv->id ?>"><?= $serv->libelle ?> - <?= $serv->prix ?>€ (Restant : <?= $serv->nbrinterventions ?>)</option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <button type="submit">Ajouter à la commande</button>
    </form>
</body>
</html>