<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class ShowLoadingSpinner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!View::exists('components.spinner')) {
            // Ensure the view exists
            abort(404, 'Loading spinner view not found.');
        }

        View::share('loading_spinner', true);
        return $next($request);
    }
}
