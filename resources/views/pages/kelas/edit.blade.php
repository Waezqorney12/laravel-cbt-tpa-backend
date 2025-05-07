@extends('layouts.app')

@section('title', 'Edit Class')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Class</h1>
            </div>
            <div class="section-body">
                <div class="container mt-4">
                    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Class Name Field -->
                        <div class="form-group">
                            <label for="class_name">Class Name</label>
                            <input type="text" name="class_name" id="class_name"
                                class="form-control @error('class_name') is-invalid @enderror"
                                value="{{ old('class_name', $kelas->class_name) }}" required>
                            @error('class_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Class Description Field -->
                        <div class="form-group">
                            <label for="class_description">Class Description</label>
                            <textarea name="class_description" id="class_description" rows="5"
                                class="form-control @error('class_description') is-invalid @enderror">{{ old('class_description', $kelas->class_description) }}</textarea>
                            @error('class_description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- User Field -->
                        <div class="form-group">
                            <label for="user_id">Assigned User</label>
                            <select name="user_id" id="user_id"
                                class="form-control select2 @error('user_id') is-invalid @enderror">
                                <option value="" disabled>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $kelas->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->dataPribadi->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Class</button>
                        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Cancel</a>
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
