<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

echo "<h1>Zenhealth</h1>";

try {
    DB::connection()->getPdo();
    echo "Connexion OK";
} catch (\Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
