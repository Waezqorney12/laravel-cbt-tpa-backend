@extends('layouts.app')

@section('title', 'Edit Soal')

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
                <h1>Edit Question</h1>
            </div>
            <div class="section-body">
                <div class="container mt-4">
                    <form action="{{ route('soal.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Question Field -->
                        <div class="form-group">
                            <label for="question">Question</label>
                            <input type="text" name="question" id="question"
                                class="form-control @error('question') is-invalid @enderror"
                                value="{{ old('question', $question->question) }}" required>
                            @error('question')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Image Field -->
                        <div class="form-group">
                            <label for="quiz_image_path">Image (optional)</label>
                            @if ($question->quiz_image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::disk('s3')->url($question->quiz_image_path) }}" alt="Quiz Image"
                                        width="150">
                                </div>
                            @endif
                            <input type="file" name="quiz_image_path" id="quiz_image_path"
                                class="form-control @error('quiz_image_path') is-invalid @enderror">
                            @error('quiz_image_path')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Question</button>
                        <a href="{{ route('soal.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
