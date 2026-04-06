<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title', 'content', 'type', 'is_active', 'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];
}