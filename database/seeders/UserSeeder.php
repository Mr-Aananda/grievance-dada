<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // default user create - check by email OR emp_id
        if (
            !User::where('email', UserFactory::DEFAULT_USER_EMAIL)
                ->orWhere('emp_id', UserFactory::DEFAULT_EMPLOYEE_ID)
                ->exists()
        ) {
            User::factory()->default()->create();
        }
    }
}
