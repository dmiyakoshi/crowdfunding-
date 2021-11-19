<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'heading_introduction',
        'introduction',
        'heading_do',
        'description_do',
        'heading_reason',
        'description_reason',
        'how_use_money',
        'relese_date',
        'due_date',
    ];

    public function getImagePathsAttribute()
    {
        return 'plans/' . $this->photos->name;
    }
    public function getImageUrlsAttribute()
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

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }
}
