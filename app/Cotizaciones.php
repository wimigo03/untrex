<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Cotizaciones extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = [
        'fecha',
        'ufv',
        'dolar_oficial',
        'dolar_compra',
        'dolar_venta',
        'status'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}