<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Reservation;

if (!isset($_SESSION['numhot']) || $_SESSION['grade'] !== 'gestionnaire') {
    header('Location: dashboard.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numres = $_POST['numres'];
    $modePaiement = $_POST['modpaie'];
    
    try { 
        $totalCalcule = DB::transaction(function () use ($numres, $modePaiement) {
            $res = Reservation::with('services')->findOrFail($numres);
            
            $total = $res->services->sum('prixunit');

            $res->update([
                'montcom' => $total,
                'datpaie' => date('Y-m-d H:i:s'),
                'modpaie' => $modePaiement
            ]);
            
            return $total;
        });

        $message = "Paiement effectué avec succès ! <br> Montant encaissé : <strong>" . number_format($totalCalcule, 2) . " €</strong>";
    } catch (\Exception $e) {
        $message = "Erreur lors de l'encaissement : " . $e->getMessage();
    }
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
    <div class="container">
        <h1>ZENHEALTH - Encaissement</h1>
        <p><a href="dashboard.php">⬅ Retour au menu</a></p>

        <?php if ($message): ?>
            <div>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($reservations->isEmpty()): ?>
            <p>Aucune réservation en attente d'encaissement.</p>
        <?php else: ?>
            <form method="POST">
                <label>Sélectionner la réservation à solder :</label>
                <select name="numres" required>
                    <option value="">-- Choisir une réservation --</option>
                    <?php foreach ($reservations as $res): ?>
                        <option value="<?= $res->numres ?>">
                            Résa n°<?= $res->numres ?> (Cabine <?= $res->numcab ?> - Client prévu le <?= $res->datres ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <br>

                <label>Mode de paiement:</label>
                <select name="modpaie" required>
                    <option value="Carte">Carte Bancaire</option>
                    <option value="Espèces">Espèces</option>
                    <option value="Chèque">Chèque</option>
                </select>

                <button type="submit">Confirmer l'encaissement et calculer le total</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>