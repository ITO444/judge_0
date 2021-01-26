<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $level)
    {
        $user = $request->user();
        if(!$user) {
            return abort(404);
        }
        $userLevel = min($user->level, $user->temp_level);
        if($userLevel == 0) {
            return abort(418);
        }
        if($userLevel < $level) {
            return abort(404);
        }
        return $next($request);
    }
}
