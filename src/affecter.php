<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Hotesse;
use zenhealth\models\Cabine;

if (!isset($_SESSION['numhot']) || $_SESSION['grade'] !== 'gestionnaire') {
    header('Location: dashboard.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numhot = $_POST['numhot'];
    $numcab = $_POST['numcab'];
    $dateAffectation = $_POST['date_affectation'];

    try {
        DB::transaction(function () use ($numhot, $numcab, $dateAffectation) {
            DB::table('affecter')->updateOrInsert(
                ['numcab' => $numcab, 'dataff' => $dateAffectation],
                ['numhot' => $numhot]
            );
        });
        $message = "Affectation réussie pour la cabine $numcab le $dateAffectation.";
    } catch (\Exception $e) {
        $message = "Erreur lors de l'affectation : " . $e->getMessage();
    }
}

$hotesses = Hotesse::all();
$cabines = Cabine::all();   
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Affectation - ZENHEALTH</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gestionnaire : Affectation des hôtesses</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="POST">
        <label>Sélectionner l'hôtesse :</label>
        <select name="numhot" required>
            <?php foreach ($hotesses as $h): ?>
                <option value="<?= $h->numhot ?>"><?= $h->nomserv ?>(<?= $h->grade ?>)</option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Sélectionner la cabine :</label>
        <select name="numcab" required>
            <?php foreach ($cabines as $c): ?>
                <option value="<?= $c->numcab ?>">Cabine n°<?= $c->numcab ?></option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Date d'affectation :</label>
        <input type="date" name="date_affectation" value="<?= date('Y-m-d') ?>" required>

        <br><br>
        <button type="submit">Valider l'affectation</button>
    </form>
</body>
</html>