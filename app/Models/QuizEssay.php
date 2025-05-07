<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizEssay extends Model
{
    use HasFactory;
    protected $table = 'quiz_essay';
    protected $fillable = [
        'quiz_id',
        'quiz_question_id',
        'correct_answer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
