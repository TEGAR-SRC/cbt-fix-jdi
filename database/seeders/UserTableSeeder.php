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

        // Operator user
        User::updateOrCreate(
            ['email' => 'operator@gmail.com'],
            [
                'name'      => 'Operator Sekolah',
                'password'  => bcrypt('operator123'),
                'role'      => 'operator',
                'subject'   => null,
            ]
        );

        // Parent user
        User::updateOrCreate(
            ['email' => 'parent@gmail.com'],
            [
                'name'      => 'Wali Murid',
                'password'  => bcrypt('parent123'),
                'role'      => 'parent',
                'subject'   => null,
            ]
        );

        // Dinas user
        User::updateOrCreate(
            ['email' => 'dinas@gmail.com'],
            [
                'name'      => 'Dinas Pendidikan',
                'password'  => bcrypt('dinas123'),
                'role'      => 'dinas',
                'subject'   => null,
            ]
        );
    }
}