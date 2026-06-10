<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    const DEFAULT_RULES = [
        'administrator' => [
            'name' => 'Administrator',
            'is_permanent' => true,
        ],
        'korean-management' => [
            'name' => 'Korean Management',
            'is_permanent' => false,
        ],
        'office-staff' => [
            'name' => 'Office Staff',
            'is_permanent' => false,
        ],
    ];

    const ADMINISTRATOR_RULE_NAME = self::DEFAULT_RULES['administrator']['name'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::DEFAULT_RULES as $role) {
            Role::create($role);
        }

        // Assign administrator role to default account using email OR emp_id
        $adminUser = User::query()
            ->where('email', UserFactory::DEFAULT_USER_EMAIL)
            ->orWhere('emp_id', UserFactory::DEFAULT_EMPLOYEE_ID)
            ->first();

        if ($adminUser) {
            $adminUser->assignRole(self::ADMINISTRATOR_RULE_NAME);
        }
    }
}
