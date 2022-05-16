<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Empresas extends Model
{
    protected $table = 'empresas';
    protected $fillable = [
        'nombre',
        'abreviatura',
        'direccion',
        'nit',
        'razon_social'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}