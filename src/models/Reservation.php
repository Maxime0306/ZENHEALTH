<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $table = 'reservation';
    protected $primaryKey = 'numres';
    public $timestamps = false;

    protected $fillable = [
        'numres', 
        'numcab', 
        'datres', 
        'nbpers', 
        'datpaie', 
        'modpaie', 
        'montcom' // Note : utilise 'montcom' car c'est le nom dans ton SQL [cite: 14]
    ];

    public function cabine() {
        return $this->belongsTo(Cabine::class, 'numcab');
    }

    public function services() {
        return $this->BelongsToMany(Service::class,'commande','numres','numserv')
            ->withPivot('nbrinterventions');
    }
}