<?php namespace AlertME\Http\Middleware;

use Closure;

class Secure {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Make sure assets are secure if not local
        if (env('APP_ENV') !== 'local') {
           \Asset::$secure = true;
        }

        if (!$request->secure() && !env('APP_DEBUG') ) {
            return redirect()->secure($request->path());
        }

        return $next($request);
    }

}
