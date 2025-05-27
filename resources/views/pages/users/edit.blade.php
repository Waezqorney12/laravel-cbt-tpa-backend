@extends('layouts.app')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Personal Information</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Users</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Edit Personal Information</h2>
                <p class="section-lead">Update the details of the selected person.</p>

                <div class="card">
                    <div class="card-header">
                        <h4>Edit Person Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.update', $personalInformation->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Matrix ID -->
                            <div class="form-group">
                                <label for="matrix_id">Matrix ID</label>
                                <input type="text" name="matrix_id" id="matrix_id"
                                    class="form-control @error('matrix_id') is-invalid @enderror"
                                    value="{{ old('matrix_id', $personalInformation->matrix_id) }}" required>
                                @error('matrix_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $personalInformation->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name">Last Name (Optional)</label>
                                <input type="text" name="last_name" id="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name', $personalInformation->last_name) }}">
                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Birth Date -->
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date"
                                    class="form-control @error('birth_date') is-invalid @enderror"
                                    value="{{ old('birth_date', $personalInformation->birth_date) }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender"
                                    class="form-control select2 @error('gender') is-invalid @enderror" required>
                                    <option value="woman" {{ old('gender', $personalInformation->gender) == 'woman' ? 'selected' : '' }}>Woman</option>
                                    <option value="man" {{ old('gender', $personalInformation->gender) == 'man' ? 'selected' : '' }}>Man</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address"
                                    class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $personalInformation->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number', $personalInformation->phone_number) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Departement -->
                            <div class="form-group">
                                <label for="departement">Departement</label>
                                <input type="text" name="departement" id="departement"
                                    class="form-control @error('departement') is-invalid @enderror"
                                    value="{{ old('departement', $personalInformation->departement) }}" required>
                                @error('departement')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Study Program -->
                            <div class="form-group">
                                <label for="study_program">Study Program</label>
                                <input type="text" name="study_program" id="study_program"
                                    class="form-control @error('study_program') is-invalid @enderror"
                                    value="{{ old('study_program', $personalInformation->study_program) }}" required>
                                @error('study_program')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Entry Year -->
                            <div class="form-group">
                                <label for="entry_year">Entry Year</label>
                                <input type="number" name="entry_year" id="entry_year"
                                    class="form-control @error('entry_year') is-invalid @enderror"
                                    value="{{ old('entry_year', $personalInformation->entry_year) }}" required>
                                @error('entry_year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Person</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
