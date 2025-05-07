@extends('layouts.app')

@section('title', 'Edit User')

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
                <h1>Edit Forms</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Forms</a></div>
                    <div class="breadcrumb-item">Users</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Edit User</h2>



                <div class="card">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Edit User</h4>
                        </div>

                        <div class="card-body">

                            <!-- Email Field -->
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Username Field -->
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    name="username" value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Role Field -->
                            <div class="form-group">
                                <label>Role</label>
                                <select name="roles" class="form-control @error('roles') is-invalid @enderror">
                                    <option value="ADMIN" {{ old('roles', $user->roles) == 'ADMIN' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="STAFF" {{ old('roles', $user->roles) == 'STAFF' ? 'selected' : '' }}>
                                        Staff</option>
                                    <option value="USER" {{ old('roles', $user->roles) == 'USER' ? 'selected' : '' }}>
                                        User</option>
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Matrix ID Fields -->
                            <div class="form-group">
                                <label>Matrix ID</label>
                                <input type="text" class="form-control @error('matrix_id') is-invalid @enderror"
                                    name="matrix_id" value="{{ old('matrix_id', $user->dataPribadi->matrix_id ?? '') }}">
                                @error('matrix_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- First Name Fields -->
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    name="first_name"
                                    value="{{ old('first_name', $user->dataPribadi->first_name ?? '') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Last Name Fields -->
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    name="last_name" value="{{ old('last_name', $user->dataPribadi->last_name ?? '') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Birth Date Field -->
                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    name="birth_date" value="{{ old('birth_date', $user->dataPribadi->birth_date) }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Gender Field -->
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="man"
                                        {{ old('gender', $user->dataPribadi->gender ?? '') == 'man' ? 'selected' : '' }}>
                                        Man</option>
                                    <option value="woman"
                                        {{ old('gender', $user->dataPribadi->gender ?? '') == 'woman' ? 'selected' : '' }}>
                                        Woman</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Address Field -->
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->dataPribadi->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Phone Number Field -->
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                    name="phone_number"
                                    value="{{ old('phone_number', $user->dataPribadi->phone_number ?? '') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Departement Field -->
                            <div class="form-group">
                                <label>Departement</label>
                                <textarea name="departement" class="form-control @error('departement') is-invalid @enderror">{{ old('departement', $user->dataPribadi->departement ?? '') }}</textarea>
                                @error('departement')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Study Program Field -->
                            <div class="form-group">
                                <label>Study Program</label>
                                <textarea name="study_program" class="form-control @error('study_program') is-invalid @enderror">{{ old('study_program', $user->dataPribadi->study_program ?? '') }}</textarea>
                                @error('study_program')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Entry Year Field -->
                            <div class="form-group">
                                <label>Entry Year</label>
                                <input type="number" class="form-control @error('entry_year') is-invalid @enderror"
                                    name="entry_year"
                                    value="{{ old('entry_year', $user->dataPribadi->entry_year ?? '') }}">
                                @error('entry_year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
@endpush
