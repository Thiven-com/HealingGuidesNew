<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HospitalTokenCheck
{

    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        if (empty($user->id)) {
            return response()->json(['success' => 9, 'message' => "Please Login"]);
        } else {
            
            return $next($request);
        }
    }
}
