<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gift extends Model
{
    use HasFactory;

    public function getImagePathsAttribute()
    {
        return 'gifts/' . $this->photos->name;
    }
    public function getImageUrlsAttribute()
    {
        return Storage::url($this->image_paths);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }
}
