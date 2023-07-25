<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use DB;

class UserProyectos extends Model
{
    protected $table = 'user_proyectos';
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'estado'
    ];

    //use SoftDeletes;
    //protected $dates =['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function scopeByUser($query, $user){
        if($user){
            return $query->where('user_id',$user);
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado != null){
            return $query->where('estado',$estado);
        }
    }
}