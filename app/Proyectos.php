<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Proyectos extends Model
{
    protected $table = 'proyectos';
    protected $fillable = [
        'empresa_id',
        'nombre',
        'abreviatura'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}