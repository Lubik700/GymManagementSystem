<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientLoginController extends Controller
{
    public function showLogin()
    {
        // Points to your existing login blade (the one UserController used to handle)
        return view('login'); // change 'login' to match your exact blade file name
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        if ($client->status !== 'active') {
            $messages = [
                'pending'  => 'Your registration is pending admin approval. Please visit the gym.',
                'client'   => 'Your account is awaiting subscription activation. Please visit the gym.',
                'inactive' => 'Your account has been deactivated. Please contact the gym.',
            ];
            return back()->withErrors([
                'email' => $messages[$client->status] ?? 'Your account is not active.'
            ])->withInput();
        }

        // Store client in session
        session([
            'client_id'   => $client->id,
            'client_name' => $client->name,
            'client_email'=> $client->email,
        ]);
        
        if ($request->boolean('remember')) {
        cookie()->queue(
            cookie('remember_client', $client->id, 60 * 48) // 48 hours in minutes
        );
        }
        return redirect()->route('home');
    }

    public function logout(Request $request)
{
    session()->forget(['client_id', 'client_name', 'client_email']);

    // Clear remember me cookie on logout
    cookie()->queue(cookie()->forget('remember_client'));

    return redirect()->route('login');
}
}