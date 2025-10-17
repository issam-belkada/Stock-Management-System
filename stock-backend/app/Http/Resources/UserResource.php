<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'themePref' => $this->themePref,
            'role' => $this->role ? $this->role->name : null,
            'permissions' => $this->role 
                ? $this->role->permissions->pluck('name') 
                : [],
        ];
    }
}
