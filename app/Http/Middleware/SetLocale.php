<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use URL;
use Illuminate\Http\Request;

class SetLocale
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
        if($request->lang=='sv') {
            App::setLocale(request('langauge_code', $request->lang));
        }else{
            URL::defaults(['locale' => 'en']);        }

      return $next($request);
    }
}
