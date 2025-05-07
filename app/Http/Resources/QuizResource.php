<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quiz_id' => $this->quiz_id,
            'quiz_title' => $this->quiz_title,
            'quiz_description' => $this->quiz_description,
            'quiz_type' => $this->quiz_type,
            'quiz_category' => $this->quiz_category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'quiz_choice' => $this->quizChoice->map(function ($choice) {
                return [
                    'choice_a' => $choice->choice_a,
                    'choice_b' => $choice->choice_b,
                    'choice_c' => $choice->choice_c,
                    'choice_d' => $choice->choice_d,
                    'correct_answer' => $choice->correct_answer,
                ];
            }),
        ];
    }
}
