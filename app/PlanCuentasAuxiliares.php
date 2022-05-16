<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PlanCuentasAuxiliares extends Model
{
    protected $table = 'plan_cuentas_auxiliares';
    protected $fillable = [
        'tipo',
        'nombre',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}