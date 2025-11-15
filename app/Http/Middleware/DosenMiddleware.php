<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class DosenMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->auth['role'] !== 'dosen') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak, hanya dosen yang diperbolehkan.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
