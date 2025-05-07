<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas_detail extends Model
{
    use HasFactory;
    protected $table = 'kelas_details';

    protected $fillable = ['class_id', 'user_id',];


    public function class()
    {
        return $this->belongsTo(kelas::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
