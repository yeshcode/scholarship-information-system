@extends('layouts.coordinator')

@section('page-content')

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --line:#e5e7eb;
        --muted:#6b7280;
        --soft:#f8fafc;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:var(--muted); font-size:.9rem; }

    .btn-bisu{
        background:var(--bisu-blue) !important;
        border-color:var(--bisu-blue) !important;
        color:#fff !important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2) !important; border-color:var(--bisu-blue-2) !important; }

    .card-bisu{
        border:1px solid var(--line);
        border-radius:14px;
        overflow:hidden;
    }

    .thead-bisu th{
        background:var(--bisu-blue) !important;
        color:#fff !important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
        vertical-align:middle;
        text-align:center;
    }

    .table td{ vertical-align:middle; font-size:.9rem; }

    /* Modal theme */
    .modal-bisu .modal-header{
        background: var(--bisu-blue);
        color:#fff;
        border-bottom: 0;
    }
    .modal-bisu .btn-close{
        filter: invert(1);
        opacity: .9;
    }
    .modal-bisu .modal-content{
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
    }
    .modal-bisu .help-note{
        background: var(--soft);
        border: 1px dashed var(--line);
        border-radius: 12px;
        padding: .6rem .75rem;
        color: var(--muted);
        font-size: .85rem;
    }
    .readonly-hint{
        font-size: .78rem;
        color: var(--muted);
    }
    .form-control[readonly], .form-select[disabled]{
        background: #f9fafb;
        cursor: not-allowed;
    }
</style>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Header --}}
<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Manage Stipend</h2>
        <div class="subtext">Create and manage release schedules for TDP/TES batches.</div>
    </div>

    <a href="{{ route('coordinator.stipend-releases.create') }}" class="btn btn-bisu btn-sm">
        Add Stipend
    </a>
</div>

