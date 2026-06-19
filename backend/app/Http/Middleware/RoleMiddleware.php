<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user =  $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->loadmissing('role');

        if (!$user->role ||
    !in_array($user->role->name, explode('|', $roles), true)) {
    return response()->json(['message' => 'Forbidden'], 403);
}
    
        return $next($request);
    }
}
