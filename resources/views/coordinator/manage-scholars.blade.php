@extends('layouts.coordinator')

@section('page-content')

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --bisu-soft:#f4f7fb;
    }

    /* Titles */
    .page-title-bisu{
        font-weight:800;
        font-size:1.6rem;
        color:var(--bisu-blue);
        margin:0;
    }
    .subtext{
        color:#6b7280;
        font-size:.9rem;
    }

    /* Buttons */
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

    /* Cards */
    .card-bisu{
        border:1px solid #e5e7eb;
        border-radius:14px;
        overflow:hidden;
    }
    .card-bisu .card-header{
        background:#fff;
        border-bottom:1px solid #eef2f7;
    }

    /* Table */
    .thead-bisu th{
        background:var(--bisu-blue) !important;
        color:#fff !important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
    }
    .table td{
        vertical-align:middle;
        white-space:nowrap;
        font-size:.9rem;
    }

    /* Filter layout extras */
    .filter-label{
        font-weight:700;
        color:#475569;
        margin-bottom:.35rem;
        font-size:.85rem;
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
        <h2 class="page-title-bisu">Manage Scholars</h2>
        <div class="subtext">Filter by scholarship/batch and quickly search a student by name.</div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('coordinator.scholars.create') }}" class="btn btn-bisu btn-sm">
            Add Scholar
        </a>
        <a href="{{ route('coordinator.scholars.upload') }}" class="btn btn-bisu btn-sm">
            Upload File Scholars
        </a>

    </div>
</div>

