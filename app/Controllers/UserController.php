<?php

namespace App\Controllers;

use App\Models\RequestModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('users/index', [
            'users' => $this->userModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('users/form', [
            'user'   => null,
            'action' => site_url('users'),
        ]);
    }

    public function store(): ResponseInterface
    {
        $rules = [
            'name'             => 'required|max_length[100]',
            'email'            => 'required|valid_email|max_length[100]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));

        if ($this->userModel->where('email', $email)->first() !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'That email address is already in use.');
        }

        $inserted = $this->userModel->insert([
            'id'       => $this->uuid(),
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_admin' => $this->request->getPost('is_admin') !== null,
        ]);

        if ($inserted === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create the user.');
        }

        return redirect()->to('/users')->with('message', 'User created.');
    }

    public function edit($id = null): ResponseInterface|string
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $user = $this->userModel->find($id);

        if ($user === null) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        return view('users/form', [
            'user'   => $user,
            'action' => site_url('users/' . $user['id']),
        ]);
    }

    public function update($id = null): ResponseInterface
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $user = $this->userModel->find($id);

        if ($user === null) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $rules = [
            'name'             => 'required|max_length[100]',
            'email'            => 'required|valid_email|max_length[100]',
            'password'         => 'permit_empty|min_length[8]',
            'password_confirm' => 'required_with[password]|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));

        if ($email !== strtolower($user['email'])) {
            $exists = $this->userModel
                ->where('email', $email)
                ->where('id !=', $id)
                ->first();

            if ($exists !== null) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'That email address is already in use.');
            }
        }

        $update = [
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => $email,
            // Never let an administrator remove their own admin rights.
            'is_admin' => $id === $this->currentUser()['id']
                ? true
                : $this->request->getPost('is_admin') !== null,
        ];

        $password = (string) $this->request->getPost('password');

        if ($password !== '') {
            $update['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (! $this->userModel->update($id, $update)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update the user.');
        }

        return redirect()->to('/users')->with('message', 'User updated.');
    }

    public function delete($id = null): ResponseInterface
    {
        if (! $this->isUuid($id)) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        if ($id === $this->currentUser()['id']) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account.');
        }

        if ($this->userModel->find($id) === null) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $requestCount = (new RequestModel())->where('user_id', $id)->countAllResults();

        if ($requestCount > 0) {
            return redirect()
                ->to('/users')
                ->with('error', 'This user has requests and cannot be deleted.');
        }

        if (! $this->userModel->delete($id)) {
            return redirect()->to('/users')->with('error', 'Could not delete the user.');
        }

        return redirect()->to('/users')->with('message', 'User deleted.');
    }
}
