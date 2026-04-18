<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index(): string
    {
        $data['nama_admin'] = session()->get('nama_admin');
        return view('dashboard/index', $data);
    }
}