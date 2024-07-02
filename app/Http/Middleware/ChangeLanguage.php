<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLanguage
{
   
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale('en');

        if(isset($request -> lang) && $request -> lang == 'ar'){
            app()->setLocale('ar');
        }
        return $next($request);
    }
}
