<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class checkUserPermissions
{
    public function handle(Request $request, Closure $next, $level=1)
    {

        $user = Session::get('user');

        if ($user['level'] < $level) {
            return redirect('/productos');
        }

        return $next($request);
    }
}
