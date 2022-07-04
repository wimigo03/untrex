<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Proveedores extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'razon_social',
        'nombre_comercial',
        'nit',
        'nro_cuenta',
        'titular_cuenta',
        'banco',
        'ciudad_id',
        'direccion',
        'tipo',
        'contacto1',
        'contacto2',
        'celular1',
        'celular2',
        'fijo1',
        'fijo2',
        'email',
        'plazo',
        'observaciones',
        'status'
    ];
    
    use SoftDeletes;
    protected $dates =['deleted_at'];
}