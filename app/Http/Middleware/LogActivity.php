<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);

        // Log activity
        $customer = Auth::user();
        ActivityLog::create([
            'user_id' => $customer?->id,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'request_payload' => $request->except(['password', 'password_confirmation']),
            'response_payload' => $response->getContent() ? json_decode($response->getContent(), true) : null, // Safely handle JSON decoding
        ]);

        return $response;
    }
}
