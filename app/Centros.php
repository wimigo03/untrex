<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Centros extends Model
{
    protected $table = 'centros';
    protected $fillable = [
        'proyecto_id',
        'nombre',
        'abreviatura'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}