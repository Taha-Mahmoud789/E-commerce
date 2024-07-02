<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Product extends Model implements JWTSubject
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    // const CREATED_AT = 'Product_created_at';
    // const UPDATED_AT = 'Product_updated_at';
    // const DELETED_AT = 'Product_deleted_at';

    ///Using the Custom Accessors

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }

    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('m-d H:i');
    // }
    // public function getDeletedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d H:i:s');
    // }
    /*//////////////////////////////////// */
    /**
     * Define the relationship with the Inventory model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Check if the product is available in inventory.
     *
     * @return bool
     */
    public function isAvailable()
    {
        $inventory = $this->inventory()->first();
        return $inventory && $inventory->quantity > 0;
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }




    /*//////////////////////////////////// */
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
