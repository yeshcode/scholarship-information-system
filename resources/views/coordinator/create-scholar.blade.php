@extends('layouts.coordinator')

@section('page-content')

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.6rem;
        color: #003366;
        margin: 0;
    }
    .subtext { color: #6b7280; font-size: .9rem; }

    .btn-bisu-primary {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
        font-weight: 600;
    }
    .btn-bisu-primary:hover { opacity: .92; color: #fff; }

    .thead-bisu {
        background: #003366;
        color: #fff;
        font-size: .78rem;
        letter-spacing: .03em;
        text-transform: uppercase;
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
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">

    {{-- LEFT: Title + subtitle --}}
    <div>
        <h2 class="page-title-blue">Add Scholar (Manual)</h2>
        <div class="subtext">
            Search a student first. 
        </div>
    </div>

    {{-- RIGHT: Back button + current semester badge --}}
    <div class="d-flex flex-column align-items-md-end gap-2">

        <a href="{{ route('coordinator.manage-scholars') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Back to Manage Scholars
        </a>

        <div>
            @if($currentSemester)
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Current Semester:
                    <strong>{{ $currentSemester->term ?? $currentSemester->semester_name }} {{ $currentSemester->academic_year }}</strong>
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                    No current semester set
                </span>
            @endif
        </div>

    </div>
</div>


{{-- Search Card --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Search Student</strong>
        <small class="text-muted">Type name or student ID</small>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('coordinator.scholars.create') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-semibold text-secondary mb-1">Search</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}"
                           class="form-control form-control-sm"
                           placeholder="Lastname, Firstname, or Student ID...">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-bisu-primary btn-sm w-100" type="submit">Search</button>
                    <a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('coordinator.scholars.create') }}">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Candidate Results --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Search Results</strong>
        {{-- <small class="text-muted">Click "Add Scholar" only if eligible</small> --}}
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th class="text-start">Student</th>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Course</th>
                    <th class="text-start">Year</th>
                    <th class="text-start">Enrolled (Current)</th>
                    <th class="text-start">Already Scholar?</th>
                    <th style="width:170px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(($candidates ?? collect())->count() === 0)
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            @if(($q ?? '') === '')
                                Search a student to show results.
                            @else
                                No matching students found.
                            @endif
                        </td>
                    </tr>
                @else
                    @foreach($candidates as $c)
                        @php
                            $disabled = (!$c->is_enrolled_current) || ($c->is_scholar) || (!$currentSemester);
                        @endphp
                        <tr>
                            <td class="text-start">
                                {{ $c->user->lastname }}, {{ $c->user->firstname }}
                            </td>
                            <td class="text-start">{{ $c->user->student_id ?? 'N/A' }}</td>
                            <td class="text-start">{{ $c->user->course->course_name ?? 'N/A' }}</td>
                            <td class="text-start">{{ $c->user->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td class="text-start">
                                @if($c->is_enrolled_current)
                                    <span class="badge bg-success-subtle text-success">ENROLLED</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">NOT ENROLLED</span>
                                @endif
                            </td>
                            <td class="text-start">
                                @if($c->is_scholar)
                                    <span class="badge bg-warning-subtle text-warning">YES</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">NO</span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-bisu-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addScholarModal"
                                        data-student-id="{{ $c->user->id }}"
                                        data-student-name="{{ $c->user->firstname }} {{ $c->user->lastname }}"
                                        {{ $disabled ? 'disabled' : '' }}>
                                    Add Scholar
                                </button>

                                @if(!$currentSemester)
                                    <div class="small text-muted mt-1">No current semester</div>
                                @elseif($c->is_scholar)
                                    <div class="small text-muted mt-1">Already a scholar</div>
                                @elseif(!$c->is_enrolled_current)
                                    <div class="small text-muted mt-1">Not enrolled current sem</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Scholar Records --}}
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Scholar Records</strong>
        <small class="text-muted">Existing scholars</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-start">Student</th>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Scholarship</th>
                    <th class="text-start">Batch</th>
                    <th class="text-start">Status</th>
                    <th class="text-start">Date Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scholars as $s)
                    <tr>
                        <td class="text-start">{{ $s->user->lastname ?? 'N/A' }}, {{ $s->user->firstname ?? 'N/A' }}</td>
                        <td class="text-start">{{ $s->user->student_id ?? 'N/A' }}</td>
                        <td class="text-start">{{ $s->scholarship->scholarship_name ?? 'N/A' }}</td>
                        <td class="text-start">{{ $s->scholarshipBatch->batch_number ?? 'N/A' }}</td>
                        <td class="text-start">
                            @if(($s->status ?? '') === 'active')
                                <span class="badge bg-success-subtle text-success">
                                    ACTIVE
                                </span>
                            @elseif(($s->status ?? '') === 'inactive')
                                <span class="badge bg-danger-subtle text-danger">
                                    INACTIVE
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    {{ strtoupper($s->status ?? 'N/A') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-start">{{ $s->date_added ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No scholars found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body pt-3">
        {{ $scholars->links() }}
    </div>
</div>

{{-- ===================================================== --}}
{{-- MODAL: Add Scholar (ENHANCED) --}}
{{-- ===================================================== --}}
<div class="modal fade" id="addScholarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('coordinator.scholars.store') }}" class="modal-content border-0 shadow">
            @csrf

            <div class="modal-header" style="background:#003366; color:#fff;">
                <div class="d-flex flex-column">
                    <div class="fw-bold fs-5">Add Scholar</div>
                    <small class="opacity-75">Select scholarship + batch, then confirm details</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" name="student_id" id="modal_student_id">

                {{-- Student Preview --}}
                <div class="p-3 rounded-3 border mb-3" style="background:#f8fafc;">
                    <div class="small text-muted mb-1">Selected Student</div>
                    <input type="text" id="modal_student_name"
                           class="form-control form-control-sm fw-semibold"
                           readonly>
                </div>

                <div class="row g-3">
                    {{-- Scholarship --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-1">
                            Scholarship <span class="text-danger">*</span>
                        </label>
                        <select name="scholarship_id" id="modal_scholarship_id" class="form-select form-select-sm" required>
                            <option value="">Select scholarship...</option>
                            @foreach($scholarships as $sch)
                                <option value="{{ $sch->id }}">
                                    {{ $sch->scholarship_name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- <div class="form-text">Example: DOST, DOST-JLSS, TES, TDP</div> --}}
                    </div>

                    {{-- Batch (depends on scholarship) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-1">
                            Batch <span class="text-danger">*</span>
                        </label>

                        {{-- This is the real field submitted --}}
                        <select name="batch_id" id="modal_batch_id" class="form-select form-select-sm" required disabled>
                            <option value="">Select scholarship first...</option>
                        </select>

                        {{-- <div class="form-text">Batch list changes based on selected scholarship.</div> --}}

                        {{-- Hidden “all batches” data source (from your existing $batches) --}}
                        <select id="__all_batches" class="d-none">
                            @foreach($batches as $b)
                                <option
                                    value="{{ $b->id }}"
                                    data-scholarship-id="{{ $b->scholarship_id }}"
                                >
                                    Batch {{ $b->batch_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Added --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-1">
                            Date Added <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="date_added" class="form-control form-control-sm" required>
                        {{-- <div class="form-text">This will be saved as the scholar’s official start date.</div> --}}
                    </div>

                    {{-- Notes / Info --}}
                    {{-- <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-1">Note</label>
                        <div class="alert alert-info py-2 mb-0 small">
                            <strong>Reminder:</strong> Semester and Status are removed here.
                            This modal is only for linking the student to a scholarship + batch.
                        </div>
                    </div>
                </div> --}}

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger mt-3 mb-0">
                        <div class="fw-semibold">Please fix the errors:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" class="btn btn-bisu-primary btn-sm">
                    Save Scholar
                </button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addScholarModal');
    if (!modal) return;

    const studentIdEl   = document.getElementById('modal_student_id');
    const studentNameEl = document.getElementById('modal_student_name');

    const scholarshipEl = document.getElementById('modal_scholarship_id');
    const batchEl       = document.getElementById('modal_batch_id');
    const allBatchesEl  = document.getElementById('__all_batches');

    function resetBatchSelect(message = 'Select scholarship first...') {
        batchEl.innerHTML = `<option value="">${message}</option>`;
        batchEl.disabled = true;
    }

    function loadBatchesForScholarship(scholarshipId) {
        resetBatchSelect('Select batch...');

        if (!scholarshipId) {
            resetBatchSelect('Select scholarship first...');
            return;
        }

        const options = Array.from(allBatchesEl.options)
            .filter(opt => opt.dataset.scholarshipId === scholarshipId);

        if (options.length === 0) {
            resetBatchSelect('No batches found for this scholarship');
            return;
        }

        // Populate
        batchEl.innerHTML = `<option value="">Select batch...</option>`;
        options.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt.value;
            o.textContent = opt.textContent; // "Batch X"
            batchEl.appendChild(o);
        });

        batchEl.disabled = false;
    }

    // When opening modal: set student data + reset scholarship/batch
    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const studentId = btn.getAttribute('data-student-id');
        const studentName = btn.getAttribute('data-student-name');

        studentIdEl.value = studentId || '';
        studentNameEl.value = studentName || '';

        // reset fields every open
        scholarshipEl.value = '';
        resetBatchSelect('Select scholarship first...');
    });

    // Scholarship change => reload batch list
    scholarshipEl.addEventListener('change', function () {
        loadBatchesForScholarship(this.value);
    });
});
</script>


@endsection
