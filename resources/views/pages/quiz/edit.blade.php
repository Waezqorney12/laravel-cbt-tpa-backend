@extends('layouts.app')

@section('title', 'Edit Quiz')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Quiz</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('quiz.index') }}">Quizzes</a></div>
                    <div class="breadcrumb-item">Edit Quiz</div>
                </div>
            </div>

            <div class="section-body">
                <form action="{{ route('quiz.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Class Dropdown -->
                    <div class="form-group">
                        <label for="class_id">Class</label>
                        <select name="class_id" id="class_id" class="form-control select2">
                            <option value="" disabled>Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ $quiz->class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $quiz->title) }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $quiz->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="type" id="type" class="form-control select2" required>
                            <option value="choice" {{ $quiz->type == 'choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="essay" {{ $quiz->type == 'essay' ? 'selected' : '' }}>Essay</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category">Category</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="category" value="Logika" class="selectgroup-input"
                                    {{ $quiz->category == 'Logika' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Logika</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="category" value="Verbal" class="selectgroup-input"
                                    {{ $quiz->category == 'Verbal' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Verbal</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="category" value="Numeric" class="selectgroup-input"
                                    {{ $quiz->category == 'Numeric' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Numeric</span>
                            </label>
                        </div>
                        @error('category')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thumbnail -->
                    <div class="form-group">
                        <label for="image_thumbnail">Thumbnail</label>
                        <input type="file" name="image_thumbnail" id="image_thumbnail" class="form-control">
                        @if ($quiz->image_thumbnail_path)
                            <small>Current Thumbnail:</small>
                            <img src="{{ Storage::disk('s3')->url($quiz->image_thumbnail_path) }}" alt="Thumbnail"
                                class="img-fluid mt-2" style="max-width: 200px;">
                        @endif
                        @error('image_thumbnail')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Questions -->

                    <div class="form-group">
                        <label class="form-label fw-bold">Questions</label>
                        <div id="questions-container">
                            @if ($quiz->type === 'choice')
                                @foreach ($quiz->quizChoices as $index => $choice)
                                    <div class="question-item mb-4 p-3 border rounded bg-light shadow-sm">
                                        <h5 class="fw-bold text-primary">Question {{ $index + 1 }}</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Select Question</label>
                                            <select name="questions[{{ $index }}][quiz_question_id]"
                                                class="form-select select2">
                                                <option value="" disabled>Select Question</option>
                                                @foreach ($availableQuestions as $question)
                                                    <option value="{{ $question->id }}"
                                                        {{ $choice->quiz_question_id == $question->id ? 'selected' : '' }}>
                                                        {{ $question->question }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Choice A</label>
                                                <input type="text" name="questions[{{ $index }}][choice_a]"
                                                    value="{{ $choice->choice_a }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Choice B</label>
                                                <input type="text" name="questions[{{ $index }}][choice_b]"
                                                    value="{{ $choice->choice_b }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Choice C</label>
                                                <input type="text" name="questions[{{ $index }}][choice_c]"
                                                    value="{{ $choice->choice_c }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Choice D</label>
                                                <input type="text" name="questions[{{ $index }}][choice_d]"
                                                    value="{{ $choice->choice_d }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Correct Answer</label>
                                            <input type="text" name="questions[{{ $index }}][correct_answer]"
                                                value="{{ $choice->correct_answer }}" class="form-control" required>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif ($quiz->type === 'essay')
                                @foreach ($quiz->quizEssays as $index => $essay)
                                    <div class="question-item mb-4 p-3 border rounded bg-light shadow-sm">
                                        <h5 class="fw-bold text-primary">Essay Question {{ $index + 1 }}</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Select Question</label>
                                            <select name="questions[{{ $index }}][quiz_question_id]"
                                                class="form-select select2">
                                                <option value="" disabled>Select Question</option>
                                                @foreach ($availableQuestions as $question)
                                                    <option value="{{ $question->id }}"
                                                        {{ $essay->quiz_question_id == $question->id ? 'selected' : '' }}>
                                                        {{ $question->question }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Correct Answer</label>
                                            <textarea name="questions[{{ $index }}][correct_answer]" class="form-control" rows="3" required>{{ $essay->correct_answer }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Quiz</button>
                        <a href="{{ route('quiz.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
