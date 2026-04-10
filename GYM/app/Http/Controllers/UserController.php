<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  function Log(){
    return view('login');
}

function Register() {
    return view('register');
}

public function Membership()
{
    $client = \App\Models\Client::find(session('client_id'));
    
    // Get active subscription
    $activeSubscription = \App\Models\Subscription::where('client_id', $client->id)
        ->where('status', 'active')
        ->latest()
        ->first();
    
    // Get all subscription history
    $subscriptionHistory = \App\Models\Subscription::where('client_id', $client->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('membership', compact('client', 'activeSubscription', 'subscriptionHistory'));
}

function Plan() {
    return view('plans');
}


public function Equipment()
{
    $equipments = \App\Models\Equipment::where('is_available', true)
        ->orderBy('category')
        ->get()
        ->groupBy('category');

    return view('equipment', compact('equipments'));
}


function Feedback() {
    return view('feedback');
}

public function Home()
{
    $notices = \App\Models\Notice::where('is_active', true)
        ->orderBy('posted_at', 'desc')
        ->get();

    return view('home', compact('notices'));
}
}
