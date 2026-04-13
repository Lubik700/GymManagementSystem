<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class AuthClient
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ Check session first
        if (session('client_id')) {
            $client = Client::find(session('client_id'));

            if ($client && $client->status === 'active') {
                return $next($request);
            }

            // Session exists but client not active
            session()->forget(['client_id', 'client_name', 'client_email']);
        }

        // ✅ Check remember me cookie if no session
        if ($request->cookie('remember_client')) {
            $clientId = $request->cookie('remember_client');
            $client = Client::find($clientId);

            if ($client && $client->status === 'active') {
                // Restore session from cookie
                session([
                    'client_id'    => $client->id,
                    'client_name'  => $client->name,
                    'client_email' => $client->email,
                ]);

                // Refresh cookie for another 48 hours
                cookie()->queue(
                    cookie('remember_client', $client->id, 60 * 48)
                );

                return $next($request);
            }

            // Cookie exists but client not valid — clear it
            cookie()->queue(cookie()->forget('remember_client'));
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'Please login to access this page.']);
    }
}