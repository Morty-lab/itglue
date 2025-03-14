@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Submissions</h5>
                    <span class="badge bg-light text-dark">{{ count($submissions) }} Submissions</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>User</th>
                                    <th>Submitted At</th>
                                    <th>Employees</th>
                                    <th>Devices</th>
                                    <th>Licenses</th>
                                    <th>Branches</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $submission)
                                    <tr>
                                        <td>{{ $submission['company'] ? $submission['company']->company_name : 'Not submitted' }}</td>
                                        <td>{{ $submission['user']->email }}</td>
                                        <td>{{ $submission['submitted_at'] ? $submission['submitted_at']->format('M d, Y H:i') : 'Not submitted' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $submission['counts']['employees'] > 0 ? 'success' : 'secondary' }}">
                                                {{ $submission['counts']['employees'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $submission['counts']['devices'] > 0 ? 'success' : 'secondary' }}">
                                                {{ $submission['counts']['devices'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $submission['counts']['licenses'] > 0 ? 'success' : 'secondary' }}">
                                                {{ $submission['counts']['licenses'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $submission['counts']['branches'] > 0 ? 'success' : 'secondary' }}">
                                                {{ $submission['counts']['branches'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($submission['status'] == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($submission['status'] == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.submission.show', $submission['user']->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>

                                                <!-- Review button - opens modal -->
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission['user']->id }}">
                                                    <i class="fas fa-check-circle"></i> Review
                                                </button>
                                            </div>

                                            <!-- Review Modal -->
                                            <div class="modal fade" id="reviewModal{{ $submission['user']->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $submission['user']->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="reviewModalLabel{{ $submission['user']->id }}">
                                                                Review Submission - {{ $submission['company'] ? $submission['company']->company_name : $submission['user']->email }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.submission.update', $submission['user']->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select class="form-select" id="status" name="status" required>
                                                                        <option value="pending" {{ $submission['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="approved" {{ $submission['status'] == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                        <option value="rejected" {{ $submission['status'] == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="feedback" class="form-label">Feedback</label>
                                                                    <textarea class="form-control" id="feedback" name="feedback" rows="3">{{ $submission['company'] ? $submission['company']->admin_feedback : '' }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No submissions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript for the admin dashboard here
});
</script>
@endpush
