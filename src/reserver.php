<?php
require_once __DIR__ . '/../bootstrap.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Illuminate\Database\Capsule\Manager as DB;
use ZENHEALTH\models\Reservation;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $numres = (int) $_POST['numres'];
    $numcab = (int) $_POST['numcab'];
    $nbpers = (int) $_POST['nbpers'];

    // datetime-local -> "YYYY-MM-DDTHH:MM"  =>  "YYYY-MM-DD HH:MM:SS"
    $datres = str_replace('T', ' ', $_POST['datres']) . ':00';

    DB::beginTransaction();

    try {
        // Vérifier disponibilité : même cabine + même date/heure
        $existe = Reservation::where('numcab', $numcab)
            ->where('datres', $datres)
            ->first();

        if ($existe !== null) {
            throw new Exception("Cabine déjà réservée à ce créneau.");
        }

        // Créer la réservation
        $r = new Reservation();
        $r->numres = $numres;
        $r->numcab = $numcab;
        $r->datres = $datres;
        $r->nbpers = $nbpers;
        $r->datpaie = null;
        $r->modpaie = null;
        $r->montcom = null;
        $r->save();

        DB::commit();
        $message = "Réservation enregistrée.";

    } catch (\Exception $e) { 
        DB::rollBack();

        echo "Type : " . get_class($e) . "<br>";
        echo "Message : " . $e->getMessage();
    }
}
?>

<?php if ($message !== '') { ?>
  <p><?= $message ?></p>
<?php } ?>

<form method="post">
  <label>Numéro réservation (numres)</label>
  <input type="number" name="numres" required>

  <label>Cabine (numcab)</label>
  <input type="number" name="numcab" required>

  <label>Date/heure (datres)</label>
  <input type="datetime-local" name="datres" required>

  <label>Nombre de personnes (nbpers)</label>
  <input type="number" name="nbpers" min="1" required>

  <button type="submit">Réserver</button>
</form>