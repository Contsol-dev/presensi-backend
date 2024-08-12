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
        // === {{ IP dari 192.168.1.1 sampai 192.168.1.10 }} ===
        // $allowedIpRangeStart = ip2long('192.168.1.1');
        // $allowedIpRangeEnd = ip2long('192.168.1.50');

        // $userIp = ip2long($request->ip());

        // if ($userIp < $allowedIpRangeStart || $userIp > $allowedIpRangeEnd) {
        //     return response()->json(['message' => 'Unauthorized.'], 403);
        // }

        // === {{ IP regex, untuk semua user dari prefix di dalam array }} ===
        $allowedIps = [
            '192.168.1.', //untuk semua dari ip 192.168.1.x
            '192.168.100.',
            '10.10.73.'
        ];

        $ip = $request->ip();
        $isAllowed = false;

        foreach ($allowedIps as $allowedIp) {
            if (preg_match("/^" . preg_quote($allowedIp, '/') . "/", $ip)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // === {{ untuk IP yg didaftarkan }} ===
        // $allowedIps = [
        //     // '103.208.205.166',
        //     '192.168.1.10'
        // ];

        // if (!in_array($request->ip(), $allowedIps)) {
        //     return response()->json([
        //         'message' => 'Unauthorized.'
        //     ], 403);
        // }

        return $next($request);
    }
}
