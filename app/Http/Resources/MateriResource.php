<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'materi_id' => $this->materi_id,
            'status' => $this->status,
            "created_at" => $this->created_at,
            'materi' => [
                "materi_title" => $this->materi->materi_title,
                "materi_description" => $this->materi->materi_description,
                "materi_kategori" => $this->materi->materi_kategori,

            ]

        ];
    }
}