<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted">Semester filter is based on release record</small>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('coordinator.manage-stipend-releases') }}">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label-bisu">Release Semester</label>
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All semesters</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ (string)$semesterId === (string)$sem->id ? 'selected' : '' }}>
                                {{ $sem->term ?? $sem->semester_name }} {{ $sem->academic_year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button>
                    <a href="{{ route('coordinator.manage-stipend-releases') }}" class="btn btn-outline-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Release Schedule List</div>
        <small class="text-muted">Newest first</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Scholarship</th>
                    <th>Batch</th>
                    <th>Release Semester</th>
                    <th>Title</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($releases as $release)
                    @php
                        $batch = $release->scholarshipBatch;
                        $schName = $batch?->scholarship?->scholarship_name ?? 'N/A';
                        $batchLabel = $batch ? ('Batch ' . $batch->batch_number) : 'N/A';

                        $releaseSemLabel = $release->semester
                            ? (($release->semester->term ?? $release->semester->semester_name) . ' ' . $release->semester->academic_year)
                            : 'N/A';

                        $status = $release->status ?? '';
                        $statusLabel = match($status) {
                            'for_billing' => 'For Billing',
                            'for_check' => 'For Check',
                            'for_release' => 'For Release',
                            'received' => 'Received',
                            default => strtoupper($status ?: 'N/A')
                        };

                        $badge = match($status) {
                            'for_billing' => 'bg-warning-subtle text-warning',
                            'for_check' => 'bg-primary-subtle text-primary',
                            'for_release' => 'bg-success-subtle text-success',
                            'received' => 'bg-secondary-subtle text-secondary',
                            default => 'bg-light text-dark'
                        };

                        // unique modal ids
                        $editModalId = 'editReleaseModal_' . $release->id;
                        $deleteModalId = 'deleteReleaseModal_' . $release->id;
                    @endphp

                    <tr>
                        <td>{{ $schName }}</td>
                        <td>{{ $batchLabel }}</td>
                        <td>{{ $releaseSemLabel }}</td>

                        <td class="fw-semibold">{{ $release->title }}</td>
                        <td class="text-end">₱ {{ number_format((float)$release->amount, 2) }}</td>
                        <td><span class="badge {{ $badge }}">{{ $statusLabel }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('coordinator.stipend-releases.form', $release->id) }}" class="btn btn-sm btn-outline-secondary">
                                Form
                            </a>

                            {{-- EDIT (Modal) --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $editModalId }}">
                                Edit
                            </button>

                            {{-- DELETE (Modal) --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $deleteModalId }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    {{-- =========================
                         EDIT MODAL (Status only)
                         ========================= --}}
                    <div class="modal fade modal-bisu" id="{{ $editModalId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>
                                        <div class="fw-bold">Edit Release Status</div>
                                        <div class="small opacity-75">Only the status is editable.</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('coordinator.stipend-releases.update', $release->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- REQUIRED BY VALIDATION (hidden, unchanged) --}}
                                    <input type="hidden" name="batch_id" value="{{ $release->batch_id }}">
                                    <input type="hidden" name="semester_id" value="{{ $release->semester_id }}">
                                    <input type="hidden" name="title" value="{{ $release->title }}">
                                    <input type="hidden" name="amount" value="{{ $release->amount }}">
                                    <input type="hidden" name="notes" value="{{ $release->notes }}">

                                    <div class="modal-body">
                                        <div class="help-note mb-3">
                                            🔒 The fields below are for reference only and cannot be edited here.
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label mb-1">Scholarship</label>
                                                <input type="text" class="form-control form-control-sm" value="{{ $schName }}" readonly>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label mb-1">Batch</label>
                                                <input type="text" class="form-control form-control-sm" value="{{ $batchLabel }}" readonly>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label mb-1">Release Semester</label>
                                                <input type="text" class="form-control form-control-sm" value="{{ $releaseSemLabel }}" readonly>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label mb-1">Title</label>
                                                <input type="text" class="form-control form-control-sm" value="{{ $release->title }}" readonly>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label mb-1">Amount</label>
                                                <input type="text" class="form-control form-control-sm" value="₱ {{ number_format((float)$release->amount, 2) }}" readonly>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label mb-1">Status (Editable)</label>
                                                <select name="status" class="form-select form-select-sm" required>
                                                    <option value="for_billing" {{ $release->status === 'for_billing' ? 'selected' : '' }}>For Billing</option>
                                                    <option value="for_check" {{ $release->status === 'for_check' ? 'selected' : '' }}>For Check</option>
                                                    <option value="for_release" {{ $release->status === 'for_release' ? 'selected' : '' }}>For Release</option>
                                                    <option value="received" {{ $release->status === 'received' ? 'selected' : '' }}>Received</option>
                                                </select>
                                                <div class="readonly-hint mt-1">This is the only editable field.</div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label mb-1">Notes</label>
                                                <textarea class="form-control form-control-sm" rows="3" readonly>{{ $release->notes }}</textarea>
                                                <div class="readonly-hint mt-1">Read-only</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer bg-white">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-bisu btn-sm">Update Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- =========================
                         DELETE MODAL (Confirm)
                         ========================= --}}
                    <div class="modal fade modal-bisu" id="{{ $deleteModalId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>
                                        <div class="fw-bold">Confirm Delete</div>
                                        <div class="small opacity-75">This action cannot be undone.</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="alert alert-danger mb-3">
                                        Are you sure you want to delete this stipend release schedule?
                                    </div>

                                    <div class="border rounded-3 p-3">
                                        <div class="row g-2 small">
                                            <div class="col-12"><span class="text-muted">Scholarship:</span> <span class="fw-semibold">{{ $schName }}</span></div>
                                            <div class="col-12"><span class="text-muted">Batch:</span> <span class="fw-semibold">{{ $batchLabel }}</span></div>
                                            <div class="col-12"><span class="text-muted">Release Semester:</span> <span class="fw-semibold">{{ $releaseSemLabel }}</span></div>
                                            <div class="col-12"><span class="text-muted">Title:</span> <span class="fw-semibold">{{ $release->title }}</span></div>
                                            <div class="col-12"><span class="text-muted">Amount:</span> <span class="fw-semibold">₱ {{ number_format((float)$release->amount, 2) }}</span></div>
                                            <div class="col-12"><span class="text-muted">Status:</span> <span class="badge {{ $badge }}">{{ $statusLabel }}</span></div>
                                            <div class="col-12"><span class="text-muted">Notes:</span> <span class="fw-semibold">{{ $release->notes ?: 'None' }}</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer bg-white">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>

                                    <form action="{{ route('coordinator.stipend-releases.destroy', $release->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No release schedules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body">
        {{ $releases->links() }}
    </div>
</div>

@endsection