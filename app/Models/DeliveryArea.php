<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryArea extends Model
{
    protected $guarded = [];

    public function userAddress(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}