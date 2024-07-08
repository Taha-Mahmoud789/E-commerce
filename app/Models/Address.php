<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model implements JWTSubject
{
    use HasFactory;
    protected $tabel = 'addresses';
    protected $fillable = [
        'user_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
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
