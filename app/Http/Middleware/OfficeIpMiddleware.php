<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class OfficeIpMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentIp = $request->ip();
        $ipFile = storage_path('app/office_ip.txt');

        if (!file_exists($ipFile)) {
            file_put_contents($ipFile, $currentIp);
        }

        $allowedIp = trim(file_get_contents($ipFile));

        if ($currentIp !== $allowedIp) {
            return response(view('errors.wrong_network'));
        }

        return $next($request);
    }
}
