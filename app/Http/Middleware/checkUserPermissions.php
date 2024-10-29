<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class checkUserPermissions
{
    public function handle(Request $request, Closure $next, $role='user')
    {
        $user = Session::get('user');

        if (!$user || $user['role'] !== $role) {
            return redirect('/login');
        }

        return $next($request);
    }
}
