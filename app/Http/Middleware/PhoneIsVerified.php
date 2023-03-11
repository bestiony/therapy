<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PhoneIsVerified
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
        if (! $request->user()->userPhoneVerified() && get_option('registration_phone_number_verification') == 1) {
            return redirect()->route('user.phone.verification');
        }
        return $next($request);
    }
}
