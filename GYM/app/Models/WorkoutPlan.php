<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    protected $fillable = ['client_id', 'name', 'frequency', 'duration'];

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}