<?php

namespace Database\Factories;

use App\Modules\Access\Role\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_name' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'role_id' => fn () => $this->resolveRoleId('user'),
            'must_change_password' => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $this->resolveRoleId('admin'),
        ]);
    }

    private function resolveRoleId(string $name): int
    {
        return Role::firstOrCreate(['name' => $name])->id;
    }
}
