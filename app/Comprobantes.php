<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Comprobantes extends Model
{
    protected $table = 'comprobantes';
    protected $fillable = [
        'id',
        'user_id',
        'centro_id',
        'user_autorizado_id',
        'nro_comprobante',
        'nro_comprobante_id',
        'tipo_cambio',
        'ufv',
        'tipo',
        'entregado_recibido',
        'fecha',
        'concepto',
        'monto',
        'moneda',
        'copia',
        'status',
        'status_validate'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}