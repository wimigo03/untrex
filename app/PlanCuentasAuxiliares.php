<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PlanCuentasAuxiliares extends Model
{
    protected $table = 'plan_cuentas_auxiliares';
    protected $fillable = [
        'proyecto_id',
        'tipo',
        'nombre',
        'reg_id',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}