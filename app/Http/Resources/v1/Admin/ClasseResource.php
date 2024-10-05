<?php

namespace App\Http\Resources\v1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClasseResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'number_session' => $this->number_session,
            'presential' => $this->presential,
            'status' => $this->status->value, // Cast Enum to its string value
            'teacher' => new UserResource($this->whenLoaded('teacher')), // Si chargé avec teacher
            'students' => UserResource::collection($this->whenLoaded('students')), // Si chargé avec les étudiants
            'levels' => LevelResource::collection($this->whenLoaded('levels')), // Si chargé avec les niveaux
        ];
    }
}
