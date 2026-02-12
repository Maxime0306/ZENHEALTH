<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Reservation;

if (!isset($_SESSION['numhot']) || $_SESSION['grade'] !== 'gestionnaire') {
    header('Location: dashboard.php');
    exit;
}

$db = new DB();
$db->addConnection(parse_ini_file('../src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numres = $_POST['numres'];
    
    DB::transaction(function () use ($numres) {
        $res = Reservation::with('services')->findOrFail($numres);
        $total = $res->services->sum('prixunit');

        $res->update([
            'montant_total' => $total,
            'datpaie' => date('Y-m-d H:i:s'),
            'modpaie' => $_POST['modpaie']
        ]);
    });
}

$reservations = Reservation::whereNull('datpaie')->get();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Encaissement - ZENHEALTH</title>
</head>
<body>
    <h1>Gestionnaire : Encaisser une réservation</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message) echo "<p style='color:blue; font-weight:bold;'>$message</p>"; ?>

    <?php if ($reservations->isEmpty()): ?>
        <p>Aucune réservation en attente d'encaissement.</p>
    <?php else: ?>
        <form method="POST">
            <label>Sélectionner la réservation terminée :</label>
            <select name="numres" required>
                <option value="">-- Choisir une réservation --</option>
                <?php foreach ($reservations as $res): ?>
                    <option value="<?= $res->numres ?>">
                        Résa n°<?= $res->numres ?> (Cabine <?= $res->numcab ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>

            <label>Mode de paiement :</label>
            <select name="mode_paiement" required>
                <option value="Carte Bancaire">Carte Bancaire</option>
                <option value="Espèces">Espèces</option>
                <option value="Chèque">Chèque</option>
            </select>

            <br><br>
            <button type="submit">Calculer le total et Encaisser</button>
        </form>
    <?php endif; ?>
</body>
</html>