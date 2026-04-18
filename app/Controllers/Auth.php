<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        helper(['form', 'url']);
    }

    // Tampilkan halaman login
    public function index(): string
    {
        // Kalau sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    // Proses login
    public function login(): \CodeIgniter\HTTP\RedirectResponse
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->findByUsername($username);

        // Cek user exist & password cocok
        if ($admin && password_verify($password, $admin['password'])) {
            session()->set([
                'logged_in'  => true,
                'admin_id'   => $admin['id'],
                'nama_admin' => $admin['nama_admin'],
            ]);
            return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . $admin['nama_admin']);
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }

    // Logout
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }
}