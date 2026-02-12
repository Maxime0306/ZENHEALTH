<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Service;

if (!isset($_SESSION['numhot']) || $_SESSION['grade'] !== 'gestionnaire') {
    header('Location: dashboard.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numserv'])) {
    try {
        DB::transaction(function () {
            $service = Service::findOrFail($_POST['numserv']);
            
            $service->prixunit = $_POST['prixunit'];
            $service->nbrinterventions = $_POST['nbrinterventions'];
            
            $service->save();
        });
        $message = "Service mis à jour avec succès !";
    } catch (\Exception $e) {
        $message = "Erreur lors de la modification : " . $e->getMessage();
    }
}

$services = Service::all();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Service - ZENHEALTH</title>
</head>
<body>
    <h1>Gestionnaire : Modifier un Service</h1>
    <p><a href="dashboard.php">Retour au menu</a></p>

    <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="POST">
        <label>Service à modifier :</label>
        <select name="numserv" required>
            <option value="">-- Sélectionner un service --</option>
            <?php foreach ($services as $s): ?>
                <option value="<?= $s->numserv ?>">
                    <?= htmlspecialchars($s->libelle) ?> (Actuel : <?= $s->prixunit ?>€, <?= $s->nbrinterventions ?> dispos)
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Nouveau Prixunit (€) :</label>
        <input type="number" step="0.01" name="prixunit" placeholder="Ex: 50.00" required>

        <br><br>

        <label>Nouveau nombre d'interventions max :</label>
        <input type="number" name="nbrinterventions" placeholder="Ex: 10" required>

        <br><br>
        <button type="submit">Appliquer les modifications</button>
    </form>
</body>
</html>