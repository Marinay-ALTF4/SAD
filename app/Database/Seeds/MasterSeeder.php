<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Register individual seeders to make seeding predictable and repeatable
        $this->call('UserSeeder');
        $this->call('MenuSeeder');
        $this->call('ProductSeeder');
    }
}
