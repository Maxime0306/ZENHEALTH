<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $table = 'reservation';
    protected $primaryKey = 'numres';
    public $timestamps = false;

    public function cabine() {
        return $this->belongsTo(Cabine::class, 'numcab');
    }

    public function service() {
        return $this->BelongsToMany(Service::class,'commande','numres','numserv')
            ->withPivot('nbrinterventions');
    }
}