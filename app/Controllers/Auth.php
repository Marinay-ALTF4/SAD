<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('Auth/Login');
    }

    public function login()
    {
        $identity = $this->request->getPost('login_identity');
        $password = $this->request->getPost('password');

        // Validate input
        if (empty($identity) || empty($password)) {
            return redirect()->back()->with('error', 'Email/Username and password are required');
        }

        // Verify user credentials from database
        $user = $this->userModel->verifyUser($identity, $password);

        if ($user) {
            // Set session data
            session()->set([
                'isLoggedIn' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
