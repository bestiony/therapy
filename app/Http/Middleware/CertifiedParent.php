<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CertifiedParent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            if (auth()->user()->role == USER_ROLE_PARENT && auth()->user()->certified_parent->status == 1) {
                return $next($request);
            } else {
                abort('403');
            }
        } else {
            return redirect()->to('/install');
        }
    }
}
