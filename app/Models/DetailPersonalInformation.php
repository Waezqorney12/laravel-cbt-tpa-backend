<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'detail_personal_information';

    protected $fillable = [
        'personal_id',
        'user_id',
        'status',
    ];

    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
