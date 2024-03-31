<?php

namespace App\Http\Middleware;

use App\Exceptions\UserBlockedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('sanctum') && $request->user('sanctum')->isBlocked()) {
            throw new UserBlockedException($request->user()->blockReason());
        }
        return $next($request);
    }
}
