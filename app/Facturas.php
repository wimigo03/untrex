<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Facturas extends Model
{
    protected $table = 'facturas';
    protected $fillable = [
        'socio_id',
        'proveedor_id',
        'fecha',
        'nit',
        'razon_social',
        'numero',
        'nro_dui',
        'nro_autorizacion',
        'cod_control',
        'monto',
        'excento',
        'descuento',
        'procedencia',
        'tipo',
        'estado',
        'comprobante_id',
        'plancuenta_id',
        'plancuentaauxiliar_id',
        'proyecto_id',
        'centro_id',
        'glosa'
    ];
    
    use SoftDeletes;
    protected $dates =['deleted_at'];
}