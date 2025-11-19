<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        return view('Auth/Login');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Example: check login (replace with DB logic)
        if ($email === "admin@gmail.com" && $password === "12345") {
            session()->set('isLoggedIn', true);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
