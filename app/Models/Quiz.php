<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quiz';
    protected $fillable = [
        'class_id',
        'image_thumbnail_path',
        'title',
        'description',
        'type',
        'category',
    ];


    public function quizClass()
    {
        return $this->belongsTo(kelas::class, 'class_id');
    }
    public function quizDetails()
    {
        return $this->hasMany(QuizDetail::class, );
    }

    public function quizChoices()
    {
        return $this->hasMany(QuizChoice::class, );
    }

    public function quizEssays()
    {
        return $this->hasMany(QuizEssay::class, );
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }


}
