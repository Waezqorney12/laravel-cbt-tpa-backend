<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $table = 'quiz_question';
    protected $fillable = [
        'quiz_image_path',
        'question',
    ];

    public function quizDetail()
    {
        return $this->hasMany(QuizDetail::class, 'quiz_question_id');
    }
    public function quizChoice()
    {
        return $this->hasMany(QuizChoice::class, 'quiz_question_id');
    }
    public function quizEssay()
    {
        return $this->hasMany(QuizEssay::class, 'quiz_question_id');
    }
}
