<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function showLogin(): ResponseInterface|string
    {
        if ($this->currentUser() !== null) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function login(): ResponseInterface
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        if (! service('auth')->attempt($email, $password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        return redirect()
            ->to('/')
            ->with('message', 'Welcome back, ' . session('user_name') . '.');
    }

    public function showRegister(): ResponseInterface|string
    {
        if ($this->currentUser() !== null) {
            return redirect()->to('/');
        }

        return view('auth/register');
    }

    public function register(): ResponseInterface
    {
        $rules = [
            'name'             => 'required|max_length[100]',
            'email'            => 'required|valid_email|max_length[100]|is_unique[user.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = $this->uuid();

        $inserted = (new \App\Models\UserModel())->insert([
            'id'       => $id,
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_admin' => false,
        ]);

        if ($inserted === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create your account. Please try again.');
        }

        $user = (new \App\Models\UserModel())->find($id);

        if ($user !== null) {
            service('auth')->login($user);
        }

        return redirect()->to('/')->with('message', 'Your account was created.');
    }

    public function logout(): ResponseInterface
    {
        service('auth')->logout();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
