<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'fund_id',
        'money',
    ];

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
