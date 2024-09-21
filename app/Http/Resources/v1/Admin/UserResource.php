<?php

namespace App\Http\Resources\v1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'cni' => $this->cni,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'photo' => $this->photo,
            'status' => $this->status,
            'joinedDate' => $this->joinedDate,
            'role' => $this->roles->pluck('name')->first(), // Renvoie tous les rôles associés
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
