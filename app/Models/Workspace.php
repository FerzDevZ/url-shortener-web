<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
