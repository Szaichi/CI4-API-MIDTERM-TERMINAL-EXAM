<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    // ROLE FILTER //
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('user');

        if (!$user) { // check login
            return redirect()->to('/login');
        }

        if (!in_array($user['role'], $arguments)) { // check allowed roles
            return redirect()->to('/unauthorized');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}