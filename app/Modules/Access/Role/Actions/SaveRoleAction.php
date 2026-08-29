<?php

namespace App\Modules\Access\Role\Actions;

use App\Modules\Access\Role\Role;

class SaveRoleAction
{
    public function execute(Role $role, array $data): Role
    {
        if (array_key_exists('name', $data)) {
            $role->name = $data['name'];
        }
        if (array_key_exists('description', $data)) {
            $role->description = $data['description'];
        }
        $role->save();
        if (array_key_exists('permission_ids', $data)) {
            $role->permissions()->sync($data['permission_ids'] ?? []);
        }
        return $role->load('permissions');
    }
}
