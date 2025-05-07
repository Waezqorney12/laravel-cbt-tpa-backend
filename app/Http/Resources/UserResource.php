<?php

namespace App\Http\Resources;

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
            'personal_id' => $this->personal_id,
            'matrix' => $this->dataPribadi->matrix_id,
            'email' => $this->email,
            'username' => $this->username,
            'first_name' => $this->dataPribadi->first_name,
            'last_name' => $this->dataPribadi->last_name,
            'full_name' => $this->dataPribadi->full_name,
            'birth_date' => $this->dataPribadi->birth_date,
            'gender' => $this->dataPribadi->gender,
            'address' => $this->dataPribadi->address,
            'phone_number' => $this->dataPribadi->phone_number,
            'departement' => $this->dataPribadi->departement,
            'study_program' => $this->dataPribadi->study_program,
            'entry_year' => $this->dataPribadi->entry_year,
            'roles' => $this->roles,
            'image' => optional($this->images)->user_image_path,
        ];
    }
}
