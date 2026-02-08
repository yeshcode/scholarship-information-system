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
        background:var(--bisu-blue)!important;
        border-color:var(--bisu-blue)!important;
        color:#fff!important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }

    .card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
    .card-bisu .card-header{ background:#fff; border-bottom:1px solid #eef2f7; }

    .thead-bisu th{
        background:var(--bisu-blue)!important;
        color:#fff!important;
        font-size:.78rem;
        letter-spacing:.03em;
        text-transform:uppercase;
        white-space:nowrap;
    }
    .table td{ vertical-align:middle; white-space:nowrap; font-size:.9rem; }
    .filter-label{ font-weight:700; color:#475569; margin-bottom:.35rem; font-size:.85rem; }

    .row-disabled{
    background:#f1f5f9 !important;
    color:#94a3b8;
    }
    .row-disabled input[type="checkbox"]{
        pointer-events:none;
    }
    .sticky-actions{
        position: sticky;
        bottom: 0;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: .75rem;
        z-index: 5;
    }

</style>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Action failed:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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

<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Manage Stipends</h2>
        <div class="subtext">
            Filter Scholarship → Batch → Release. Bulk-assign stipend schedules to eligible scholars.
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

    <div class="d-flex gap-2">
        <button class="btn btn-bisu btn-sm" data-bs-toggle="modal" data-bs-target="#bulkSelectModal">
            + Bulk Assign Stipend
        </button>
    </div>
</div>

{{-- FILTERS --}}
<div class="card card-bisu shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Filters</div>
        <small class="text-muted">Scholarship • Batch • Release • Search</small>
    </div>

    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('coordinator.manage-stipends') }}">
            <div class="row g-3">

                <div class="col-12 col-md-3">
                    <label class="filter-label">Scholarship</label>
                    <select name="scholarship_id" id="scholarship_id" class="form-select form-select-sm">
                        <option value="">Select scholarship…</option>
                        @foreach($scholarships as $s)
                            <option value="{{ $s->id }}" {{ (string)request('scholarship_id')===(string)$s->id?'selected':'' }}>
                                {{ $s->scholarship_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Batch</label>
                    <select name="batch_id" id="batch_id" class="form-select form-select-sm">
                        <option value="">Select batch…</option>
                        @foreach($batches as $b)
                            <option value="{{ $b->id }}" {{ (string)request('batch_id')===(string)$b->id?'selected':'' }}>
                                Batch {{ $b->batch_number }}
                                ({{ $b->semester->term ?? '' }} {{ $b->semester->academic_year ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Release Schedule</label>
                    <input type="hidden" name="stipend_release_id" id="s2_release_id_hidden">

                        <div class="col-12">
                        <label class="filter-label">Stipend Release Schedule</label>
                        <input type="text" id="s2_release_title" class="form-control form-control-sm" readonly>
                        <div class="form-text">Chosen from Step 1 (Set A / Set B).</div>
                        </div>

                </div>

                <div class="col-12 col-md-3">
                    <label class="filter-label">Search scholar</label>
                    <input type="text" name="q" id="q" class="form-control form-control-sm"
                           value="{{ request('q') }}" placeholder="Lastname / Firstname / Student ID">
                </div>

                <div class="col-12 col-md-3">
                <label class="filter-label">Stipend Status</label>
                <select name="stipend_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="for_release" {{ request('stipend_status')==='for_release'?'selected':'' }}>For Release</option>
                    <option value="received" {{ request('stipend_status')==='received'?'selected':'' }}>Received</option>
                </select>
                </div>


            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card card-bisu shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold text-secondary">Stipend Records</div>
        <small class="text-muted">Showing created stipend rows</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    <th>Scholar</th>
                    <th>Scholarship</th>
                    <th>Batch</th>
                    <th>Release Title</th>
                    <th>Release Status</th>
                    <th>Release At</th>
                    <th>Received At</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($stipends as $stipend)
                    @php
                        $rel = $stipend->stipendRelease;
                        $relStatusLabel = strtoupper(str_replace('_',' ', $rel->status ?? ''));
                    @endphp
                    <tr>
                        <td>{{ $stipend->scholar->user->firstname ?? 'N/A' }} {{ $stipend->scholar->user->lastname ?? '' }}</td>
                        <td>{{ $stipend->scholar->scholarship->scholarship_name ?? 'N/A' }}</td>
                        <td>Batch {{ $stipend->scholar->scholarshipBatch->batch_number ?? 'N/A' }}</td>
                        <td>{{ $rel->title ?? 'N/A' }}</td>
                        <td><span class="badge bg-info-subtle text-info">{{ $relStatusLabel ?: 'N/A' }}</span></td>

                        <td>
                            @if($stipend->release_at)
                                {{ \Carbon\Carbon::parse($stipend->release_at)->format('M d, Y h:i A') }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($stipend->received_at)
                                {{ \Carbon\Carbon::parse($stipend->received_at)->format('M d, Y h:i A') }}
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ number_format((float)$stipend->amount_received, 2) }}</td>
                        <td>{{ strtoupper(str_replace('_',' ', $stipend->status)) }}</td>

                        <td class="text-end">
                            <a href="{{ route('coordinator.stipends.edit', $stipend->id) }}" class="text-primary me-2">Edit</a>
                            <a href="{{ route('coordinator.stipends.confirm-delete', $stipend->id) }}" class="text-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No stipend records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-body">
        {{ $stipends->links() }}
    </div>
</div>

{{-- BULK ASSIGN MODAL --}}
{{-- MODAL 1: SELECT SCHOLARS --}}
<div class="modal fade" id="bulkSelectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header" style="background:var(--bisu-blue); color:#fff;">
        <div>
          <div class="fw-bold">Bulk Assign Stipend — Step 1</div>
          <small class="opacity-75">Filter & select scholars. Eligible records appear first.</small>
        </div>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        {{-- Filters INSIDE modal (optional: you can reuse your page filters too) --}}
        <div class="row g-3 mb-3">
          <div class="col-12 col-md-4">
            <label class="filter-label">Scholarship</label>
            <select id="m_scholarship_id" class="form-select form-select-sm">
              <option value="">Select scholarship…</option>
              @foreach($scholarships as $s)
                <option value="{{ $s->id }}">{{ $s->scholarship_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12 col-md-4">
            <label class="filter-label">Batch</label>
            <select id="m_batch_id" class="form-select form-select-sm">
              <option value="">Select batch…</option>
              @foreach($batches as $b)
                <option value="{{ $b->id }}" data-sch="{{ $b->scholarship_id }}">
                    Batch {{ $b->batch_number }}
                    </option>
              @endforeach
            </select>
            <div class="form-text">Batch dropdown will be filtered by scholarship.</div>
          </div>

          <div class="col-12 col-md-4">
            <label class="filter-label">Release Schedule</label>
            <select id="m_release_id" class="form-select form-select-sm" disabled>
                <option value="">Select batch first…</option>
            </select>
            <div class="form-text">Select the schedule you are filling (Set A / Set B).</div>
            </div>


          <div class="col-12 col-md-4">
            <label class="filter-label">Search scholar</label>
            <input type="text" id="m_q" class="form-control form-control-sm" placeholder="Lastname / Firstname / Student ID">
          </div>

          <div class="col-12">
            <div class="alert alert-info small mb-0">
              Eligible = <strong>ENROLLED or GRADUATED</strong> in the <strong>active semester</strong>.
              Ineligible records are shown but disabled (gray) and placed at the bottom.
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0" id="scholarsPickTable">
            <thead class="thead-bisu">
              <tr>
                <th style="width:70px;">
                  <input type="checkbox" id="checkAllEligible">
                </th>
                <th>Student ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Enrollment Status</th>
                <th>Scholarship</th>
                <th>Batch</th>
                <th>Note</th>
              </tr>
            </thead>

            <tbody>
              @foreach($eligibleScholars as $row)
                @php
                  // expected fields from controller:
                  // $row->is_selectable (bool)
                  // $row->enrollment_status_label (string)
                  // $row->note (string)
                @endphp

                <tr class="{{ $row->is_selectable && !$row->has_stipend_in_batch ? '' : 'row-disabled' }}"
                    data-sch="{{ $row->scholarship_id }}"
                    data-batch="{{ $row->batch_id }}"
                    data-scholar-id="{{ $row->id }}"
                    data-hasstipend="{{ $row->has_stipend_in_batch ? '1' : '0' }}"
                    data-name="{{ strtolower(($row->user->lastname ?? '').' '.($row->user->firstname ?? '')) }}"
                    data-studentid="{{ strtolower($row->user->student_id ?? '') }}"
                    data-selectable="{{ ($row->is_selectable && !$row->has_stipend_in_batch) ? '1' : '0' }}">

                                    

                  <td class="text-center">
                    @if($row->is_selectable && !$row->has_stipend_in_batch)
                        <input type="checkbox" class="pickScholar" value="{{ $row->id }}">
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                    </td>


                  <td>{{ $row->user->student_id ?? '' }}</td>
                  <td>{{ $row->user->lastname ?? '' }}</td>
                  <td>{{ $row->user->firstname ?? '' }}</td>
                  <td>
                    <span class="badge {{ $row->is_selectable ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                      {{ $row->enrollment_status_label }}
                    </span>
                  </td>

                  <td>{{ $row->scholarship->scholarship_name ?? '' }}</td>
                  <td>Batch {{ $row->scholarshipBatch->batch_number ?? '' }}</td>

                  <td class="small {{ $row->has_stipend_in_batch ? 'text-secondary fst-italic' : 'text-muted' }}">
                    {{ $row->note }}
                    </td>

                    <td class="small">
                        <span class="noteText">{{ $row->note }}</span>
                        </td>


                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>

      <div class="sticky-actions d-flex justify-content-between align-items-center">
        <div class="small text-muted">
          Selected scholars: <span class="fw-bold" id="selectedCount">0</span>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-bisu btn-sm" id="proceedToSchedule" type="button" disabled>
            Proceed →
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- MODAL 2: SET SCHEDULE + CONFIRM --}}
<div class="modal fade" id="bulkScheduleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header" style="background:var(--bisu-blue); color:#fff;">
        <div>
          <div class="fw-bold">Bulk Assign Stipend — Step 2</div>
          <small class="opacity-75">Set release schedule & date/time. Status will be set to FOR RELEASE automatically.</small>
        </div>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('coordinator.stipends.bulk-assign-v2') }}" id="bulkScheduleForm">
        @csrf

        <div class="modal-body">

          <input type="hidden" name="scholarship_id" id="s2_scholarship_id">
          <input type="hidden" name="batch_id" id="s2_batch_id">

          <div class="row g-3">
            <div class="col-12">
              <div class="alert alert-info small mb-0">
                Selected scholars: <strong id="s2_selectedCount">0</strong>
              </div>
            </div>

            <div class="col-12">
              <label class="filter-label">Stipend Release Schedule (must match batch)</label>
              <select name="stipend_release_id" id="s2_release_id" class="form-select form-select-sm" required>
                <option value="">Select release…</option>
                @foreach($releases as $r)
                  <option value="{{ $r->id }}" data-batch="{{ $r->batch_id }}">
                    {{ $r->title }} ({{ strtoupper(str_replace('_',' ', $r->status ?? '')) }})
                  </option>
                @endforeach
              </select>
              <div class="form-text">Only schedules for the selected batch should remain selectable.</div>
            </div>

            <div class="col-12">
              <label class="filter-label">Release Date & Time</label>
              <input type="datetime-local" name="release_at" class="form-control form-control-sm" required>
            </div>

            <div class="col-12">
              <div class="alert alert-warning small mb-0">
                Amount will be taken from the selected release schedule. (No manual amount input.)
              </div>
            </div>

          </div>

          <hr class="my-3">

          <div class="fw-bold mb-2">Selected scholars preview</div>
          <div class="small text-muted mb-2">This is a quick preview; the final list will be submitted.</div>
          <ul class="small" id="selectedPreviewList"></ul>

          {{-- Selected IDs will be injected here --}}
          <div id="selectedInputs"></div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" type="button">Back</button>
          <button class="btn btn-bisu btn-sm" type="submit">Submit & Notify Scholars</button>
        </div>
      </form>

    </div>
  </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {

  const selectModalEl = document.getElementById('bulkSelectModal');
  const scheduleModalEl = document.getElementById('bulkScheduleModal');

  const mScholarship = document.getElementById('m_scholarship_id');
  const mBatch = document.getElementById('m_batch_id');
  const mRelease = document.getElementById('m_release_id');
  const mQ = document.getElementById('m_q');

  const proceedBtn = document.getElementById('proceedToSchedule');
  const selectedCountEl = document.getElementById('selectedCount');

  const checkAllEligible = document.getElementById('checkAllEligible');
  const table = document.getElementById('scholarsPickTable');

  // Step 2 fields
  const s2ScholarshipId = document.getElementById('s2_scholarship_id');
  const s2BatchId = document.getElementById('s2_batch_id');
  const s2ReleaseHidden = document.getElementById('s2_release_id_hidden');
  const s2ReleaseTitle = document.getElementById('s2_release_title');
  const s2SelectedCount = document.getElementById('s2_selectedCount');

  // endpoints
  const urlReleasesByBatch = @json(route('coordinator.stipend-releases.by-batch'));
  const urlPickMeta = @json(route('coordinator.stipends.pick-meta'));

  function getRows(){
    return Array.from(table.querySelectorAll('tbody tr'));
  }

  // ---------- Scholarship -> Batch filtering (your current behavior)
  function filterBatchOptions(){
    const sch = mScholarship.value;
    Array.from(mBatch.options).forEach(opt => {
      if (!opt.value) return;
      const optSch = opt.getAttribute('data-sch');
      opt.hidden = sch && optSch !== sch;
    });
    if (mBatch.selectedOptions[0]?.hidden) mBatch.value = '';
  }

  // ---------- Apply search/batch/sch filters to row visibility
  function applyRowFilters(){
    const sch = mScholarship.value;
    const batchVal = mBatch.value;
    const search = (mQ.value || '').trim().toLowerCase();

    getRows().forEach(tr => {
      const trSch = tr.getAttribute('data-sch');
      const trBatch = tr.getAttribute('data-batch');
      const name = tr.getAttribute('data-name') || '';
      const sid = tr.getAttribute('data-studentid') || '';

      let ok = true;
      if (sch && trSch !== sch) ok = false;
      if (batchVal && trBatch !== batchVal) ok = false;
      if (search && !(name.includes(search) || sid.includes(search))) ok = false;

      tr.style.display = ok ? '' : 'none';
    });

    applyEligibilityAndBlock(); // ✅ re-apply disable state after filters
    syncSelected();
  }

  // ---------- Enable/disable rows based on release meta
  let metaEligible = new Set();
  let metaBlocked = new Set();
  let metaLoaded = false;

  function setRowDisabled(tr, reasonText){
    tr.classList.add('row-disabled');
    tr.setAttribute('data-selectable', '0');

    const cb = tr.querySelector('.pickScholar');
    if (cb) cb.checked = false;

    const note = tr.querySelector('.noteText');
    if (note) note.textContent = reasonText || 'Not selectable';

    // Hide checkbox cell if you want, but simplest: keep it and just disable
    const cell = tr.querySelector('td:first-child');
    if (cell) {
      cell.innerHTML = '<span class="text-muted small">—</span>';
    }
  }

  function setRowEnabled(tr){
    tr.classList.remove('row-disabled');
    tr.setAttribute('data-selectable', '1');

    const sid = tr.getAttribute('data-scholar-id');
    const cell = tr.querySelector('td:first-child');
    if (cell) {
      cell.innerHTML = `<input type="checkbox" class="pickScholar" value="${sid}">`;
    }

    const note = tr.querySelector('.noteText');
    if (note) note.textContent = 'Selectable';
  }

  function applyEligibilityAndBlock(){
    const batchVal = mBatch.value;
    const releaseVal = mRelease.value;

    // If no release chosen yet, don't allow selections
    if (!batchVal || !releaseVal || !metaLoaded) {
      getRows().forEach(tr => {
        if (tr.style.display === 'none') return;
        setRowDisabled(tr, 'Select a release schedule first.');
      });
      return;
    }

    getRows().forEach(tr => {
      if (tr.style.display === 'none') return;

      // only rows of chosen batch should remain visible already; still safe:
      if (tr.getAttribute('data-batch') !== batchVal) return;

      const scholarId = tr.getAttribute('data-scholar-id');
      if (!scholarId) return;

      if (!metaEligible.has(scholarId)) {
        setRowDisabled(tr, 'Not eligible in this release semester.');
        return;
      }

      if (metaBlocked.has(scholarId)) {
        setRowDisabled(tr, 'Already scheduled for this batch & semester.');
        return;
      }

      setRowEnabled(tr);
    });
  }

  // ---------- selection counting
  function syncSelected(){
    const checked = Array.from(table.querySelectorAll('.pickScholar:checked'));
    selectedCountEl.textContent = checked.length;

    // Proceed requires: at least 1 selected + release chosen
    const okRelease = !!mRelease.value;
    proceedBtn.disabled = checked.length === 0 || !okRelease;
  }

  // Select all eligible visible
  checkAllEligible?.addEventListener('change', () => {
    const visibleEligibleRows = getRows().filter(tr =>
      tr.style.display !== 'none' && tr.getAttribute('data-selectable') === '1'
    );

    visibleEligibleRows.forEach(tr => {
      const cb = tr.querySelector('.pickScholar');
      if (cb) cb.checked = checkAllEligible.checked;
    });

    syncSelected();
  });

  // Checkbox changes (event delegation)
  table.addEventListener('change', (e) => {
    if (e.target.classList.contains('pickScholar')) {
      syncSelected();
    }
  });

  // ---------- AJAX: load releases when batch changes
  async function loadReleasesForBatch(batchId){
    mRelease.innerHTML = '<option value="">Loading…</option>';
    mRelease.disabled = true;
    metaLoaded = false;
    metaEligible = new Set();
    metaBlocked = new Set();

    if (!batchId) {
      mRelease.innerHTML = '<option value="">Select batch first…</option>';
      return;
    }

    try {
      const res = await fetch(`${urlReleasesByBatch}?batch_id=${encodeURIComponent(batchId)}`);
      const data = await res.json();

      if (!Array.isArray(data) || data.length === 0) {
        mRelease.innerHTML = '<option value="">No release schedules found</option>';
        return;
      }

      mRelease.innerHTML = '<option value="">Select release…</option>';
      data.forEach(r => {
        const opt = document.createElement('option');
        opt.value = r.id;
        opt.textContent = r.title;
        opt.dataset.semesterId = r.semester_id;
        opt.dataset.amount = r.amount;
        mRelease.appendChild(opt);
      });

      mRelease.disabled = false;

    } catch (err) {
      console.error(err);
      mRelease.innerHTML = '<option value="">Failed to load releases</option>';
    }
  }

  // ---------- AJAX: load eligible/blocked when release changes
  async function loadPickMeta(releaseId){
    metaLoaded = false;
    metaEligible = new Set();
    metaBlocked = new Set();

    if (!releaseId) {
      applyEligibilityAndBlock();
      syncSelected();
      return;
    }

    try {
      const res = await fetch(`${urlPickMeta}?release_id=${encodeURIComponent(releaseId)}`);
      const meta = await res.json();

      metaEligible = new Set((meta.eligible_ids || []).map(String));
      metaBlocked = new Set((meta.blocked_ids || []).map(String));
      metaLoaded = true;

      applyEligibilityAndBlock();
      syncSelected();

    } catch (err) {
      console.error(err);
      metaLoaded = false;
      applyEligibilityAndBlock();
      syncSelected();
    }
  }

  // ---------- events
  mScholarship.addEventListener('change', () => {
    filterBatchOptions();
    // Reset release if scholarship changes
    mRelease.innerHTML = '<option value="">Select batch first…</option>';
    mRelease.disabled = true;
    applyRowFilters();
  });

  mBatch.addEventListener('change', async () => {
    await loadReleasesForBatch(mBatch.value);
    applyRowFilters();
  });

  mRelease.addEventListener('change', async () => {
    await loadPickMeta(mRelease.value);
  });

  let tt=null;
  mQ.addEventListener('input', () => {
    clearTimeout(tt);
    tt=setTimeout(applyRowFilters, 200);
  });

  // ---------- Proceed to step 2
  proceedBtn.addEventListener('click', () => {
    const checked = Array.from(table.querySelectorAll('.pickScholar:checked'));
    const ids = checked.map(cb => cb.value);

    if (!mRelease.value) {
      alert('Please select a Release Schedule first.');
      return;
    }
    if (ids.length === 0) {
      alert('No scholars selected.');
      return;
    }

    // Step 2 hidden fields
    s2ScholarshipId.value = mScholarship.value || '';
    s2BatchId.value = mBatch.value || '';

    // release
    const selectedOpt = mRelease.selectedOptions[0];
    s2ReleaseHidden.value = mRelease.value;
    s2ReleaseTitle.value = selectedOpt ? selectedOpt.textContent : '';

    // count
    s2SelectedCount.textContent = ids.length;

    // build hidden inputs
    const inputsWrap = document.getElementById('selectedInputs');
    inputsWrap.innerHTML = '';
    ids.forEach(id => {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = 'scholar_ids[]';
      inp.value = id;
      inputsWrap.appendChild(inp);
    });

    // preview list
    const preview = document.getElementById('selectedPreviewList');
    preview.innerHTML = '';
    checked.slice(0, 10).forEach(cb => {
      const tr = cb.closest('tr');
      const lname = tr.children[2].textContent.trim();
      const fname = tr.children[3].textContent.trim();
      const li = document.createElement('li');
      li.textContent = `${lname}, ${fname}`;
      preview.appendChild(li);
    });

    if (checked.length > 10) {
      const li = document.createElement('li');
      li.className = 'text-muted';
      li.textContent = `+ ${checked.length - 10} more…`;
      preview.appendChild(li);
    }

    bootstrap.Modal.getOrCreateInstance(selectModalEl).hide();
    bootstrap.Modal.getOrCreateInstance(scheduleModalEl).show();
  });

  // ---------- init
  filterBatchOptions();
  applyRowFilters();

});
</script>


@endsection
