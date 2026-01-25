@extends('layouts.coordinator')

@section('page-content')

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }

    .btn-bisu{
        background:var(--bisu-blue) !important;
        border-color:var(--bisu-blue) !important;
        color:#fff !important;
        font-weight:700;
    }
    .btn-bisu:hover{
        background:var(--bisu-blue-2) !important;
        border-color:var(--bisu-blue-2) !important;
        color:#fff !important;
    }

    .thead-bisu th{
        background:var(--bisu-blue) !important;
        color:#fff !important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
    }
    .table td{ vertical-align:middle; white-space:nowrap; font-size:.9rem; }

    /* ✅ make modal body scrollable even if bootstrap fails */
    .modal-body{
        max-height: calc(100vh - 210px);
        overflow-y: auto;
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
        <h2 class="page-title-bisu">Upload Scholars (Bulk)</h2>
        <div class="subtext">
            Upload an <strong>Excel/CSV</strong> file. The system will match rows against your student database and check
            enrollment status in the <strong>current semester</strong>.
        </div>

        @if(!empty($currentSemester))
            <div class="mt-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Current Semester:
                    <strong>{{ $currentSemester->term ?? $currentSemester->semester_name }} {{ $currentSemester->academic_year }}</strong>
                </span>
            </div>
        @endif
    </div>

    <a href="{{ route('coordinator.manage-scholars') }}" class="btn btn-outline-secondary btn-sm">
        ← Back to Manage Scholars
    </a>
</div>

{{-- Upload Card --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="fw-bold text-secondary">Upload File</div>
        <small class="text-muted">Supported: Excel (.xlsx/.xls) • CSV</small>
    </div>

    <div class="card-body">
        <form action="{{ route('coordinator.scholars.upload.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold text-secondary mb-1">Choose file</label>
                    <input
                        type="file"
                        name="file"
                        class="form-control form-control-sm"
                        accept=".xlsx,.xls,.csv"
                        required
                    >
                    <div class="form-text">
                        The system will auto-detect headers like <code>First Name</code>, <code>FIRSTNAME</code>, <code>first_name</code>, etc.
                        It only reads: <strong>First Name, Last Name, Year Level, Enrollment Status</strong>.
                    </div>
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-bisu btn-sm w-100">
                        Process File
                    </button>

                    <a href="{{ route('coordinator.manage-scholars') }}" class="btn btn-outline-secondary btn-sm w-100">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ✅ RESULTS MODAL --}}
@if(session('results'))
<div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Comparison Results</div>
                    <small class="opacity-75">
                        Only <strong>Verified + Enrolled (Current) + Not Yet Scholar</strong> can be selected.
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Main action: Add selected as scholars --}}
            <form method="POST" action="{{ route('coordinator.scholars.upload.add-selected') }}">
                @csrf

                <input type="hidden" name="results_json" value='@json(session("results"))'>

                <div class="modal-body">

                    {{-- Assignment controls --}}
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="row g-2 align-items-end">

                                {{-- Scholarship --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary mb-1">Assign Scholarship</label>
                                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm" required>
                                        <option value="">Select scholarship...</option>
                                        @foreach(($scholarships ?? []) as $s)
                                            <option value="{{ $s->id }}">{{ $s->scholarship_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Batch --}}
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold text-secondary mb-1">Batch (optional)</label>
                                    <select name="batch_id" class="form-select form-select-sm">
                                        <option value="">No batch</option>
                                        @foreach(($batches ?? []) as $b)
                                            <option value="{{ $b->id }}">
                                                {{ $b->scholarship->scholarship_name ?? 'Scholarship' }}
                                                - Batch {{ $b->batch_number }}
                                                ({{ $b->semester->term ?? '' }} {{ $b->semester->academic_year ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        If your scholarship has batches (TDP/TES), choose a batch. Otherwise leave as No batch.
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold text-secondary mb-1">Scholar Status</label>
                                    <select name="status" class="form-select form-select-sm" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="graduated">Graduated</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-8">
                                    <div class="alert alert-info mb-0 py-2 small">
                                        Select eligible rows below, then click <strong>Process / Add Selected</strong>.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Results table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-bisu">
                                <tr>
                                    <th style="width:80px;">Select</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Year Level</th>
                                    <th>Verified</th>
                                    <th>Enrollment Status</th>
                                    <th>Current Scholarship</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach(session('results') as $index => $result)
                                    @php
                                        $verified = !empty($result['user']);
                                        $isScholar = !empty($result['is_scholar']);
                                        $enrollStatus = $result['enrollment_status'] ?? 'not_enrolled';
                                        $canSelect = $verified && ($enrollStatus === 'enrolled') && !$isScholar;
                                    @endphp

                                    <tr>
                                        <td class="text-center">
                                            @if($canSelect)
                                                <input type="checkbox" name="selected_indexes[]" value="{{ $index }}">
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>{{ $result['data']['last_name'] ?? '' }}</td>
                                        <td>{{ $result['data']['first_name'] ?? '' }}</td>
                                        <td>{{ $result['data']['year_level'] ?? '' }}</td>

                                        <td>
                                            @if($verified)
                                                <span class="badge bg-success-subtle text-success">Verified</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Not Verified</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($enrollStatus === 'enrolled')
                                                <span class="badge bg-success-subtle text-success">ENROLLED</span>
                                            @elseif($enrollStatus === 'dropped')
                                                <span class="badge bg-danger-subtle text-danger">DROPPED</span>
                                            @elseif($enrollStatus === 'graduated')
                                                <span class="badge bg-primary-subtle text-primary">GRADUATED</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">NOT ENROLLED</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($isScholar)
                                                <span class="badge bg-warning-subtle text-warning">
                                                    {{ $result['existing_scholarship_name'] ?? 'SCHOLAR' }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-bisu btn-sm">
                        Process / Add Selected
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('results'))
        const el = document.getElementById('resultsModal');
        const resultsModal = new bootstrap.Modal(el, { backdrop: 'static' });
        resultsModal.show();
    @endif
});
</script>

@endsection
