<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use ZENHEALTH\models\Reservation;

$db = new DB();
$db->addConnection(parse_ini_file('src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

echo "<h1>Zenhealth</h1>";

try {
    DB::connection()->getPdo();
    echo "Connexion OK<br>";
} catch (\Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}


DB::beginTransaction();

try {
    $existe = Reservation::where('numcab', $numcab)
        ->where('datres', $dates)
        ->first();  

    if ($existe !== null) {
        throw new Exception("Cabine deja réservée");
    }

    DB::commit();       
    echo "Transaction validée";

} catch (\Exception $e) {
    DB::rollBack();       
    echo "Transaction annulée : " . $e->getMessage();
}