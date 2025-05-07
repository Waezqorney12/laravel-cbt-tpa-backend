<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
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
        return view('pages.quiz.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|exists:kelas,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:essay,choice',
            'category' => 'required|string|max:255',
            'image_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_thumbnail')) {
            $imagePath = $request->file('image_thumbnail')->store('quiz_thumbnails', 'public');
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

        return redirect()->route('quiz.index')->with('success', 'Quiz successfully created.');
    }

    public function show($id)
    {
        $quiz = Quiz::with(['quizDetails', 'quizChoices', 'quizEssays'])->findOrFail($id);

        return view('pages.quiz.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);

        return view('pages.quiz.edit', compact('quiz'));
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
        ]);

        $quiz = Quiz::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image_thumbnail')) {
            if ($quiz->image_thumbnail_path && Storage::exists('public/' . $quiz->image_thumbnail_path)) {
                Storage::delete('public/' . $quiz->image_thumbnail_path);
            }
            $quiz->image_thumbnail_path = $request->file('image_thumbnail')->store('quiz_thumbnails', 'public');
        }

        // Update the quiz
        $quiz->update([
            'class_id' => $validatedData['class_id'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'type' => $validatedData['type'],
            'category' => $validatedData['category'],
        ]);

        return redirect()->route('quiz.index')->with('success', 'Quiz successfully updated.');
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
