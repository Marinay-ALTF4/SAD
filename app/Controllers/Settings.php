<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\UserModel;

class Settings extends BaseController
{
    protected $settingsModel;
    protected $userModel;
    private array $roles = ['admin', 'staff'];

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        $data = [
            'settings'             => $this->settingsModel->first(),
            'currentUser'          => $userId ? $this->userModel->find($userId) : null,
            'users'                => $this->userModel->findAll(),
            'validation'           => service('validation'),
            'accountErrors'        => session()->getFlashdata('account_errors') ?? [],
            'userErrors'           => session()->getFlashdata('user_errors') ?? [],
            'accountSuccess'       => session()->getFlashdata('account_success'),
            'userFormSuccess'      => session()->getFlashdata('user_form_success'),
            'userManagementNotice' => session()->getFlashdata('user_management_notice'),
            'userManagementError'  => session()->getFlashdata('user_management_error'),
        ];

        return view('Dashboard/Settings', $data);
    }

    public function save()
    {
        return redirect()->to('/settings');
    }

    public function account()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to('/login');
        }

        return redirect()->to('/settings');
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
            return redirect()->to('/settings')->withInput()->with('user_errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'username' => $this->request->getPost('new_username'),
            'email'    => $this->request->getPost('new_email'),
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('new_role'),
        ]);

        return redirect()->to('/settings')->with('user_form_success', 'New user added successfully.');
    }

    public function updateUserRole($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/settings')->with('user_management_error', 'User not found.');
        }

        $role = $this->request->getPost('role');

        if (! in_array($role, $this->roles, true)) {
            return redirect()->to('/settings')->with('user_management_error', 'Invalid role selected.');
        }

        if ((int) session()->get('user_id') === (int) $id && $role !== 'admin') {
            return redirect()->to('/settings')->with('user_management_error', 'You cannot remove your own admin access.');
        }

        $this->userModel->update($id, ['role' => $role]);

        return redirect()->to('/settings')->with('user_management_notice', 'User role updated.');
    }

    public function deleteUser($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/settings')->with('user_management_error', 'User not found.');
        }

        if ((int) session()->get('user_id') === (int) $id) {
            return redirect()->to('/settings')->with('user_management_error', 'You cannot delete your own account while logged in.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/settings')->with('user_management_notice', 'User removed.');
    }

    public function updateUserProfile($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/settings')->with('user_management_error', 'User not found.');
        }

        $rules = [
            'edit_username' => 'required|min_length[3]',
            'edit_email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'edit_password' => 'permit_empty|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/settings')
                ->withInput()
                ->with('user_management_error', 'Please correct the highlighted user fields.');
        }

        $payload = [
            'id'       => $id,
            'username' => $this->request->getPost('edit_username'),
            'email'    => $this->request->getPost('edit_email'),
        ];

        $password = $this->request->getPost('edit_password');
        if (! empty($password)) {
            $payload['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->save($payload);

        return redirect()->to('/settings')->with('user_management_notice', 'User profile updated.');
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

