<?php

namespace App\Http\Middleware;

use App\Traits\General;
use Closure;
use Illuminate\Http\Request;

class CheckPodcastStatus
{
    use General;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $podcast_status = get_option('podcast_status');

        if (!$podcast_status) {
            $this->showToastrMessage('error', 'Podcasts are not available');
            return redirect()->back();
        }

        return $next($request);
    }
}
