<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriImage extends Model
{
    use HasFactory;

    protected $table = [
        'materi_id',
        'materi_image_path'
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
