<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    // ✅ Force correct table name
    protected $table = 'equipments';

    protected $fillable = [
        'name', 'category', 'description', 'brand',
        'quantity', 'condition', 'is_available', 'image',
    ];
}