<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
    'client_id', 'plan_name', 'duration', 'amount',
    'start_date', 'end_date', 'status', 'notes',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}