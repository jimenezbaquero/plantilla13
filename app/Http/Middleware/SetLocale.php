<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');
        
        if (!$locale) {
            $locale = $request->getPreferredLanguage(['es', 'en']);
            $locale = substr($locale, 0, 2);
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
