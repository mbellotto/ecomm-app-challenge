<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthService
{
    private $users;

    public function __construct()
    {
        $this->users = collect([
            [
                'email' => 'manager@challenge.com',
                'password' => 'password',
                'role' => 'admin',
                'level' => 111,
            ],
            [
                'email' => 'operator@challenge.com',
                'password' => 'password',
                'role' => 'operator',
                'level' => 11,
            ],
            [
                'email' => 'user@challenge.com',
                'password' => 'password',
                'role' => 'user',
                'level' => 1,
            ],
        ]);
    }

    public function authenticate($email, $password)
    {

        $isAuthenticated = false;

        $key = $this->users->search(function ($user) use ($email, $password) {
            return $user['email'] === $email && $user['password'] === $password;
        });

        if ($key !== false ) {
            Session::put('user', $this->users[$key]);
            $isAuthenticated = true;
        }

        return $isAuthenticated;
    }

    public function getUser()
    {
        return Session::get('user');
    }

    public function logout()
    {
        Session::forget('user');
        Session::regenerateToken();
    }
}
