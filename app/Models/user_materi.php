<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'materi_id',
        'value'
    ];
}
