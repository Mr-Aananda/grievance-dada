<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public const DEFAULT_USER_NAME = 'Administrator';
    public const DEFAULT_USER_EMAIL = 'admin@dadadhaka.com';
    public const DEFAULT_USER_PHONE = '01971072007';
    public const DEFAULT_DEPARTMENT_NAME = 'IT Department';
    public const DEFAULT_DESIGNATION = 'Senior Executive(Software)';
    public const DEFAULT_EMPLOYEE_ID = '24158';
    public const DEFAULT_PASSWORD = 'password';

    /**
     * Get the IT Department ID (based on the seeded data)
     */
    protected function getITDepartmentId(): int
    {
        return Department::where('name', self::DEFAULT_DEPARTMENT_NAME)->value('id') ?? 1;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'emp_id' => $this->faker->unique()->numerify('EMP#####'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'department_id' => $this->getITDepartmentId(),
            'designation' => $this->faker->jobTitle(),
            'section_id' => null,
            'user_id' => null,
            'status' => true,
            'email_verified_at' => now(),
            'password' => Hash::make(self::DEFAULT_PASSWORD),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Create the default admin user.
     *
     * @return static
     */
    public function default(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => self::DEFAULT_USER_NAME,
                'emp_id' => self::DEFAULT_EMPLOYEE_ID,
                'email' => self::DEFAULT_USER_EMAIL,
                'phone' => self::DEFAULT_USER_PHONE,
                'department_id' => $this->getITDepartmentId(),
                'designation' => self::DEFAULT_DESIGNATION,
                'section_id' => null,
                'user_id' => null,
                'status' => true,
                'password' => Hash::make(self::DEFAULT_PASSWORD),
            ];
        });
    }
}
