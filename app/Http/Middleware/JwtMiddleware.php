<?php

namespace App\Http\Middleware;

use App\helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $excludedPaths = [
            'api/register',
            'api/login'
        ];

        if (in_array($request->path(), $excludedPaths)) {
            return $next($request);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return ResponseHelper::error('Token not valid', 401);
        }

        return $next($request);
    }

}
