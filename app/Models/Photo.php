<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
            'name',
            'path',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
