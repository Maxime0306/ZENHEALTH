<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model {
    protected $table = 'service';
    protected $primaryKey = 'numserv';
    public $timestamps = false;

    public function reservations() {
        return $this->BelongsToMany(Reservation::class, 'commande', 'numres', 'numserv')
            ->withPivot('nbrinterventions');
    }
}