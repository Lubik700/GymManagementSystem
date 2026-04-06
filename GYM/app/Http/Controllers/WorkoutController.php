<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use App\Models\WorkoutExercise;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    // Show all plans
    public function index()
    {
        $plans = WorkoutPlan::where('client_id', session('client_id'))
            ->with('exercises')
            ->latest()
            ->get();

        return view('plans', compact('plans'));
    }

    // Create new plan
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'frequency' => 'nullable|string|max:100',
            'duration'  => 'nullable|string|max:100',
        ]);

        WorkoutPlan::create([
            'client_id' => session('client_id'),
            'name'      => $request->name,
            'frequency' => $request->frequency,
            'duration'  => $request->duration,
        ]);

        return back()->with('success', 'Workout plan created successfully!');
    }

    // Update plan
    public function update(Request $request, WorkoutPlan $plan)
    {
        // Make sure plan belongs to logged in client
        if ($plan->client_id !== session('client_id')) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'name'      => 'required|string|max:255',
            'frequency' => 'nullable|string|max:100',
            'duration'  => 'nullable|string|max:100',
        ]);

        $plan->update($request->only('name', 'frequency', 'duration'));

        return back()->with('success', 'Workout plan updated successfully!');
    }

    // Delete plan
    public function destroy(WorkoutPlan $plan)
    {
        if ($plan->client_id !== session('client_id')) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $plan->delete();
        return back()->with('success', 'Workout plan deleted!');
    }

    // Add exercise to plan
    public function addExercise(Request $request, WorkoutPlan $plan)
    {
        if ($plan->client_id !== session('client_id')) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'name'      => 'required|string|max:255',
            'equipment' => 'nullable|string|max:100',
            'position'  => 'nullable|string|max:100',
            'sets'      => 'nullable|integer',
            'reps'      => 'nullable|string|max:50',
            'rest'      => 'nullable|string|max:50',
        ]);

        WorkoutExercise::create([
            'workout_plan_id' => $plan->id,
            'name'            => $request->name,
            'equipment'       => $request->equipment,
            'position'        => $request->position,
            'sets'            => $request->sets,
            'reps'            => $request->reps,
            'rest'            => $request->rest,
        ]);

        return back()->with('success', 'Exercise added!');
    }

    // Update exercise
    public function updateExercise(Request $request, WorkoutExercise $exercise)
    {
        if ($exercise->plan->client_id !== session('client_id')) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $exercise->update($request->only('name', 'equipment', 'position', 'sets', 'reps', 'rest'));
        return back()->with('success', 'Exercise updated!');
    }

    // Delete exercise
    public function destroyExercise(WorkoutExercise $exercise)
    {
        if ($exercise->plan->client_id !== session('client_id')) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $exercise->delete();
        return back()->with('success', 'Exercise deleted!');
    }
}