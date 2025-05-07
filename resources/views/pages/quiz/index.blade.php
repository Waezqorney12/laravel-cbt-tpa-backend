@extends('layouts.app')

@section('title', 'Quizzes')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Quiz Screen</h1>
                <a href="{{ route('quiz.create') }}" class="btn btn-primary">Add Quiz</a>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>All Quizzes</h4>
                        <form method="GET" action="{{ route('quiz.index') }}" class="form-inline ml-auto">
                            <input type="text" name="search" class="form-control" placeholder="Search..."
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ml-2">Search</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($quiz->image_thumbnail_path)
                                                <img src="{{ asset('storage/' . $quiz->image_thumbnail_path) }}"
                                                    alt="Quiz Image" width="100">
                                            @else
                                                No Image
                                            @endif
                                        </td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->category }}</td>
                                        <td>{{ ucfirst($quiz->type) }}</td>
                                        <td>
                                            <a href="{{ route('quiz.show', $quiz->id) }}"
                                                class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('quiz.edit', $quiz->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('quiz.destroy', $quiz->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No quizzes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $quizzes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
