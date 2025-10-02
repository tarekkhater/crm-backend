<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */


    public function handle($request, Closure $next)
    {

        if($request->route()->named('logout')) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect('/');
        }
       if($request->route()->uri()=='login'){
           if (auth()->check()) {
               return redirect('/');
           }

       }

        return parent::handle($request, $next);
    }
}
