<?php

namespace App\Models;

use App\Consts\UserConst;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

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

    protected $appends = [
        'relese_flag',
        'start_flag',
        'end_flag',
    ];

    public function myPaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination())
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }


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

    public function getReleseFlagAttribute()
    {
        $now = \Carbon\Carbon::now()->format("Y-m-d");

        return $now >= $this->relese_date; //今日の日付が募集開始日より後の日付ならtrue 募集が開始
    }

    public function getTotalAttribute()
    {
        $total = 0;

        $supports = $this->supports;

        foreach ($supports as $support) {
            $total += $support->money;
        }
        return $total;
    }

    public function getMoneyAttribute()
    {
        return $this->total;
    }

    public function getStartFlagAttribute()
    {
        if ($this->releseFlag) {
            return (($this->total / $this->goal) >= 0.1);
        } else {
            return true;
        }
    }

    public function getEndFlagAttribute()
    {
        $now = \Carbon\Carbon::now()->format("Y-m-d");

        return $now > $this->due_date; //今日の日付が募集期限より後の日付ならtrue 募集を終了
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