{{-- Filters --}}
<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted"></small>
    </div>

    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('coordinator.manage-scholars') }}">

            {{-- Row 1: Scholarship + Batch side-by-side --}}
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="filter-label">Scholarship</label>
                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm">
                        <option value="">All Scholarships</option>
                        @foreach($scholarships as $s)
                            <option value="{{ $s->id }}"
                                {{ (string)request('scholarship_id') === (string)$s->id ? 'selected' : '' }}>
                                {{ $s->scholarship_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label class="filter-label">Batch (TDP/TES only)</label>
                    <select name="batch_id" id="batch_id" class="form-select form-select-sm">
                        <option value="">All Batches</option>

                        @foreach(($batchOptions ?? []) as $b)
                            <option value="{{ $b->id }}"
                                {{ (string)request('batch_id') === (string)$b->id ? 'selected' : '' }}>
                                Batch {{ $b->batch_number }}
                                ({{ $b->semester->term ?? '' }} {{ $b->semester->academic_year ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    <div id="batchHelp" class="form-text text-muted"></div>
                </div>
            </div>

            {{-- Row 2: Long search (below) --}}
            <div class="row g-3 mt-1">
                <div class="col-12">
                    <label class="filter-label">Search Student</label>
                    <input
                        type="text"
                        name="q"
                        id="q"
                        value="{{ request('q') }}"
                        class="form-control form-control-sm"
                        placeholder="Type student name or ID"
                        autocomplete="off"
                    >
                </div>
            </div>

        </form>
    </div>
</div>

{{-- Table --}}
<div class="card card-bisu shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Scholar List</div>

        @if(isset($selectedSemester))
            <small class="text-muted">
                Semester:
                <strong>
                    {{ $selectedSemester->term ?? $selectedSemester->semester_name ?? '' }}
                    {{ $selectedSemester->academic_year ?? '' }}
                </strong>
            </small>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Student ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Enrolled Status</th>
                    <th>Semester Enrolled</th>
                    <th>Scholarship</th>
                    <th>Batch No.</th>
                    <th>Date Added</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($scholars as $scholar)
                    @php
                        // Enrollment info from controller aliases (recommended)
                        $enrolledStatus = $scholar->enrolled_status ?? 'not_enrolled';
                        $semLabel = ($scholar->enrolled_term && $scholar->enrolled_academic_year)
                            ? ($scholar->enrolled_term . ' ' . $scholar->enrolled_academic_year)
                            : 'N/A';

                        $schName = strtoupper($scholar->scholarship->scholarship_name ?? '');
                        $isTdpTesRow = str_contains($schName, 'TDP') || str_contains($schName, 'TES');
                        $batchLabel = $isTdpTesRow ? ($scholar->scholarshipBatch->batch_number ?? 'N/A') : 'N/A';
                    @endphp

                    <tr>
                        <td>{{ $scholar->u_student_id ?? $scholar->user->student_id ?? 'N/A' }}</td>
                        <td>{{ $scholar->u_lastname ?? $scholar->user->lastname ?? 'N/A' }}</td>
                        <td>{{ $scholar->u_firstname ?? $scholar->user->firstname ?? 'N/A' }}</td>

                        <td>
                            @if($enrolledStatus === 'enrolled')
                                <span class="badge bg-success-subtle text-success">ENROLLED</span>
                            @elseif($enrolledStatus === 'dropped')
                                <span class="badge bg-danger-subtle text-danger">DROPPED</span>
                            @elseif($enrolledStatus === 'graduated')
                                <span class="badge bg-primary-subtle text-primary">GRADUATED</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">NOT ENROLLED</span>
                            @endif
                        </td>

                        <td>{{ $semLabel }}</td>
                        <td>{{ $scholar->scholarship->scholarship_name ?? 'N/A' }}</td>
                        <td>{{ $batchLabel }}</td>
                        <td>
                            {{ $scholar->date_added ? \Carbon\Carbon::parse($scholar->date_added)->format('M d, Y') : 'N/A' }}
                        </td>
                        <td>{{ $scholar->user->course->course_name ?? 'N/A' }}</td>
                        <td>{{ $scholar->user->yearLevel->year_level_name ?? 'N/A' }}</td>

                        <td>
                            <div class="d-flex gap-1">
                                <!-- Update -->
                                <button
                                    class="btn btn-bisu btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateScholarModal"
                                    data-id="{{ $scholar->id }}"
                                    data-name="{{ ($scholar->u_lastname ?? '') }}, {{ ($scholar->u_firstname ?? '') }}"
                                    data-status="{{ $scholar->status ?? 'active' }}"
                                >
                                    Update
                                </button>


                                <!-- Delete (hard) -->
                                {{-- <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteScholarModal"
                                    data-id="{{ $scholar->id }}"
                                    data-name="{{ ($scholar->u_lastname ?? $scholar->user->lastname ?? '') }}, {{ ($scholar->u_firstname ?? $scholar->user->firstname ?? '') }}"
                                    >
                                    Delete
                                </button> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No scholars found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Update Scholar Modal -->
        <div class="modal fade" id="updateScholarModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" id="updateScholarForm">
                        @csrf
                        @method('PATCH')

                <div class="modal-header">
                <h5 class="modal-title">Update Scholar Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                <div class="mb-2">
                    <div class="fw-semibold" id="scholarNameText">Scholar</div>
                    <small class="text-muted">Set if the student is still a scholar.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="scholarStatus" class="form-select form-select-sm">
                    <option value="active">Still Scholar</option>
                    <option value="inactive">No Longer Scholar</option>
                    </select>
                </div>

                <div class="mb-2" id="dateRemovedWrap" style="display:none;">
                    <label class="form-label">Date Removed</label>
                    <input type="date" name="date_removed" id="dateRemoved" class="form-control form-control-sm">
                    <small class="text-muted">Use the date when they stopped being a scholar.</small>
                </div>
                </div>

                <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" type="submit">Save</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        <!-- Delete Scholar Modal -->
<div class="modal fade" id="deleteScholarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="deleteScholarForm">
        @csrf
        @method('DELETE')

        <div class="modal-header">
          <h5 class="modal-title">Delete Scholar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <div class="fw-semibold" id="deleteScholarName">Scholar</div>
            <small class="text-muted">
              This will permanently delete the scholar record. This cannot be undone.
            </small>
          </div>

          <div class="alert alert-warning mb-0">
            <strong>Note:</strong> If this scholar has stipend records, your controller will block deletion.
            Use <strong>Update → Inactive</strong> instead.
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger btn-sm" type="submit">Yes, Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
    </div>

    @if(method_exists($scholars, 'links'))
        <div class="card-body">
            {{ $scholars->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // =========================
  // Filters auto-submit
  // =========================
  const filterForm = document.getElementById('filterForm');
  const scholarship = document.getElementById('scholarship_id');
  const batch = document.getElementById('batch_id');
  const q = document.getElementById('q');
  const batchHelp = document.getElementById('batchHelp');

  function isTdpTesText(text){
    const t = (text || '').toUpperCase();
    return t.includes('TDP') || t.includes('TES');
  }

  function syncBatchEnabled(){
    if (!scholarship || !batch) return;

    const selectedText = scholarship.options[scholarship.selectedIndex]?.text || '';
    const enable = isTdpTesText(selectedText);

    if (!enable) {
      batch.value = "";
      batch.setAttribute('disabled', 'disabled');
      if (batchHelp) batchHelp.textContent;
    } else {
      batch.removeAttribute('disabled');
      if (batchHelp) batchHelp.textContent = "";
    }
  }

  syncBatchEnabled();

  scholarship?.addEventListener('change', () => {
    syncBatchEnabled();
    filterForm?.submit();
  });

  batch?.addEventListener('change', () => filterForm?.submit());

  let t = null;
  q?.addEventListener('input', () => {
    clearTimeout(t);
    t = setTimeout(() => filterForm?.submit(), 350);
  });


  // =========================
  // Update Modal -> PATCH route
  // =========================
  const updateModal = document.getElementById('updateScholarModal');
  const updateForm  = document.getElementById('updateScholarForm');
  const nameText    = document.getElementById('scholarNameText');
  const statusSel   = document.getElementById('scholarStatus');
  const dateWrap    = document.getElementById('dateRemovedWrap');
  const dateInput   = document.getElementById('dateRemoved');

  const updateUrlTemplate = @json(route('coordinator.scholars.update-status', ['scholar' => '__ID__']));

  function toggleDate() {
    if (!statusSel || !dateWrap || !dateInput) return;

    if (statusSel.value === 'inactive') {
      dateWrap.style.display = '';
      if (!dateInput.value) dateInput.value = new Date().toISOString().slice(0, 10);
    } else {
      dateWrap.style.display = 'none';
      dateInput.value = '';
    }
  }

  statusSel?.addEventListener('change', toggleDate);

  updateModal?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const id  = btn?.getAttribute('data-id');
    const nm  = btn?.getAttribute('data-name') || 'Scholar';
    const st  = btn?.getAttribute('data-status') || 'active';

    if (nameText) nameText.textContent = nm;

    if (updateForm && id) {
      updateForm.action = updateUrlTemplate.replace('__ID__', id);
    }

    if (statusSel) statusSel.value = st;
    toggleDate();
  });


  // =========================
  // Delete Modal -> DELETE route
  // =========================
  const delModal = document.getElementById('deleteScholarModal');
  const delForm  = document.getElementById('deleteScholarForm');
  const delName  = document.getElementById('deleteScholarName');

  const deleteUrlTemplate = @json(route('coordinator.scholars.destroy', ['scholar' => '__ID__']));

  delModal?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const id  = btn?.getAttribute('data-id');
    const nm  = btn?.getAttribute('data-name') || 'Scholar';

    if (delName) delName.textContent = nm;

    if (delForm && id) {
      delForm.action = deleteUrlTemplate.replace('__ID__', id);
    }
  });

});
</script>

@endsection
