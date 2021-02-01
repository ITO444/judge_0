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
    public function handle($request, Closure $next, $level, $page = null)
    {
        $user = $request->user();
        if(!$user) {
            return redirect('/login');
        }
        $userLevel = min($user->level, $user->temp_level);
        if($userLevel == 0) {
            return abort(418);
        }
        $contest = $user->contestNow();
        if($contest != null){
            if($page == null){
                return redirect("/contest/$contest_id");
            }
        }elseif($userLevel < $level) {
            return abort(404);
        }
        return $next($request);
    }
}
