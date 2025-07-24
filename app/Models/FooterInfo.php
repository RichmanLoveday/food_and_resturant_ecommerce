<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterInfo extends Model
{
    protected $fillable = ['short_info', 'address', 'phone', 'email', 'copyright'];
}