<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks'; // ✅ Force correct table name

    protected $fillable = [
        'client_id', 'rating', 'category', 'title', 'message', 'suggestion',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}