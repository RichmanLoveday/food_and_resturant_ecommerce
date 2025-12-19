<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AppDownloadSection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'image',
        'background',
        'title',
        'short_description',
        'apple_store_link',
        'play_store_link',
    ];
}
