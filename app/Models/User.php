<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

use App\Consorcios;
use App\Socios;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'consorcio_id',
        'socio_id',
        'username',
        'name',
        'email',
        'password',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function consorcio(){
        return $this->belongsTo(Consorcios::class,'consorcio_id','id');
    }

    public function socio(){
        return $this->belongsTo(Socios::class,'socio_id','id');
    }
    
    public function scopeBySocio($query, $socio){
        if($socio)  
            return $query->where('socio_id', $socio);
    }

    public function scopeByUsername($query, $username){
        if($username)  
            return $query->where('username', 'LIKE', '%' . $username . '%');
    }

    public function scopeByName($query, $name){
        if($name)  
            return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeByEmail($query, $email){
        if($email)  
            return $query->where('email', 'like', '%' . $email . '%');
    }
}
