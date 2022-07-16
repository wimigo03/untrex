<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Consorcios extends Model
{
    protected $table = 'consorcios';
    protected $fillable = [
        'nombre',
        'tipo',
        'estado'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];
}