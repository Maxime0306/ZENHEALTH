<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Cabine;

session_start();

if (!isset($_SESSION['numhot'])) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastRes = Reservation::max('numres');
    $numres = $lastRes ? $lastRes + 1 : 100; 

    $numcab = (int) $_POST['numcab'];
    $nbpers = (int) $_POST['nbpers'];
    $datres = str_replace('T', ' ', $_POST['datres']) . ':00';

    DB::beginTransaction();

    try {
        $existe = Reservation::where('numcab', $numcab)
            ->where('datres', $datres)
            ->first();

        if ($existe !== null) {
            throw new Exception("Cette cabine est déjà réservée pour ce créneau.");
        }

        $r = new Reservation();
        $r->numres = $numres;
        $r->numcab = $numcab;
        $r->datres = $datres;
        $r->nbpers = $nbpers;
        $r->save();

        DB::commit();
        $message = "Réservation n°$numres enregistrée avec succès.";

    } catch (\Exception $e) {
        DB::rollBack();
        $message = "Erreur : " . $e->getMessage();
    }
}

$cabines = Cabine::all();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver - ZENHEALTH</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Réserver une cabine</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message): ?>
        <p style="color: blue;"><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <form method="post">
        <label>Choisir une cabine :</label><br>
        <select name="numcab" required>
            <option value="">-- Sélectionner une cabine --</option>
            <?php foreach ($cabines as $c): ?>
                <option value="<?= $c->numcab ?>">
                    Cabine n°<?= $c->numcab ?> (<?= $c->nbplace ?> places)
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Date et heure :</label><br>
        <input type="datetime-local" name="datres" required>
        <br><br>

        <label>Nombre de personnes :</label><br>
        <select name="nbpers" required>
            <?php for($i=1; $i<=8; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> personne(s)</option>
            <?php endfor; ?>
        </select>
        <br><br>

        <button type="submit">Confirmer la réservation</button>
    </form>
</body>
</html>