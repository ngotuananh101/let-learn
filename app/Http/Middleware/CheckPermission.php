<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @param mixed ...$permission
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$permission): Response
    {
        if ($request->expectsJson()){
            $check = $request->user()->hasAnyPermission($permission);
            if (!$check) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } else {
            if (! $request->user()->hasAnyPermission($permission)) {
                abort(403);
            }
        }
        return $next($request);
    }
}
