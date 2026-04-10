<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating'     => 'required|integer|min:1|max:5',
            'category'   => 'required|string',
            'title'      => 'required|string|max:255',
            'message'    => 'required|string',
            'suggestion' => 'nullable|string',
        ]);

        Feedback::create([
            'client_id'  => session('client_id'),
            'rating'     => $request->rating,
            'category'   => $request->category,
            'title'      => $request->title,
            'message'    => $request->message,
            'suggestion' => $request->suggestion,
        ]);

        return back()->with('success', 'Thank you for your feedback! 🎉');
    }
}