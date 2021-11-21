<?php

namespace App\Models;

use App\Consts\UserConst;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'goal',
        'heading_introduction',
        'introduction',
        'heading_do',
        'description_do',
        'heading_reason',
        'description_reason',
        'how_use_money',
        'method_id',
        'relese_date',
        'due_date',
    ];


    public function scopeMyPlan(Builder $query)
    {
        $query->where(
            'user_id',
            Auth::guard(UserConst::GUARD)->user()->id
        );

        return $query;
    }

    public function getImagePathsAttribute()
    {
        $photos = $this->photos;
        $paths = [];

        for ($i = 0; $i < count($photos); $i++) {
            $paths[$i] = 'plans/' . $photos[$i]->path;
        }

        return $paths;
    }
    public function getImageUrlsAttribute()
    {
        $imagePaths = $this->image_paths;
        $imageUrls = [];

        if (config('filesystems.default') == 'gcs') {
            for ($i = 0; $i < count($imagePaths); $i++) {
                $imageUrls[$i] = Storage::temporaryUrl($imagePaths[$i], now()->addMinutes(5));
            }
        } else {
            for ($i = 0; $i < count($imagePaths); $i++) {
                $imageUrls[$i] = Storage::url($imagePaths[$i]);
            }
        }

        return $imageUrls;
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
        return $this->belongsTo(Method::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }
}
