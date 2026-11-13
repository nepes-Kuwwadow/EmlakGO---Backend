<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Bearer tokenini barlamak we ulanyjyny tapmak
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json([
                'message' => 'Token berilmedi. Ygtyýarnama zerur.',
            ], 401);
        }

        $apiToken = ApiToken::where('token', $bearerToken)->with('user')->first();

        if (!$apiToken || !$apiToken->user) {
            return response()->json([
                'message' => 'Nädogry token.',
            ], 401);
        }

        if ($apiToken->isExpired()) {
            return response()->json([
                'message' => 'Tokeniň möhleti doldy.',
            ], 401);
        }

        // Tokeniň soňky ulanylma wagtyny täzelemek
        $apiToken->update(['last_used_at' => now()]);

        // Ulanyjyny request-e bellemek
        $request->setUserResolver(fn () => $apiToken->user);
        auth()->setUser($apiToken->user);

        return $next($request);
    }
}
