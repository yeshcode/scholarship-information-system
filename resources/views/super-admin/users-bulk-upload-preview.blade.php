@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="mb-1">Bulk Upload Preview</h3>
            <div class="text-muted small">
                Total rows: <b>{{ $totalCount }}</b> |
                Rows with issues: <b>{{ $issuesCount }}</b>
            </div>
        </div>
        <a href="{{ route('admin.users.bulk-upload-form') }}" class="btn btn-outline-secondary btn-sm">
            Upload another file
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Line</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preview as $r)
                            <tr class="{{ !empty($r['issues']) ? 'table-warning' : '' }}">
                                <td>{{ $r['line'] }}</td>
                                <td>{{ $r['student_id'] }}</td>
                                <td>{{ $r['lastname'] }}, {{ $r['firstname'] }}</td>
                                <td>{{ $r['bisu_email'] }}</td>
                                <td>{{ $r['college'] }}</td>
                                <td>{{ $r['course'] }}</td>
                                <td>{{ $r['year_level'] }}</td>
                                <td>
                                    @if(empty($r['issues']))
                                        <span class="badge bg-success">OK</span>
                                    @else
                                        <span class="badge bg-danger">Has issues</span>
                                        <div class="small text-muted mt-1">
                                            {{ implode('; ', $r['issues']) }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.users.bulk-upload-form') }}" class="btn btn-outline-secondary">
                Cancel
            </a>

            @if($issuesCount > 0)
                <button class="btn btn-success" disabled title="Fix issues first">
                    Confirm Upload
                </button>
            @else
                <!-- Trigger modal -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Confirm Upload
                </button>
            @endif
        </div>
    </div>
</div>

@if($issuesCount === 0)
<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Bulk Upload</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        This will register <b>{{ $totalCount }}</b> students.
        Their default password will be their <b>student_id</b>.
        <div class="mt-2 text-muted small">
            Make sure the list is correct before confirming.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
        <form method="POST" action="{{ route('admin.users.bulk-upload.confirm') }}">
            @csrf
            <button type="submit" class="btn btn-success">Yes, Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
