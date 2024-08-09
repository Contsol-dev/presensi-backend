<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // ip dari 192.168.1.1 sampai 192.168.1.10
        $allowedIpRangeStart = ip2long('192.168.1.1');
        $allowedIpRangeEnd = ip2long('192.168.1.10');

        $userIp = ip2long($request->ip());

        if ($userIp < $allowedIpRangeStart || $userIp > $allowedIpRangeEnd) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        //untuk semua dari ip 192.168.1.x
        // $allowedIps = [
        //     '192.168.1.'  // Awalan IP yang diizinkan
        // ];

        // $ip = $request->ip();
        // $isAllowed = false;

        // foreach ($allowedIps as $allowedIp) {
        //     if (preg_match("/^" . preg_quote($allowedIp, '/') . "/", $ip)) {
        //         $isAllowed = true;
        //         break;
        //     }
        // }

        // if (!$isAllowed) {
        //     return response()->json(['message' => 'Unauthorized.'], 403);
        // }

        return $next($request);
    }
}
