@extends('layouts.app')

@section('title', 'Personal Information')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Personal Information</h1>
                <div class="section-header-button">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Add Person</a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="row">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Personal Information</h2>
                <p class="section-lead">
                    You can manage all personal information, such as editing, deleting, and more.
                </p>

                <div class="card">
                    <div class="card-header">
                        <h4>List of Personal Information</h4>
                        <div class="card-header-form">
                            <form action="{{ route('users.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control" placeholder="Search by name"
                                        value="{{ request('name') }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Matrix ID</th>
                                        <th>Full Name</th>
                                        <th>Birth Date</th>
                                        <th>Gender</th>
                                        <th>Phone Number</th>
                                        <th>Departement</th>
                                        <th>Study Program</th>
                                        <th>Entry Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($personalInformation as $person)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $person->matrix_id }}</td>
                                            <td>{{ $person->full_name }}</td>
                                            <td>{{ $person->birth_date }}</td>
                                            <td>{{ ucfirst($person->gender) }}</td>
                                            <td>{{ $person->phone_number }}</td>
                                            <td>{{ $person->departement }}</td>
                                            <td>{{ $person->study_program }}</td>
                                            <td>{{ $person->entry_year }}</td>
                                            <td>
                                                <a href="{{ route('users.edit', $person->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $person->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No personal information found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $personalInformation->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush
