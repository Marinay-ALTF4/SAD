<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email'    => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'username' => 'staff',
                'email'    => 'staff@example.com',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'role'     => 'staff',
            ],
        ];

        // Insert multiple rows
        $this->db->table('users')->insertBatch($data);
    }
}       
