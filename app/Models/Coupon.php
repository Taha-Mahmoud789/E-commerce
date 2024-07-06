<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'discount_type',
        'expires_at',
        'is_active',
    ];

    protected $dates = ['expires_at'];

       //  Get the identifier that will be stored in the subject claim of the JWT.
    
    //  @return mixed
    
   public function getJWTIdentifier()
   {
       return $this->getKey();
   }

    //     Return a key value array, containing any custom claims to be added to the JWT.
    
    //      @return array
      
   public function getJWTCustomClaims()
   {
       return [];
   }
}

