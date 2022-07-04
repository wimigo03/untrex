<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Ciudades extends Model
{
    protected $table = 'ciudades';
    protected $fillable = [
        'nombre',
        'abreviatura',
        'estado'
    ];
    
    use SoftDeletes;
    protected $dates =['deleted_at'];
}