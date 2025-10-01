<?php

namespace App\Http\Middleware;

use App\Traits\AuthCheckTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthJwtMiddleware
{
    use AuthCheckTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->requireAuth($request);
        $request->JwtUser = $user;
        return $next($request);
    }
}
