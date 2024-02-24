<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Traits\General;
use Closure;
use Illuminate\Http\Request;

class CheckCourseDraft
{
    use General;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $course = Course::where('uuid', $request->route('uuid'))->firstOrFail();
        if (!$course->isStillNew()) {
            $this->showToastrMessage('error', 'This course is not in draft mode');
            return redirect()->back();
        }
        return $next($request);
    }
}