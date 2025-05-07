<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table = 'materi';
    protected $fillable = [
        'class_id',
        'materi_title',
        'materi_description',
        'materi_kategori'
    ];

    public function class()
    {
        return $this->belongsTo(kelas::class, 'class_id', 'id');
    }
    public function detailMateri()
    {
        return $this->hasMany(DetailMateri::class);
    }
    public function image()
    {
        return $this->hasMany(MateriImage::class);
    }
}
