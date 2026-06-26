<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function login(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $admin    = $this->adminModel->findByUsername($username);

        if ($admin && $this->passwordIsValid($password, $admin)) {
            session()->set([
                'logged_in'  => true,
                'admin_id'   => $admin['id'],
                'user_id'    => $admin['id'],
                'nama_admin' => $admin['nama'],
                'role'       => $admin['role'],
            ]);

            return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $admin['nama']);
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }

    private function passwordIsValid(string $password, array $admin): bool
    {
        $storedPassword = (string) $admin['password'];

        if (password_verify($password, $storedPassword)) {
            if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
                $this->adminModel->update($admin['id'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                ]);
            }

            return true;
        }

        if (hash_equals($storedPassword, $password)) {
            $this->adminModel->update($admin['id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            return true;
        }

        return false;
    }
}
