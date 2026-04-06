<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    protected $fillable = [
        'workout_plan_id', 'name', 'equipment', 
        'position', 'sets', 'reps', 'rest',
    ];

    public function plan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}