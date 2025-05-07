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
            "materi_id" => $this->id,
            "materi_kategori" => $this->materi_kategori,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,

            "materi" => [
                "materi_title" => $this->materi_title,
                "materi_description" => $this->materi_description,
                "materi_image" => optional($this->image->first())->materi_image_path,
            ]

        ];
    }
}
