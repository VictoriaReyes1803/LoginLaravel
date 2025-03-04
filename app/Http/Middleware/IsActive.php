<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (!auth()->check()) {
            return redirect()->route('login.form'); 
        }

        $user = auth()->user();

        if (!$user->is_active) {
            return redirect()->route('login.form')->with([
                'message' => 'User not active',
            ]);
        }

        return $next($request); 
    }
}
