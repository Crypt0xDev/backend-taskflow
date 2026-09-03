<?php

namespace Database\Seeders;

use App\Modules\Access\Permission\Permission;
use App\Modules\Access\Role\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private const MODULES = ['users', 'roles', 'tasks', 'categories', 'comments', 'tags'];
    private const ACTIONS = ['view', 'create', 'update', 'delete'];

    public function run(): void
    {
        $permissions = collect();
        foreach (self::MODULES as $module) {
            foreach (self::ACTIONS as $action) {
                $permissions->push(Permission::updateOrCreate(
                    ['module' => $module, 'name' => $action],
                    ['description' => ucfirst($action) . " {$module}"]
                ));
            }
        }

        $roles = [
            ['name' => 'admin', 'description' => 'Acceso total al panel administrativo.'],
            ['name' => 'user', 'description' => 'Cuenta estándar sin acceso administrativo.'],
        ];

        foreach ($roles as $role) {
            $model = Role::updateOrCreate(['name' => $role['name']], $role);

            $rolePermissions = match ($role['name']) {
                'admin' => $permissions,
                'user' => $permissions->filter(
                    fn (Permission $permission) => in_array($permission->module, ['tasks', 'categories', 'tags'], true)
                        && in_array($permission->name, ['view', 'create', 'update', 'delete'], true)
                ),
                default => collect(),
            };

            $model->permissions()->sync($rolePermissions->pluck('id'));
        }
    }
}
