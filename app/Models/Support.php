<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

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
        return $this->hasOne(Gift::class);
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    public function plan()
    {
        return $this->hasOne(Plan::class);
    }
}
