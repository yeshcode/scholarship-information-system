{{-- resources/views/super-admin/enroll-students.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.6rem;
        color: #003366;
        margin: 0;
    }

    .subtext {
        color: #6b7280;
        font-size: .9rem;
        margin-top: .25rem;
    }

    /* Compact table */
    .table-compact th,
    .table-compact td {
        padding: 0.35rem 0.45rem !important;
        font-size: 0.82rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-compact thead th {
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .thead-bisu {
        background-color: #003366;
        color: #fff;
    }

    .btn-bisu {
        background-color: #003366;
        border-color: #003366;
        color: #fff;
    }
    .btn-bisu:hover { opacity: .92; }

    /* Modal */
    /* Modal backdrop */
.confirm-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 12px; /* space around modal */
}

/* Modal container - fits screen */
.confirm-card {
    background: #fff;
    width: 100%;
    max-width: 1100px;      /* wide enough */
    height: calc(100vh - 24px); /* FIT SCREEN */
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column; /* header/body/footer stacked */
    box-shadow: 0 10px 35px rgba(0,0,0,.25);
}

/* Header stays visible */
.modal-header {
    background: #003366;
    color: #fff;
    padding: .9rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 0 0 auto;
}

/* Body scrolls if content is long */
.modal-body {
    padding: 1rem;
    overflow-y: auto;
    flex: 1 1 auto;
}

/* Footer always visible */
.modal-footer {
    padding: .9rem 1rem;
    background: #f9fafb;
    display: flex;
    gap: .5rem;
    justify-content: flex-end;
    border-top: 1px solid #e5e7eb;
    flex: 0 0 auto;
}

    .pill {
        display: inline-block;
        padding: .2rem .5rem;
        border-radius: 999px;
        font-size: .78rem;
        background: #eef2ff;
        color: #1f2937;
        border: 1px solid #e5e7eb;
    }
</style>

<div class="p-3 mx-auto" style="max-width: 1200px;">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="page-title-blue">Bulk Semester Update (Students)</h1>
            <div class="subtext">
                Select students, choose a target semester, then confirm the update.
            </div>
        </div>

        <div class="text-md-end">
            <div class="pill">
                Current Semester:
                <strong>
                    {{ $currentSemester?->term }} {{ $currentSemester?->academic_year }}
                </strong>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
    @endif



   {{-- FILTERS --}}
{{-- FILTERS / STEP 1 (Target semester only) --}}
<form method="GET" action="{{ route('admin.enrollments.enroll-students') }}" class="card shadow-sm mb-3 border-0">
    <div class="card-body">

        {{-- Step header --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <div class="fw-bold" style="color:#003366;">Step 1: Choose Target Semester</div>
                <div class="small text-muted">Select the semester where the students will be enrolled.</div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="pill">
                    Selected: <strong id="selected-count">0</strong>
                </span>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-selected">
                    Clear Selected
                </button>
            </div>
        </div>

        {{-- Mode tabs (UI only, keeps your existing mode param) --}}
        @php $mode = request('mode','promote'); @endphp
        <ul class="nav nav-pills gap-2 mb-3" style="--bs-nav-pills-link-active-bg:#003366;">
            <li class="nav-item">
                <button type="button"
                        class="nav-link {{ $mode==='promote' ? 'active' : '' }}"
                        onclick="setModeAndSubmit('promote')">
                    Promote / Returning
                </button>
            </li>
            <li class="nav-item">
                <button type="button"
                        class="nav-link {{ $mode==='new' ? 'active' : '' }}"
                        onclick="setModeAndSubmit('new')">
                    New Enrollment
                </button>
            </li>
        </ul>

        <input type="hidden" name="mode" id="mode-field" value="{{ $mode }}">

        <div class="row g-2 align-items-end">

            {{-- ✅ SOURCE SEMESTER ONLY IN PROMOTE MODE --}}
            @if($mode === 'promote')
            <div class="col-12 col-md-5">
                <label class="form-label mb-1">
                    Source Semester (from)
                    <span class="text-danger fw-bold">*</span>
                </label>

                <select name="source_semester_id"
                        class="form-select form-select-sm"
                        required
                        onchange="this.form.submit()">
                    <option value="">Select source semester</option>
                    @foreach($semesters as $s)
                        <option value="{{ $s->id }}"
                            {{ (string)request('source_semester_id') === (string)$s->id ? 'selected' : '' }}>
                            {{ $s->term }} {{ $s->academic_year }}
                        </option>
                    @endforeach
                </select>

                <div class="small text-muted">
                    Required for promotion.
                </div>
            </div>
            @endif


            {{-- ✅ TARGET SEMESTER (required) --}}
            <div class="col-12 {{ $mode === 'promote' ? 'col-md-5' : 'col-md-8' }}">
                <label class="form-label mb-1">
                    Target Semester (to)
                    <span class="text-danger fw-bold">*</span>
                </label>

                <select name="semester_id"
                        id="target-semester"
                        class="form-select form-select-sm"
                        required
                        onchange="this.form.submit()">
                    <option value="">Select target semester</option>
                    @foreach($semesters as $s)
                        <option value="{{ $s->id }}"
                            {{ (string)request('semester_id') === (string)$s->id ? 'selected' : '' }}>
                            {{ $s->term }} {{ $s->academic_year }}
                        </option>
                    @endforeach
                </select>

                <div class="small text-muted">
                    Students already enrolled in this semester will be automatically excluded.
                </div>
            </div>


            {{-- Buttons --}}
            <div class="col-12 {{ $mode === 'promote' ? 'col-md-2' : 'col-md-4' }} d-grid">
                <button class="btn btn-bisu btn-sm" type="submit">
                    Apply
                </button>
                <a class="btn btn-link btn-sm text-muted mt-1 p-0"
                   href="{{ route('admin.enrollments.enroll-students', ['mode' => $mode]) }}">
                    Reset
                </a>
            </div>
        </div>

        <div class="mt-3 small text-muted">
            <strong>Auto rules:</strong>
            If target is <em>1st Semester of a new academic year</em>, year level will be promoted.
            4th year students will be marked as <strong>Graduated</strong>.
        </div>

    </div>
</form>

<script>
    function setModeAndSubmit(mode){
        const modeField = document.getElementById('mode-field');
        if(modeField) modeField.value = mode;

        const form = modeField?.closest('form');
        if(!form) return;

        // ✅ When switching to New Enrollment: remove source_semester_id from query completely
        if(mode === 'new'){
            const source = form.querySelector('[name="source_semester_id"]');
            if(source) source.value = '';
        }
        form.submit();
    }
</script>



    {{-- TABLE (NO SCROLL, 20 ROWS PER PAGE) --}}
    <form id="selection-form">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-compact text-center mb-0">
                    <thead class="thead-bisu">
                        <tr>
                            <th style="width:38px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>College</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th style="width:110px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           class="user-checkbox"
                                           value="{{ $student->id }}">
                                </td>
                                <td>{{ $student->student_id ?? 'N/A' }}</td>
                                <td class="text-start">
                                    {{ $student->lastname }}, {{ $student->firstname }}
                                </td>
                                <td class="text-start">{{ $student->bisu_email }}</td>
                                <td>{{ $student->college->college_name ?? 'N/A' }}</td>
                                <td>{{ $student->course->course_name ?? 'N/A' }}</td>
                                <td>{{ $student->yearLevel->year_level_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $student->status ?? 'active' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted py-3">
                                    No students found.
                                    <div class="small">
                                        (If you selected a target semester, students already enrolled in that semester may be excluded.)
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing <strong>{{ $students->count() }}</strong> of <strong>{{ $students->total() }}</strong> students
            </div>
            <div>
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>

        {{-- ACTION BUTTON --}}
        <div class="sticky-actions mt-3 d-flex gap-2 justify-content-between align-items-center flex-wrap">
            <div class="small text-muted">
                Step 2: Select students from the table, then proceed to confirm.
            </div>
            <div class="d-flex gap-2">
                <button type="button" id="proceed-btn" class="btn btn-bisu btn-sm">
                    Proceed to Confirm Selected
                </button>

                <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="btn btn-secondary btn-sm">
                    Back to Enrollments
                </a>
            </div>
        </div>
    </form>

</div>

{{-- MODAL --}}
<div id="confirmation-modal" class="confirm-backdrop">
    <div class="confirm-card">
        <div class="modal-header">
            <div>
                <strong>Confirm Update</strong>
                <div class="small" style="opacity:.9;">
                    Target:
                    <span id="target-label">
                        {{ $targetSemester?->term }} {{ $targetSemester?->academic_year }}
                    </span>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-light" id="cancel-x">✕</button>
        </div>

        <div class="modal-body">
            <div class="alert alert-info py-2 small mb-3">
                This will update selected students to the <strong>target semester</strong>.
                Year level promotion and graduation will be handled automatically based on your rules.
            </div>

            {{-- PREVIEW TABLE --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-compact mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:120px;">Student ID</th>
                            <th>Name</th>
                            <th style="width:240px;">College</th>
                            <th style="width:240px;">Course</th>
                            <th style="width:140px;">Year Level</th>
                        </tr>
                    </thead>
                    <tbody id="selected-preview-body">
                        {{-- injected by JS --}}
                    </tbody>
                </table>
            </div>

            {{-- CONFIRM FORM --}}
            <form method="POST"
                  action="{{ route('admin.enrollments.store-enroll-students') }}"
                  id="confirm-form"
                  class="mt-3">
                @csrf

                {{-- IMPORTANT: send these always --}}
                <input type="hidden" name="mode" value="{{ request('mode','promote') }}">
                <input type="hidden" name="source_semester_id" value="{{ request('source_semester_id') }}">
                <input type="hidden" name="semester_id" value="{{ request('semester_id') }}">

                {{-- selected_users[] injected by JS --}}
                <div id="selected-hidden-inputs"></div>
            </form>

            <div class="small text-muted mt-2">
                If “Confirm Update” is disabled, make sure you selected a Target Semester in the filter.
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" id="cancel-btn">Cancel</button>

            {{-- ✅ This is the REAL submit button --}}
            <button type="submit"
                    form="confirm-form"
                    class="btn btn-success btn-sm"
                    id="confirm-btn">
                Confirm Update
            </button>
        </div>
    </div>
</div>


<script>
    // ====== Persistent multi-page selection (localStorage) ======
    const STORAGE_KEY = 'enroll_selected_users_v1';

    function getStoredSelected() {
        try {
            return new Set(JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'));
        } catch (e) {
            return new Set();
        }
    }

    function saveStoredSelected(set) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(set)));
    }

    function updateSelectedCount() {
        const set = getStoredSelected();
        const countEl = document.getElementById('selected-count');
        if (countEl) countEl.textContent = set.size;

        const hint = document.getElementById('selected-hint');
        if (hint) hint.style.display = set.size > 0 ? 'inline' : 'none';
    }

    // Restore checkbox states on page load
    function restoreCheckboxes() {
        const set = getStoredSelected();
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = set.has(String(cb.value));
        });

        // Select-all should reflect visible state
        const allVisible = document.querySelectorAll('.user-checkbox').length;
        const checkedVisible = document.querySelectorAll('.user-checkbox:checked').length;
        const selectAll = document.getElementById('select-all');
        if (selectAll) selectAll.checked = allVisible > 0 && allVisible === checkedVisible;

        updateSelectedCount();
    }

    // When a single checkbox changes, store it
    function bindCheckboxEvents() {
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const set = getStoredSelected();
                if (this.checked) set.add(String(this.value));
                else set.delete(String(this.value));
                saveStoredSelected(set);

                // Update select-all for visible page
                const allVisible = document.querySelectorAll('.user-checkbox').length;
                const checkedVisible = document.querySelectorAll('.user-checkbox:checked').length;
                const selectAll = document.getElementById('select-all');
                if (selectAll) selectAll.checked = allVisible > 0 && allVisible === checkedVisible;

                updateSelectedCount();
            });
        });
    }

    // Select-all affects only visible rows, but should also update storage
    const selectAll = document.getElementById('select-all');
    selectAll?.addEventListener('change', function () {
        const set = getStoredSelected();
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = this.checked;
            if (this.checked) set.add(String(cb.value));
            else set.delete(String(cb.value));
        });
        saveStoredSelected(set);
        updateSelectedCount();
    });

    // Clear selected
    document.getElementById('clear-selected')?.addEventListener('click', function () {
        localStorage.removeItem(STORAGE_KEY);
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
        if (selectAll) selectAll.checked = false;
        updateSelectedCount();
    });

    // ====== Your modal logic (updated to use stored selection) ======
    const modal = document.getElementById('confirmation-modal');
    const proceedBtn = document.getElementById('proceed-btn');
    const confirmBtn = document.getElementById('confirm-btn');

    function openModal() { modal.style.display = 'flex'; }
    function closeModal() { modal.style.display = 'none'; }

    document.getElementById('cancel-btn')?.addEventListener('click', closeModal);
    document.getElementById('cancel-x')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function(e){ if(e.target === modal) closeModal(); });

    proceedBtn?.addEventListener('click', function () {
        const targetSemesterId = document.getElementById('target-semester')?.value;

        if (!targetSemesterId) {
            alert('Please select a Target Semester first.');
            return;
        }

        const stored = getStoredSelected();
        if (stored.size === 0) {
            alert('Please select at least one student (you can select across pages).');
            return;
        }

        // Set hidden semester_id before submit
        document.querySelector('#confirm-form input[name="semester_id"]').value = targetSemesterId;

        const previewBody = document.getElementById('selected-preview-body');
        const hiddenWrap = document.getElementById('selected-hidden-inputs');

        previewBody.innerHTML = '';
        hiddenWrap.innerHTML = '';

        // Build preview using what’s available on this page (for names),
        // and still submit ALL selected IDs across pages.
        const currentPageMap = new Map();
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            const row = cb.closest('tr');
            if (!row) return;
            const studentIdText = row.children[1]?.textContent?.trim() || '';
            const name = row.children[2]?.textContent?.trim() || '';
            const college = row.children[4]?.textContent?.trim() || '';
            const course = row.children[5]?.textContent?.trim() || '';
            const yearLevel = row.children[6]?.textContent?.trim() || '';

            currentPageMap.set(String(cb.value), { studentIdText, name, college, course, yearLevel });
        });

        // Show preview for current page selected only (optional),
        // but submit ALL IDs stored.
        stored.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_users[]';
            input.value = id;
            hiddenWrap.appendChild(input);

            // Preview only if row data is available on this page
            const info = currentPageMap.get(id);
            if (info) {
                previewBody.innerHTML += `
                    <tr>
                        <td>${info.studentIdText}</td>
                        <td>${info.name}</td>
                        <td>${info.college}</td>
                        <td>${info.course}</td>
                        <td>${info.yearLevel}</td>
                    </tr>
                `;
            }
        });

        // If none of selected are in this page, still show a note
        if (previewBody.innerHTML.trim() === '') {
            previewBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-muted small text-center py-3">
                        You selected <strong>${stored.size}</strong> student(s).
                        Preview shows only the students visible on the current page.
                    </td>
                </tr>
            `;
        }

        if (confirmBtn) confirmBtn.disabled = false;
        openModal();
    });

    // Restore selection when page loads
    restoreCheckboxes();
    bindCheckboxEvents();
    updateSelectedCount();
</script>

@endsection
