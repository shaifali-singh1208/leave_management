<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => User::ROLE_MANAGER,
            'department_id' => 1,
        ]);

        User::create([
            'name' => 'Employee',
            'email' => 'employee@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => User::ROLE_EMPLOYEE,
            'manager_id' => $manager->id,
            'department_id' => 1,
        ]);

    }
}
