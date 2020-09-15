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
    public function handle($request, Closure $next, $level, $special = 10, $to = 10)
    {
        $userLevel = $request->user()->level;
        if($userLevel == $special){
            $userLevel = $to;
        }
        if ($userLevel < $level) {
            return abort(404);
        }
        return $next($request);
    }
}
