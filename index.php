<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use zenhealth\models\Hotesse;

session_start();

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $user = Hotesse::where('email', $email)->first();

    if ($user && password_verify($password, $user->passwd)) {
        $_SESSION['numhot'] = $user->numhot;
        $_SESSION['nomserv'] = $user->nomserv;
        $_SESSION['grade'] = $user->grade;
        
        header('Location: src/dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ZENHEALTH</title>
</head>
<body>
    <div>
        <h1>ZENHEALTH</h1>
        
        <?php if (isset($_SESSION['numhot'])): ?>
            <p>Vous êtes déjà connecté en tant que <?php echo $_SESSION['grade']; ?>.</p>
            <a href="src/dashboard.php">Accéder au menu</a> | <a href="src/logout.php">Déconnexion</a>
        <?php else: ?>
            
            <form method="POST">
                <label>Email :</label>
                <input type="email" name="email" required>
                
                <label>Mot de passe :</label>
                <input type="password" name="password" required>
                
                <button type="submit">Se connecter</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>