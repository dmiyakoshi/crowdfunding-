<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Plan extends Model
{
    use HasFactory;

    public function getImagePathAttribute()
    {
        return 'plans/' . $this->photos->name;
    }
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function method()
    {
        return $this->hasOne(Method::class);
    }

    public function gift()
    {
        return $this->hasMany(Gift::class);
    }
}
