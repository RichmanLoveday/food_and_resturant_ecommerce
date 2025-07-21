<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'phone_one',
        'phone_two',
        'mail_one',
        'mail_two',
        'address',
        'map_link',
    ];
}
