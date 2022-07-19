<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class BalanceAperturasFiscales extends Model
{
    protected $table = 'balance_aperturas_fiscales';
    protected $fillable = [
        'proyecto_id',
        'comprobante_fiscal_id',
        'fecha_creacion',
        'gestion',
        'moneda',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}