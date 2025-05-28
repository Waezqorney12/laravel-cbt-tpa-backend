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
                <h1>Reports</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="{{ route('home') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">Reports</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Manage Reports</h2>
                <p class="section-lead">View, edit, or delete reports.</p>

                <div class="card">
                    <div class="card-header">
                        <h4>Report List</h4>
                    </div>
                    <div class="card-body">
                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Context</th>
                                        <th>Status</th>
                                        <th>Rating</th>
                                        <th>Users</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reports as $report)
                                        <tr>
                                            <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $report->subject }}</td>
                                            <td>{{ $report->context }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $report->status == 'accepted' ? 'success' : ($report->status == 'declined' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $report->rating ?? '-' }}</td>
                                            <td>
                                                @forelse ($report->detailReport as $detail)
                                                    <div>{{ $detail->user->dataPribadi->full_name ?? '-' }}</div>
                                                @empty
                                                    <span class="text-muted">No Users</span>
                                                @endforelse
                                            </td>
                                            <td>{{ $report->created_at?->format('d M Y') ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('reports.edit', $report->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this report?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No reports found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
