@extends('layouts.app')

@section('title', 'Add Quiz')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Quiz</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('quiz.index') }}">Quiz</a></div>
                    <div class="breadcrumb-item">Add Quiz</div>
                </div>
            </div>

            <div class="section-body">
                <form action="{{ route('quiz.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Quiz Details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <select name="class_id" id="class_id" class="form-control select2">
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control select2" required>
                                            <option value="choice">Choice</option>
                                            <option value="essay">Essay</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Category</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="category" value="Logika"
                                                    class="selectgroup-input" checked ="">
                                                <span class="selectgroup-button">Logika</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="category" value="Verbal"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button">Verbal</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="category" value="Numeric"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button">Numeric</span>
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image_thumbnail">Thumbnail</label>
                                        <input type="file" name="image_thumbnail" id="image_thumbnail"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="questions-section">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Questions</h4>
                                    <button type="button" class="btn btn-primary btn-sm ml-auto" id="add-question">Add
                                        Question</button>
                                </div>
                                <div class="card-body" id="questions-container">
                                    <!-- Questions will be dynamically added here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = 0;

            document.getElementById('add-question').addEventListener('click', function() {
                const container = document.getElementById('questions-container');
                const type = document.getElementById('type').value;

                let questionHtml = `
                    <div class="form-group question-item">
                        <label for="questions[${questionIndex}][quiz_question_id]">Question ID</label>
                        <input type="text" name="questions[${questionIndex}][quiz_question_id]" class="form-control" required>
                `;

                if (type === 'choice') {
                    questionHtml += `
                        <label for="questions[${questionIndex}][choice_a]">Choice A</label>
                        <input type="text" name="questions[${questionIndex}][choice_a]" class="form-control" required>
                        <label for="questions[${questionIndex}][choice_b]">Choice B</label>
                        <input type="text" name="questions[${questionIndex}][choice_b]" class="form-control" required>
                        <label for="questions[${questionIndex}][choice_c]">Choice C</label>
                        <input type="text" name="questions[${questionIndex}][choice_c]" class="form-control" required>
                        <label for="questions[${questionIndex}][choice_d]">Choice D</label>
                        <input type="text" name="questions[${questionIndex}][choice_d]" class="form-control" required>
                        <label for="questions[${questionIndex}][correct_answer]">Correct Answer</label>
                        <input type="text" name="questions[${questionIndex}][correct_answer]" class="form-control" required>
                    `;
                } else if (type === 'essay') {
                    questionHtml += `
                        <label for="questions[${questionIndex}][correct_answer]">Correct Answer</label>
                        <textarea name="questions[${questionIndex}][correct_answer]" class="form-control" required></textarea>
                    `;
                }

                questionHtml += `</div>`;
                container.insertAdjacentHTML('beforeend', questionHtml);
                questionIndex++;
            });
        });
    </script>
@endpush
