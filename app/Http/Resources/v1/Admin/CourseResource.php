<?php

namespace App\Http\Resources\v1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'media' => [
                'image' => $this->image_path ? asset('storage/' . $this->image_path) : null,
                'pdf' => $this->pdf_path ? asset('storage/' . $this->pdf_path) : null,
            ],
            // 'classes' => $this->classes->pluck('id'), // Retourne les IDs des classes associées
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
