<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizEssay;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizWebController extends Controller
{
    public function index(Request $request)
    {
        // Fetch quizzes with optional search functionality
        $query = Quiz::query();

        if ($request->has('search') && $request->search !== null) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        $quizzes = $query->paginate(10);

        return view('pages.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        $classes = kelas::all(); // Assuming the model is named 'Kelas'

        // Pass the classes to the view
        return view('pages.quiz.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string|in:choice,essay',
            'category' => 'required|string|in:Logika,Verbal,Numeric',
            'image_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'questions' => 'required|array', // Add validation for questions
        ]);


        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_thumbnail')) {
            $imagePath = $request->file('image_thumbnail')->store('quiz_thumbnails', 's3');
            Storage::disk('s3')->setVisibility($imagePath, 'public');
        }

        // Create the quiz
        $quiz = Quiz::create([
            'class_id' => $validatedData['class_id'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'category' => $validatedData['category'],
            'image_thumbnail_path' => $imagePath,
        ]);

        // Handle questions based on quiz type
        $questions = $validatedData['questions'];
        switch ($quiz->type) {
            case 'choice':
                foreach ($questions as $question) {
                    QuizChoice::create([
                        'quiz_id' => $quiz->id,
                        'quiz_question_id' => $question['quiz_question_id'],
                        'choice_a' => $question['choice_a'],
                        'choice_b' => $question['choice_b'],
                        'choice_c' => $question['choice_c'],
                        'choice_d' => $question['choice_d'],
                        'correct_answer' => $question['correct_answer'],
                    ]);
                }
                break;

            case 'essay':
                foreach ($questions as $question) {
                    QuizEssay::create([
                        'quiz_id' => $quiz->id,
                        'quiz_question_id' => $question['quiz_question_id'],
                        'correct_answer' => $question['correct_answer'],
                    ]);
                }
                break;

            default:
                return redirect()->route('quiz.index')->with('error', 'Invalid quiz type.');
        }

        return redirect()->route('quiz.index')->with('success', 'Quiz successfully created with questions.');
    }

    public function show($id)
    {
        $quiz = Quiz::with(['quizDetails', 'quizChoices', 'quizEssays', 'quizClass'])->findOrFail($id);

        return view('pages.quiz.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Quiz::with(['quizChoices', 'quizEssays'])->findOrFail($id);
        $classes = kelas::all();
        $availableQuestions = QuizQuestion::all(); // Fetch all available questions

        return view('pages.quiz.edit', compact('quiz', 'classes', 'availableQuestions'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|exists:kelas,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:essay,choice',
            'category' => 'required|string|max:255',
            'image_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'questions' => 'required|array', // Add validation for questions
        ]);

        $quiz = Quiz::with(['quizChoices', 'quizEssays'])->findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image_thumbnail')) {
            if ($quiz->image_thumbnail_path && Storage::exists('public/' . $quiz->image_thumbnail_path)) {
                Storage::delete('public/' . $quiz->image_thumbnail_path);
            }
            $quiz->image_thumbnail_path = $request->file('image_thumbnail')->store('quiz_thumbnails', 's3');
            Storage::disk('s3')->setVisibility($quiz->image_thumbnail_path, 'public');
        }

        // Update the quiz
        $quiz->update([
            'class_id' => $validatedData['class_id'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'category' => $validatedData['category'],
        ]);

        // Update questions based on quiz type
        $questions = $validatedData['questions'];
        switch ($quiz->type) {
            case 'choice':
                // Delete existing choices and recreate them
                $quiz->quizChoices()->delete();
                foreach ($questions as $question) {
                    QuizChoice::create([
                        'quiz_id' => $quiz->id,
                        'quiz_question_id' => $question['quiz_question_id'],
                        'choice_a' => $question['choice_a'],
                        'choice_b' => $question['choice_b'],
                        'choice_c' => $question['choice_c'],
                        'choice_d' => $question['choice_d'],
                        'correct_answer' => $question['correct_answer'],
                    ]);
                }
                break;

            case 'essay':
                // Delete existing essays and recreate them
                $quiz->quizEssays()->delete();
                foreach ($questions as $question) {
                    QuizEssay::create([
                        'quiz_id' => $quiz->id,
                        'quiz_question_id' => $question['quiz_question_id'],
                        'correct_answer' => $question['correct_answer'],
                    ]);
                }
                break;

            default:
                return redirect()->route('quiz.index')->with('error', 'Invalid quiz type.');
        }

        return redirect()->route('quiz.index')->with('success', 'Quiz and questions successfully updated.');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);

        // Delete related data
        $quiz->quizDetails()->delete();
        $quiz->quizChoices()->delete();
        $quiz->quizEssays()->delete();
        $quiz->quizResults()->delete();

        // Delete the quiz thumbnail
        if ($quiz->image_thumbnail_path && Storage::exists('public/' . $quiz->image_thumbnail_path)) {
            Storage::delete('public/' . $quiz->image_thumbnail_path);
        }

        // Delete the quiz
        $quiz->delete();

        return redirect()->route('quiz.index')->with('success', 'Quiz successfully deleted.');
    }
}
