<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->auth['role'] !== 'mahasiswa') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak, hanya mahasiswa yang diperbolehkan.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
