<?php
namespace zenhealth\models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $table = 'reservation';
    protected $primaryKey = 'numres';
    public $timestamps = false;
}