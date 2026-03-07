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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect('login');
        }

        foreach ($roles as $roleGroup) {
            $roleNames = explode(',', $roleGroup);
            foreach ($roleNames as $name) {
                if ($user->hasRole(trim($name))) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
