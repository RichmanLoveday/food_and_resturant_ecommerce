<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySetting extends Model
{
    protected $fillable = ['key', 'value'];
}