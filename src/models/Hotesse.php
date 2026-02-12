<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;
use zenhealth\models\Cabine;

class Hotesse extends Model {
    protected $table = 'hotesse';
    protected $primaryKey = 'numhot';
    public $timestamps = false;

    public function cabines(){
        $this->hasMany(Cabine::class);
    }
}