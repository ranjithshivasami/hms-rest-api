<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'user_id' => $this->user_id,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'address' => $this->address,
            'medical_history' => $this->medical_history,
            'allergies' => $this->allergies,
            'name' => $this->user->name,
            'role' => $this->user->role
        ];
    }
}
