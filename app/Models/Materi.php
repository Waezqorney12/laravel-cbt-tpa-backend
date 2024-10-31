<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table = 'materi';
    protected $fillable = [
        'materi_title',
        'materi_description',
    ];

    public function detailMateri()
    {
        return $this->hasMany(DetailMateri::class);
    }
    public function image()
    {
        return $this->hasMany(MateriImage::class);
    }
}
