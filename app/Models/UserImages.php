<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    use HasFactory;
    protected $table = 'user_images';
    protected $fillable = [
        'user_id',
        'user_image_path',
    ];
    public function images()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
