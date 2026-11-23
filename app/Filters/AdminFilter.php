<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Allows only administrators through. Must run after the auth filter.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');

        if (! $auth->check()) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }

        if (! $auth->isAdmin()) {
            return redirect()->to('/')->with('error', 'Administrator access is required.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
