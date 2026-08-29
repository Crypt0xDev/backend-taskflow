<?php

namespace Database\Seeders;

use App\Modules\Access\Permission\Permission;
use App\Modules\Access\Role\Role;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'tasks' => [
                'tasks.view' => 'Ver tareas',
                'tasks.create' => 'Crear tareas',
                'tasks.edit' => 'Editar tareas',
                'tasks.delete' => 'Eliminar tareas',
            ],
            'categories' => [
                'categories.view' => 'Ver categorías',
                'categories.create' => 'Crear categorías',
                'categories.edit' => 'Editar categorías',
                'categories.delete' => 'Eliminar categorías',
            ],
            'comments' => [
                'comments.view' => 'Ver comentarios',
                'comments.moderate' => 'Moderar (eliminar) comentarios',
            ],
            'users' => [
                'users.view' => 'Ver usuarios',
                'users.manage' => 'Gestionar usuarios (rol, eliminar)',
                'users.reset_password' => 'Restablecer contraseñas',
            ],
        ];

        $ids = [];
        foreach ($catalog as $module => $perms) {
            foreach ($perms as $name => $description) {
                $permission = Permission::updateOrCreate(
                    ['name' => $name],
                    ['module' => $module, 'description' => $description],
                );
                $ids[$name] = $permission->id;
            }
        }

        $userGrants = [
            'tasks.view',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'categories.view',
            'comments.view',
        ];

        $admin = Role::updateOrCreate(['name' => 'admin'], ['description' => 'Acceso total']);
        $user = Role::updateOrCreate(['name' => 'user'], ['description' => 'Gestiona sus propias tareas']);
        $admin->permissions()->sync(array_values($ids));
        $user->permissions()->sync(array_values(array_intersect_key($ids, array_flip($userGrants))));

        $this->command?->info('Roles y permisos sincronizados (admin, user).');
    }
}
