@extends('layouts.app')

@section('title', 'Edit Report')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Report</h1>
            </div>
            <div class="section-body">
                <div class="container mt-4">
                    <form action="{{ route('reports.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Subject Field -->
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject"
                                class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject', $report->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Context Field -->
                        <div class="form-group">
                            <label for="context">Context</label>
                            <input type="text" name="context" id="context"
                                class="form-control @error('context') is-invalid @enderror"
                                value="{{ old('context', $report->context) }}" required>
                            @error('context')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                                required>
                                <option value="accepted"
                                    {{ old('status', $report->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="waiting" {{ old('status', $report->status) == 'waiting' ? 'selected' : '' }}>
                                    Waiting</option>
                                <option value="declined"
                                    {{ old('status', $report->status) == 'declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Submit and Cancel -->
                        <button type="submit" class="btn btn-primary">Update Report</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
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
