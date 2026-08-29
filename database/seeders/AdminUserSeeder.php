<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->makeUser(
            env('ADMIN_EMAIL'),
            env('ADMIN_USERNAME'),
            $this->resolvePassword('ADMIN_PASSWORD'),
            'admin'
        );

        $this->makeUser(
            env('DEMO_EMAIL'),
            env('DEMO_USERNAME'),
            $this->resolvePassword('DEMO_PASSWORD'),
            'user'
        );
    }

    private function resolvePassword(string $key): string
    {
        $password = env($key);
        if (! empty($password)) {
            return $password;
        }

        $generated = Str::password(16);
        $this->command?->warn("{$key} no definido en .env — generado: {$generated}");

        return $generated;
    }

    private function makeUser(string $email, string $userName, string $password, string $role): void
    {
        $user = User::withTrashed()->updateOrCreate(
            ['email' => $email],
            [
                'user_name' => $userName,
                'password' => $password,
            ]
        );

        $user->role = $role;
        $user->deleted_at = null;
        $user->save();

        $this->command?->info("User '{$userName}' <{$email}> ({$role}) ready.");
    }
}
