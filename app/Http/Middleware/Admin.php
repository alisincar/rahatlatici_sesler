<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->check()) {
            return $next($request);
        } else {
            return redirect()->route('admin.auth.login');
        }
    }
}
