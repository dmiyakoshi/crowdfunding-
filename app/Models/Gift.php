<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    public function getImagePathAttribute()
    {
        // $photos = $this->photos; //画像を複数にするならこちらにする
        // $paths = [];

        // for ($i = 0; $i < count($photos); $i++) {
        //     $paths[$i] = 'gifts/' . $photos[$i]->path;
        // }

        // return $paths;

        return '/gifts/' . $this->photo->path;
    }
    public function getImageUrlAttribute()
    {
        // $imagePaths = $this->image_paths; //複数に変更する場合ならこちらをつかう
        // $imageUrls = [];

        // if (config('filesystems.default') == 'gcs') {
        //     for ($i = 0; $i < count($imagePaths); $i++) {
        //         $imageUrls[$i] = Storage::temporaryUrl($imagePaths[$i], now()->addMinutes(5));
        //     }
        // } else {
        //     for ($i = 0; $i < count($imagePaths); $i++) {
        //         $imageUrls[$i] = Storage::url($imagePaths[$i]);
        //     }
        // }

        // return $imageUrls;

        if (config('filesystems.default') == 'gcs') {
            return Storage::temporaryUrl($this->image_path, now()->addMinutes(5));
        }
        return Storage::url($this->image_path);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function photo()
    {
        return $this->hasOne(Photo::class);
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }
}
