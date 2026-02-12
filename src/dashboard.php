<?php
session_start();

if (!isset($_SESSION['numhot'])) {
    header('Location: ../index.php');
    exit;
}

$nom = $_SESSION['nomserv'];
$grade = $_SESSION['grade'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ZENHEALTH</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>ZENHEALTH Institut de Beauté</h1>
        <p>Bienvenue, <?php echo $nom; ?> (<i><?php echo $grade; ?></i>)</p>
        <a href="logout.php">Déconnecter</a>
    </header>

    <main>
        <section>
            <h2>Actions Hôtesse</h2>
            <ul>
                <li><a href="reserver.php">Réserver une cabine disponible</a></li>
                <li><a href="commander.php">Commander des services pour une réservation</a></li>
            </ul>
        </section>

        <?php if ($grade === 'gestionnaire'): ?>
            <h2>Actions Gestionnaire</h2>
            <ul>
                <li><a href="affecter.php">Affecter les hôtesses aux cabines</a></li>
                <li><a href="annuler.php">Annuler une réservation non consommée</a></li>
                <li><a href="modifier_service.php">Modifier les prix ou interventions des services</a></li>
                <li><a href="encaisser.php">Calculer le montant et encaisser</a></li>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>