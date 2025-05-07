<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    /**
     * Display a listing of the questions.
     */
    public function index(Request $request)
    {
        $query = QuizQuestion::query();

        // Check if the search parameter exists
        if ($request->has('pertanyaan') && $request->pertanyaan !== null) {
            $query->where('question', 'like', '%' . $request->pertanyaan . '%');
        }

        // Paginate the results
        $questions = $query->paginate(10);

        // Pass the questions to the view
        return view('pages.soals.index', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        return view('pages.soals.create');
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'quiz_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('question');

        // Handle image upload if provided
        if ($request->hasFile('quiz_image_path')) {
            $imagePath = $request->file('quiz_image_path')->store('question_images', 'public');
            $data['quiz_image_path'] = $imagePath;
        }

        QuizQuestion::create($data);

        return redirect()->route('soal.index')->with('success', 'Question successfully created.');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit($id)
    {
        $question = QuizQuestion::findOrFail($id);
        return view('pages.soals.edit', compact('question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'quiz_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $question = QuizQuestion::findOrFail($id);
        $data = $request->only('question');

        // Handle image upload if provided
        if ($request->hasFile('quiz_image_path')) {
            $imagePath = $request->file('quiz_image_path')->store('quiz_images', 'public');
            $data['quiz_image_path'] = $imagePath;
        }

        $question->update($data);

        return redirect()->route('soal.index')->with('success', 'Question successfully updated.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy($id)
    {
        $question = QuizQuestion::findOrFail($id);

        // Delete the image file if it exists
        if ($question->quiz_image_path) {
            Storage::disk('public')->delete($question->quiz_image_path);
        }

        $question->delete();

        return redirect()->route('soal.index')->with('success', 'Question successfully deleted.');
    }
}