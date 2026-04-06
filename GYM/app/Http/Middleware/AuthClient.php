<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class AuthClient
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('client_id')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Please login to access this page.']);
        }

        $client = Client::find(session('client_id'));

        if (!$client || $client->status !== 'active') {
            session()->forget(['client_id', 'client_name', 'client_email']);
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not active.']);
        }

        return $next($request);
    }
}