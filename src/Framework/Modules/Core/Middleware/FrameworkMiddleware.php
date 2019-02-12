<?php

namespace Framework\Modules\Core\Middleware;


class FrameworkMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        /**
         * Check if we are in local mode, only allow build requests in local mode
         */
        if (app()->environment('local')) {
            return $next($request);
        }

        return abort(403);
    }
}
