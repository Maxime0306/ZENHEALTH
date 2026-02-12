<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;

class Cabine extends Model {
    protected $table = 'cabine';
    protected $primaryKey = 'numcab';
    public $timestamps = false;

    public function hotesse(){
        return $this->belongsTo(hotesse::class);
    }
}