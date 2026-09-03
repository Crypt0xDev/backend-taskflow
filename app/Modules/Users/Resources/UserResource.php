<?php

namespace App\Modules\Users\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->user_name,
            'email' => $this->email,
            'role' => [
                'id' => $this->role?->id,
                'name' => $this->role?->name,
            ],
            'permissions' => $this->whenLoaded(
                'role',
                fn () => $this->role?->permissions
                    ?->map(fn ($permission) => "{$permission->module}.{$permission->name}")
                    ->values() ?? [],
                []
            ),
            'birth_date' => $this->birth_date?->toDateString(),
            'age' => $this->age,
            'avatar' => $this->avatar,
            'must_change_password' => (bool) $this->must_change_password,
            'created_at' => $this->created_at,
        ];
    }
}
