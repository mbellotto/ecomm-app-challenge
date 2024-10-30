<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->authenticate($credentials['email'], $credentials['password'])) {
            Log::channel('productos')->info('POST /login Access granted to user with email={email}', ['email' => $credentials['email']]);
            return redirect()->intended('productos');
        }

        Log::channel('productos')->info('POST /login Access refused to user with email={email}', ['email' => $credentials['email']]);
        return back()->withErrors([
            'email' => 'Por favor verifique sus credenciales e intentelo nuevamente',
        ]);
    }

    public function logout(Request $request)
    {
        Log::channel('productos')->info('POST /logout Start');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
