@extends('layouts.app')

@section('title', 'Quiz Details')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Quiz Details</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('quiz.index') }}">Quizzes</a></div>
                    <div class="breadcrumb-item">Quiz Details</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $quiz->title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Class:</label>
                                    <p>{{ $quiz->quizClass->class_name ?? 'N/A' }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <p>{{ $quiz->description ?? 'No description provided.' }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Type:</label>
                                    <p>{{ ucfirst($quiz->type) }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Category:</label>
                                    <p>{{ $quiz->category }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail:</label>
                                    @if ($quiz->image_thumbnail_path)
                                        <img src="{{ Storage::disk('s3')->url($quiz->image_thumbnail_path) }}"
                                            alt="Thumbnail" class="img-fluid" style="max-width: 200px;">
                                    @else
                                        <p>No thumbnail uploaded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-lg border-0">
                            <div
                                class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Questions</h4>
                                <span class="badge bg-light text-primary">{{ ucfirst($quiz->type) }} Quiz</span>
                            </div>
                            <div class="card-body">
                                @if ($quiz->type === 'choice')
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Question ID</th>
                                                    <th>Choices</th>
                                                    <th>Correct Answer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($quiz->quizChoices as $choice)
                                                    <tr>
                                                        <td><strong>#{{ $choice->quiz_question_id }}</strong></td>
                                                        <td>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><strong>A:</strong> {{ $choice->choice_a }}</li>
                                                                <li><strong>B:</strong> {{ $choice->choice_b }}</li>
                                                                <li><strong>C:</strong> {{ $choice->choice_c }}</li>
                                                                <li><strong>D:</strong> {{ $choice->choice_d }}</li>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-success">{{ $choice->correct_answer }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif ($quiz->type === 'essay')
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Question ID</th>
                                                    <th>Correct Answer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($quiz->quizEssays as $essay)
                                                    <tr>
                                                        <td><strong>#{{ $essay->quiz_question_id }}</strong></td>
                                                        <td>
                                                            <span class="text-muted">{{ $essay->correct_answer }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning text-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill"></i> No questions available for this
                                        quiz.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
