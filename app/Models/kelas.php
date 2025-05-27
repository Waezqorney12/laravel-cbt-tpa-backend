<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'class_name',
        'class_description',
        'class_pin',
        'class_color',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function detailKelas()
    {
        return $this->hasMany(kelas_detail::class);
    }

    public function kelas()
    {
        return $this->hasMany(Quiz::class);
    }
}
