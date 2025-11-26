<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Check if the request is for panel routes
        if ($request->is('panel*')) {
            return $request->expectsJson() ? null : '/';
        }
        
        return $request->expectsJson() ? null : '/';
    }
}
