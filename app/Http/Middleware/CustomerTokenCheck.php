<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerTokenCheck
{

    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        if (empty($user->id)) {
            return response()->json(['success' => 9, 'message' => "Please Login"]);
        } else {
            if ($user->is_block == 'yes') {
                return response()->json(['success' => 9, 'message' => "Your Account Was Blocked,Please Conatct Admin"]);
            }
            return $next($request);
        }
    }
}
