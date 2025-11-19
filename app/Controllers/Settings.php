<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\UserModel;

class Settings extends BaseController
{
    protected $settingsModel;
    protected $userModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        $data = [
            'settings'       => $this->settingsModel->first(),
            'currentUser'    => $userId ? $this->userModel->find($userId) : null,
            'users'          => $this->userModel->findAll(),
            'validation'     => service('validation'),
            'accountErrors'  => session()->getFlashdata('account_errors') ?? [],
        ];

        return view('Dashboard/Settings', $data);
    }

    public function save()
    {
        $rules = [
            'shop_name'      => 'required|min_length[3]',
            'shop_address'   => 'required',
            'contact_number' => 'required',
            'opening_hours'  => 'required',
            'default_tax'    => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please correct the highlighted errors.');
        }

        $data = [
            'shop_name'      => $this->request->getPost('shop_name'),
            'shop_address'   => $this->request->getPost('shop_address'),
            'contact_number' => $this->request->getPost('contact_number'),
            'opening_hours'  => $this->request->getPost('opening_hours'),
            'default_tax'    => $this->request->getPost('default_tax'),
        ];

        $existing = $this->settingsModel->first();

        if ($existing) {
            $data['id'] = $existing['id'];
        }

        $this->settingsModel->save($data);

        return redirect()->to('/settings')->with('success', 'Settings saved successfully.');
    }

    public function account()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to('/login');
        }

        $rules = [
            'account_username' => 'required|min_length[3]',
            'account_email'    => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'account_password' => 'permit_empty|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('account_errors', $this->validator->getErrors());
        }

        $data = [
            'id'       => $userId,
            'username' => $this->request->getPost('account_username'),
            'email'    => $this->request->getPost('account_email'),
        ];

        $password = $this->request->getPost('account_password');
        if (! empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->save($data);

        session()->set([
            'username' => $data['username'],
            'email'    => $data['email'],
        ]);

        return redirect()->to('/settings')->with('account_success', 'Account information updated.');
    }

    public function addUser()
    {
        $rules = [
            'new_username' => 'required|min_length[3]',
            'new_email'    => 'required|valid_email|is_unique[users.email]',
            'new_password' => 'required|min_length[5]',
            'new_role'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/settings/users/new')->withInput()->with('user_errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'username' => $this->request->getPost('new_username'),
            'email'    => $this->request->getPost('new_email'),
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('new_role'),
        ]);

        return redirect()->to('/settings/users/new')->with('user_form_success', 'New user added successfully.');
    }

    public function newUserForm()
    {
        $data = [
            'userErrors' => session()->getFlashdata('user_errors') ?? [],
        ];

        return view('Dashboard/AddUser', $data);
    }
}

?>

