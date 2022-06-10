<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\PlanCuentas;
use DB;

class PlanCuentas extends Model
{
    protected $table = 'plan_cuentas';
    protected $fillable = [
        'nombre',
        'codigo',
        'parent_id',
        'descripcion',
        'cuenta_detalle',
        'cheque',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];

    public function hijos(){
        return $this->hasMany(PlanCuentas::class,'parent_id','id');
      }
}