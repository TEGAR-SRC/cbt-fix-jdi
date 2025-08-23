<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'      => 'Administrator',
                'password'  => bcrypt('admin123'),
                'role'      => 'admin',
                'subject'   => null,
            ]
        );

        // Teacher user (Math)
        User::updateOrCreate(
            ['email' => 'guru@gmail.com'],
            [
                'name'      => 'Guru Matematika',
                'password'  => bcrypt('guru123'),
                'role'      => 'teacher',
                'subject'   => 'Matematika',
            ]
        );
    }
}