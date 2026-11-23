<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;

class UserSeed extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        if(!$userModel->where('name', 'admin')->first()) {
            $userModel->insert([
                'name'     => 'admin',
                'email'    => 'admin@example.com',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'is_admin' => true,
            ]);
            CLI::Write('Admin user created successfully.', 'green');
        } else {
            CLI::Write('Admin user already exists. Skipping creation.', 'yellow');
        }

        if(!$userModel->where('name', 'user')->first()) {
            $userModel->insert([
                'name'     => 'user',
                'email'    => 'user@example.com',
                'password' => password_hash('user', PASSWORD_DEFAULT),
                'is_admin' => false,
            ]);
            CLI::Write('Regular user created successfully.', 'green');
        } else {
            CLI::Write('Regular user already exists. Skipping creation.', 'yellow');
        }
    }
}
