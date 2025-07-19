<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'image',
        'title',
        'main_title',
        'description',
        'video_link',
    ];
}
