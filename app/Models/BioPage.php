<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BioPage extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'bio',
        'photo_path',
        'theme_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
