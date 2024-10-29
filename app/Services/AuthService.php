<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class AuthService
{
    private $users;

    public function __construct()
    {
        $this->users = collect([
            [
                'email' => 'admin@challenge.com',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'email' => 'operator@challenge.com',
                'password' => 'password',
                'role' => 'operator',
            ],
            [
                'email' => 'user@challenge.com',
                'password' => 'password',
                'role' => 'user',
            ],
        ]);
    }

    public function authenticate($email, $password)
    {

        $isAuthenticated = false;

        $key = $this->users->search(function ($user) use ($email, $password) {
            return $user['email'] === $email && $user['password'] === $password;
        });

        if ($key) {
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
