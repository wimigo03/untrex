<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class BalanceAperturas extends Model
{
    protected $table = 'balance_aperturas';
    protected $fillable = [
        'proyecto_id',
        'comprobante_id',
        'fecha_creacion',
        'gestion',
        'moneda',
        'base',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}