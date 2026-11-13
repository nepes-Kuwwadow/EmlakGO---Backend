<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Ulanyjynyň rol barlagçy
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Ygtyýarnama zerur.',
            ], 401);
        }

        if ($request->user()->role !== $role) {
            return response()->json([
                'message' => 'Bu amaly ýerine ýetirmäge rugsat ýok.',
            ], 403);
        }

        return $next($request);
    }
}
