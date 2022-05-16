<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ComprobantesDetalle extends Model
{
    protected $table = 'comprobantes_detalles';
    protected $fillable = [
        'id',
        'comprobante_id',
        'plancuenta_id',
        'plancuentaauxiliar_id',
        'proyecto_id',
        'centro_id',
        'debe',
        'haber',
        'tipo_transaccion',
        'cheque_nro',
        'cheque_orden'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}