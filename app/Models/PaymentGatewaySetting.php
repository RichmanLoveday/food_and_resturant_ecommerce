<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class PaymentGatewaySetting extends Model implements HasMedia
{
    use \Spatie\MediaLibrary\InteractsWithMedia;
    protected $fillable = ['key', 'value'];
}