<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ComprobanteFacturas extends Model
{
    protected $table = 'comprobante_facturas';
    protected $fillable = [
        'compropbante_id',
        'factura_id',
        'estado'
    ];
    
    use SoftDeletes;
    protected $dates =['deleted_at'];
}