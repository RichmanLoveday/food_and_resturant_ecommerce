<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppDownloadSection extends Model
{
    protected $fillable = [
        'image',
        'background',
        'title',
        'short_description',
        'apple_store_link',
        'play_store_link',
    ];
}