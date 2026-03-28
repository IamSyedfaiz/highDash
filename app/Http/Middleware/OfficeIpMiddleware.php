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
        // 1. Get the TRUE client IP (handling proxies/load balancers on live server)
        $currentIp = $request->server('HTTP_CF_CONNECTING_IP') ??
            $request->server('HTTP_X_FORWARDED_FOR') ??
            $request->ip();

        // Handle multiple IPs in X-Forwarded-For (take the first one)
        if (strpos($currentIp, ',') !== false) {
            $currentIp = trim(explode(',', $currentIp)[0]);
        }

        // 2. Safely read allowed IP from file
        $ipFile = storage_path('app/office_ip.txt');
        $allowedIp = '';
        if (file_exists($ipFile)) {
            $allowedIp = trim(file_get_contents($ipFile));
        }

        // 3. Define globally trusted IPs (Localhost / Server IP)
        $trustedIps = [
            '127.0.0.1',
            '::1',
            '106.219.162.135' // The specific server IP the user mentioned
        ];

        // 4. Checking logic
        if (in_array($currentIp, $trustedIps) || $currentIp === $allowedIp) {
            return $next($request);
        }

        // If file is empty or doesn't exist, register the first untrusted IP that hits it
        if (empty($allowedIp)) {
            file_put_contents($ipFile, $currentIp);
            return $next($request);
        }

        // 5. Block access
        return response(view('errors.wrong_network'));
    }
}
