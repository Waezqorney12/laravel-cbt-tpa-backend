<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizDetail;
use App\Models\QuizEssay;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Reverb\Loggers\Log;

use function PHPUnit\Framework\isEmpty;

class QuizController extends Controller
{

    //----CREATE-----
    public function createQuestion(Request $request): JsonResponse
    {

        try {
            $user = $request->user();
            if ($user->roles == 'USER') {
                return response()->json([
                    'message' => 'Forbidden: You do not have permission to create a question'
                ], status: 403);
            }
            $requestData = Validator::make($request->all(), [
                'quiz_image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
                'question' => 'required|string',
            ]);
            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'error' => $requestData->errors()
                ], status: 400);
            }

            $validate = $requestData->validate();
            $existingQuestion = QuizQuestion::where('question', $validate['question'])->first();

            if ($existingQuestion) {
                return response()->json([
                    'message' => 'Question already exists',
                    'error' => ['question' => 'The question already exists in the database']
                ], status: 400);
            }

            $imagePath = null;
            if ($request->hasFile('quiz_image_path')) {
                $image = $request->file('quiz_image_path');
                $imagePath = $image->store('question_images', 'public');
            }

            $question = QuizQuestion::create([
                'quiz_image_path' => $imagePath,
                'question' => $validate['question'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'message' => 'Question created successfully',
                'data' => $question
            ], 201);


        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'Failed to generate quiz',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }


    //-----UTILS------
    public function exitQuizzes(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $totalBenar = 0;
            $userId = auth()->user()->id;
            $requestData = Validator::make($request->all(), [
                'quiz_id' => 'required|integer',
                'data' => 'sometimes|array'
            ]);
            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Operation Failed',
                    'errors' => $requestData->errors()
                ]);
            }
            $validate = $requestData->validate();
            $quizId = $validate['quiz_id'];
            $data = $validate['data'];
            $totalSoal = QuizDetail::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->count();
            foreach ($data as $items) {
                $id = $items[0];
                $questionId = $items[1];
                $answer = $items[2];
                $isCorrect = $items[3];

                if ($answer != null && $id != null) {
                    QuizDetail::where('id', $id)
                        ->where('quiz_question_id', $questionId)
                        ->where('user_id', $userId)
                        ->update([
                            'answer' => $answer,
                            'is_correct' => $isCorrect
                        ]);

                        if($isCorrect){
                            $totalBenar++;
                        }
                    continue;
                }
            }
            QuizDetail::where('quiz_id', $quizId)
                ->whereNull('answer')
                ->update([
                    'answer' => 'Tidak dijawab',
                    'is_correct' => false
                ]);
            $totalNilai = $totalSoal > 0 ? ($totalBenar/$totalSoal)* 100 : 0;
            QuizResult::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->update([
                'score'=>$totalNilai,
                'status' => $totalNilai > 70 ? 'pass' : 'fails'
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Success',
                'data' => 'user successfull exit'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Unknown error',
                'errors' => $th->getMessage()
            ]);
        }
    }
    public function calculateQuizzes(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $totalBenar = 0;
        $totalSoal = 0;
        try {
            $requestData = Validator::make($request->all(), [
                'quiz_id' => 'required|integer',
                'data' => 'required|array'
            ]);
            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Operation Failed',
                    'errors' => $requestData->errors()
                ]);
            }
            $data = $requestData->validate();
            foreach ($data['data'] as $items) {
                $id = $items[0];
                $questionId = $items[1];
                $answer = $items[2];
                $isCorrect = $items[3];

                $quizDetail = QuizDetail::where('id', $id)
                    ->where('user_id', $userId)
                    ->where('quiz_question_id', $questionId);
                $quizDetail->update([
                    'answer' => $answer,
                    'is_correct' => $isCorrect
                ]);
                if ($isCorrect) {
                    $totalBenar++;
                }
                $totalSoal++;
            }
            $totalNilai = ($totalBenar / $totalSoal) * 100;
            $result = QuizResult::where('quiz_id', $data['quiz_id'])
                ->where('user_id', $userId);
            if ($totalNilai !== null) {
                $result
                    ->update([
                        'score' => $totalNilai,
                        'status' => $totalNilai > 70 ? 'pass' : 'fail'
                    ]);
            }
            $response = $result->get()->first();

            $customResponse = [
                'message' => 'Success to answer quiz',
                'data' => [
                    'id' => $response->id,
                    'totalSoal' => $totalSoal,
                    'totalBenar' => $totalBenar,
                    'totalSalah' => $totalSoal - $totalBenar,
                    'totalNilai' => $totalNilai,
                    'score' => $response->score
                ]
            ];
            return response()->json($customResponse, 200);

        } catch (\Throwable $th) {
            Log::error('Failed to answer quiz', ['error' => $th->getMessage()]);
            return response()->json([
                'message' => 'Failed to answer quiz',
                'error' => $th->getMessage()
            ], 500);
        }
    }



    //----GET-----
    public function getQuestion(Request $request): JsonResponse
    {
        try {
            $name = $request->query('name');

            if ($name != null) {
                $question = QuizQuestion::where('question', 'LIKE', "%{$name}%")->get();
            } else {
                $question = QuizQuestion::all();
            }

            return response()->json([
                'message' => 'Operation successful',
                'data' => $question
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Operation failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    //TODO: INI
    public function getQuizThumbnail(Request $request): JsonResponse
    {
        try {
            $requestData = Validator::make($request->all(),[
                'class_id' => 'required|int',
                'title' => 'string|nullable'
            ]);
            if($requestData->fails()){
                return response()->json([
                    'message' => 'Operation failed',
                    'error' => $requestData->errors()
                ]);
            }
            $data = $requestData->validate();
            $filter = trim($data['title'] ?? "");
            $filter = $filter !== "" ? $filter : null;
            $userId = auth()->check() ? auth()->user()->id : null;


            $existingClass = kelas::where('id', $data['class_id'])->firstOrFail();
            $quizzes = Quiz::where('class_id', $data['class_id'])
            ->when($filter, function ($query) use ($filter) {
                    return $query->whereRaw("LOWER(title) LIKE ?", ["%" . strtolower($filter) . "%"]);
                })
                ->with(['quizDetails' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }, 'quizResults'])
                ->get();

            $isAlreadyJoinedQuiz = $quizzes->flatMap->quizDetails->isNotEmpty();

            $quizzesWithStatus = $quizzes->map(function ($quiz) use ($isAlreadyJoinedQuiz) {
                $isDone = $isAlreadyJoinedQuiz && $quiz->quizResults->isNotEmpty()
                    ? $quiz->quizResults->every(fn ($result) => $result->status !== 'not started')
                    : false;

                return [
                    'id' => $quiz->id,
                    'image_thumbnail_path' => $quiz->image_thumbnail_path,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'type' => $quiz->type,
                    'category' => $quiz->category,
                    'isDone' => $isDone
                ];
            });

            return response()->json([
                'message' => 'Success to get quiz',
                'data' => $quizzesWithStatus
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Failed to get class',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get quiz',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    //TODO: INI
    public function editQuizzes(Request $request): JsonResponse{
        try {
            $user = auth()->check() ? auth()->user() : null;
            if($user === null || $user->roles !== 'STAFF'){
                return response()->json([
                    'message'=>'Access denied',
                    'error'=>'You do not have permission for this action'
                ], status: 403);
            }
            $userId = $user->id;
            $requestData = Validator::make($request->all(), [
                'quiz_id' => 'required|int',
                'title'=>'required|string',
                'description'=>'string|nullable'
            ]);
            if($requestData->fails()){
                return response()->json(
                    [
                        'message' => 'Validation failed',
                        'error' => $requestData->errors()
                    ]
                    );
            }
            $data = $requestData->validate();
            $existingQuiz = Quiz::where('quiz_id', $data['quiz_id'])
            ->where('user_id', $userId)
            ->first();
            if(!$existingQuiz){
                return response()->json([
                    'message'=>'Operation failed',
                    'error' => 'Quiz not found'
                ], status: 404);
            }
            $existingQuiz->update(
                [
                    'title'=>$data['title'],
                    'description' => $data['description'] != null ? $data['description'] : $existingQuiz->description
                ]
            );

            return response()->json([
                'message'=>'Operation success',
                'data'=>'Data has been successfully edited'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message'=>'An unknown error occured',
                'error'=>$th->getMessage()
            ]);
        }
    }

    //TODO: INI
    public function joinQuizzes(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if($user->roles === 'STAFF'){
                return response()->json([
                    'message'=>'Access not granted',
                    'error' => 'Only user allowed to participate'
                ], status:403);
            }
            $userId = $user->id;

            // Validate request
            $requestData = Validator::make($request->all(), [
                'quiz_id' => 'required|integer',
                'type' => 'required|string'
            ]);

            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Operation failed',
                    'errors' => $requestData->errors()
                ], 400);
            }

            $data = $requestData->validate();
            $quizType = $data['type'];

            // Fetch quiz questions in bulk
            $quizzes = match ($quizType) {
                'choice' => QuizChoice::where('quiz_id', $data['quiz_id'])->get(),
                'essay' => QuizEssay::where('quiz_id', $data['quiz_id'])->get(),
                default => null
            };

            if (!$quizzes) {
                return response()->json(['message' => 'Quiz type not found'], 404);
            }

            // Prepare bulk insert data
            $quizDetails = $quizzes->map(fn($quiz) => [
                'quiz_id' => $quiz->quiz_id,
                'user_id' => $userId,
                'quiz_question_id' => $quiz->quiz_question_id,
            ])->toArray();

            QuizDetail::insert($quizDetails);
            QuizResult::create([
                'quiz_id'=>$data['quiz_id'],
                'user_id'=>$userId,
            ]);

            // Fetch newly inserted quiz details
            $quizDetails = QuizDetail::with('quizQuestion')
                ->where('quiz_id', $data['quiz_id'])
                ->where('user_id', $userId)
                ->get();

            $answerQuizzes = match ($quizType) {
                'choice' => QuizChoice::where('quiz_id', $data['quiz_id'])
                    ->whereIn('quiz_question_id', $quizDetails->pluck('quiz_question_id'))
                    ->get(),
                'essay' => QuizEssay::where('quiz_id', $data['quiz_id'])
                    ->whereIn('quiz_question_id', $quizDetails->pluck('quiz_question_id'))
                    ->get(),
                default => null
            };

            $quizzesModify = $quizDetails->map(function ($quiz) use ($answerQuizzes, $quizType) {
                $quizzesAnswer = $answerQuizzes->where('quiz_question_id', $quiz->quiz_question_id)->first();

                $quizData = [
                    'id' => $quiz->id,
                    'quiz_id' => $quiz->quiz_id,
                    'user_id' => $quiz->user_id,
                    'quizzes' => [
                        'quiz_question_id' => $quiz->quiz_question_id,
                        'quiz_image_path' => $quiz->quizQuestion->quiz_image_path,
                        'question' => $quiz->quizQuestion->question,
                        'choice_a' => $quizzesAnswer->choice_a ?? null,
                        'choice_b' => $quizzesAnswer->choice_b ?? null,
                        'choice_c' => $quizzesAnswer->choice_c ?? null,
                        'choice_d' => $quizzesAnswer->choice_d ?? null,
                        'answer' => $quiz->answer ?? null,
                        'correct_answer' => $quizzesAnswer->correct_answer ?? null,
                        'is_correct' => $quiz->answer == $quizzesAnswer->correct_answer ? 1 : 0,
                    ],
                ];

                if ($quizType === 'essay') {
                    unset(
                        $quizData['quizzes']['choice_a'],
                        $quizData['quizzes']['choice_b'],
                        $quizData['quizzes']['choice_c'],
                        $quizData['quizzes']['choice_d'],
                    );
                }

                return $quizData;
            });

            return response()->json([
                'message' => 'Successfully joined and fetched quiz data',
                'data' => $quizzesModify
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to join quiz',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function generateQuizzes(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if ($user->roles == 'USER') {
                return response()->json([
                    'message' => 'Access not granted',
                    'error' => 'Unallowed to do the action'
                ], status: 403);
            }
            $requestData = Validator::make($request->all(), [
                'class_id' => 'required|integer',
                'image_thumbnail_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
                'title' => 'required|string',
                'description' => 'string|nullable',
                'type' => 'required|string',
                'category' => 'required|string',
                'questions' => 'required|array',
            ]);

            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Operation failed',
                    'error' => $requestData->errors()
                ]);
            }
            $response = $requestData->validate();
            $existingClass = kelas::where('id', $response['class_id'])->first();
            if (!$existingClass) {
                return response()->json([
                    'message' => 'Class not found'
                ], status: 404);
            }
            if ($request->hasFile('image_thumbnail_path')) {
                $image = $request->file('image_thumbnail_path');
                $response['image_thumbnail_path'] = $image->store('quiz_thumbnail', 'public');
            }
            $quiz = Quiz::create(
                [
                    'class_id' => $response['class_id'],
                    'image_thumbnail_path' => $response['image_thumbnail_path'] ?? null,
                    'title' => $response['title'],
                    'description' => $response['description'] ?? null,
                    'type' => $response['type'],
                    'category' => $response['category']
                ]
            );

            if (!$quiz) {
                return response()->json([
                    'message' => 'Operation failed',
                    'error' => 'Failed to generate quiz'
                ]);
            }

            $quizId = $quiz->id;
            $questions = $response['questions'];
            switch ($quiz->type) {
                case 'choice':
                    foreach ($questions as $question) {
                        $question['quiz_id'] = $quizId;
                        QuizChoice::insert($question);
                    }
                    break;
                case 'essay':
                    foreach ($questions as $question) {
                        $question['quiz_id'] = $quizId;
                        QuizEssay::insert($question);
                    }
                    break;
                default:
                    return response()->json([
                        'message' => 'Operation Failed',
                        'errors' => 'Quiz type not found'
                    ], 404);
            }
            return response()->json([
                'message' => 'Successfully generate quizzes',
                'data' => $quiz
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server unavailable',
                'error' => $th->getMessage()
            ]);
        }
    }

}
