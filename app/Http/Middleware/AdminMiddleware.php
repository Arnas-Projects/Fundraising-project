<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if (!auth()-> check()) {
            abort(403, 'Jūs turite būti prisijungęs, kad pasiektumėte šį puslapį.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Jūs neturite teisės pasiekti šio puslapio.');
        }

        return $next($request);

        // if (!auth()->check() || !auth()->user()->isAdmin()) {
        //     abort(403, 'Jūs neturite teisės pasiekti šio puslapio.');
        // }

        // return $next($request);

        // dd(auth()->user());

        // $user = auth()->user();
        // dd(method_exists($user, 'isAdmin'));
    }
}
