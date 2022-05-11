<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PlanCuentas extends Model
{
    //protected $table = 'plan_cuentas';
    public static $TableName='plan_cuentas';
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

    public function __construct(array $attributes = [])
    {
        $this->table=self::$TableName;
        parent::__construct($attributes);
    }
}