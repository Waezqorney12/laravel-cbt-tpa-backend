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
                <h1>Edit Materi</h1>
            </div>
            <div class="section-body">
                <div class="container mt-4">
                    <form action="{{ route('materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Class Dropdown -->
                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select name="class_id" id="class_id" class="form-control select2">
                                <option value="" disabled>Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ $materi->class_id == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Materi Title -->
                        <div class="form-group">
                            <label for="materi_title">Title</label>
                            <input type="text" name="materi_title" id="materi_title" class="form-control"
                                value="{{ old('materi_title', $materi->materi_title) }}" required>
                            @error('materi_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Materi Description -->
                        <div class="form-group">
                            <label for="materi_description">Description</label>
                            <textarea name="materi_description" id="materi_description" class="form-control" rows="5" required>{{ old('materi_description', $materi->materi_description) }}</textarea>
                            @error('materi_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Materi Category -->
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="materi_kategori" value="Logika" class="selectgroup-input"
                                        {{ old('materi_kategori', $materi->materi_kategori) == 'Logika' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Logika</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="materi_kategori" value="Verbal" class="selectgroup-input"
                                        {{ old('materi_kategori', $materi->materi_kategori) == 'Verbal' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Verbal</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="materi_kategori" value="Numeric" class="selectgroup-input"
                                        {{ old('materi_kategori', $materi->materi_kategori) == 'Numeric' ? 'checked' : '' }}>
                                    <span class="selectgroup-button">Numeric</span>
                                </label>
                            </div>
                            @error('materi_kategori')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Existing Images -->
                        <div class="form-group">
                            <label>Existing Images</label>
                            <div class="row">
                                @if ($materi->image->isNotEmpty())
                                    @foreach ($materi->image as $image)
                                        <div class="col-md-3 mb-3">
                                            <img src="{{ asset('storage/' . $image->materi_image_path) }}"
                                                alt="Materi Image" class="img-thumbnail">
                                            <div class="mt-2">
                                                <form action="{{ route('materi.image.destroy', $image->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this image?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p class="text-muted">No Images</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Upload New Images -->
                        <div class="form-group">
                            <label for="materi_images">Upload New Images</label>
                            <input type="file" name="materi_images[]" id="materi_images" class="form-control" multiple>
                            @error('materi_images.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('materi.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
