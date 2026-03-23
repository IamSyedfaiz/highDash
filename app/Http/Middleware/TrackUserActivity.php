<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track active authenticated users on non-API/json requests
        if (Auth::check() && !$request->expectsJson()) {
            $path = $request->path();

            // Ignore some debug and generic asset paths
            $ignored_paths = ['_debugbar', '_ignition', 'broadcasting', 'livewire', 'manifest.json', 'favicon.ico'];

            $ignore = false;
            foreach ($ignored_paths as $ignored) {
                if (str_starts_with($path, $ignored)) {
                    $ignore = true;
                    break;
                }
            }

            if (!$ignore) {
                $method = $request->method();
                $action = $method === 'GET' ? 'Page View' : 'Form Submit (' . $method . ')';
                $title = ($method === 'GET' ? "Visited: /" : "Submitted: /") . $path;

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $action,
                    'description' => $title,
                    'model_type' => null,
                    'model_id' => null,
                    'properties' => [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->fullUrl(),
                        'browser_activity' => true
                    ]
                ]);
            }
        }

        return $response;
    }
}
