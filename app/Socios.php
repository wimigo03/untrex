<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Socios extends Model
{
    protected $table = 'socios';
    protected $fillable = [
        'consorcio_id',
        'nombre',
        'abreviatura',
        'porcentaje',
        'tipo',
        'direccion',
        'nit',
        'razon_social'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}