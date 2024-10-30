<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class checkAuth
{
    public function handle(Request $request, Closure $next, $level=1)
    {
        $authService = app('App\Services\AUthService');

        $user = Session::get('user');

        if (!$user) {
            $authService->logout();
            return redirect('/login');
        }

        return $next($request);
    }
}
