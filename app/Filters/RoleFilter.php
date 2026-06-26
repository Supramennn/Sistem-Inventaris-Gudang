<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = (string) session()->get('role');
        $allowedRoles = $arguments ?? [];

        if ($allowedRoles === [] || in_array($role, $allowedRoles, true)) {
            return null;
        }

        return redirect()->to('/dashboard')
            ->with('error', 'Anda tidak memiliki akses untuk fitur tersebut.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
