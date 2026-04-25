<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LeaveType::insert([
            [
                'name' => 'Annual Leave',
                'entitlement_days' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sick Leave',
                'entitlement_days' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Casual Leave',
                'entitlement_days' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
