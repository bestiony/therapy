<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class OrMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$middlewares)
    {

        foreach($middlewares as $middleware){
            $middleware_class = Route::getMiddleware()[$middleware];
            $response = App::make($middleware_class)->handle($request, $next)->getStatusCode();
            // dd($response);
            if ($response == 200){
                return $next($request);
            }
        }
        return redirect()->back();
    }
}
