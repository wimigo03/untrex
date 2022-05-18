<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ComprobantesFiscalesDetalle extends Model
{
    protected $table = 'comprobantes_fiscales_detalles';
    protected $fillable = [
        'comprobante_fiscal_id',
        'plancuenta_id',
        'plancuentaauxiliar_id',
        'proyecto_id',
        'centro_id',
        'glosa',
        'debe',
        'haber',
        'tipo_transaccion',
        'cheque_nro',
        'cheque_orden'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}