<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'personal_id',
        'email',
        'password',
        'username',
        'roles'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function detailReport()
    {
        return $this->hasMany(DetailReport::class);
    }
    public function detailPersonalInformation()
    {
        return $this->hasOne(DetailPersonalInformation::class);
    }
    public function dataPribadi()
    {
        return $this->belongsTo(PersonalInformation::class, 'personal_id');
    }
    public function kelas()
    {
        return $this->hasOne(kelas::class);
    }
    public function detailKelas()
    {
        return $this->hasMany(kelas_detail::class);
    }
    public function detailMateri()
    {
        return $this->hasMany(DetailMateri::class);
    }

    public function images()
    {
        return $this->hasOne(UserImages::class);
    }

    public function userOtp()
    {
        return $this->hasOne(UserOtp::class);
    }

    public function quizDetail()
    {
        return $this->hasMany(QuizDetail::class);
    }
    public function quizResult()
    {
        return $this->hasMany(QuizResult::class);
    }

}
