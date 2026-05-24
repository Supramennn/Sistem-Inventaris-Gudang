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

        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');
        $admin    = $this->adminModel->findByUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            session()->set([
                'logged_in'  => true,
                'admin_id'   => $admin['id'],
                'nama_admin' => $admin['nama_admin'],
            ]);

            return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $admin['nama_admin']);
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}
