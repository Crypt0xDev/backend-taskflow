<?php

namespace App\Modules\Comment\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'author' => $this->whenLoaded('user', fn() => $this->user ? [
                'id' => $this->user->id,
                'username' => $this->user->user_name,
            ] : null),
            'created_at' => $this->created_at,
        ];
    }
}
