<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Hospital
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!Auth::guard('hospital')->check()) {

            Log::info('Hospital Dash Failed');

            return redirect()
                ->route('hospital.login');
        }
        return $next($request);
    }
}