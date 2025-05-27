<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'personal_information';
    protected $fillable = [
        'matrix_id',
        'first_name',
        'last_name',
        'full_name',
        'birth_date',
        'gender',
        'address',
        'phone_number',
        'departement',
        'study_program',
        'entry_year',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function detailPersonalInformation()
    {
        return $this->hasOne(DetailPersonalInformation::class);
    }
}
