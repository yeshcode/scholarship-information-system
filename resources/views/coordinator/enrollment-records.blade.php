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

    .table-compact th, .table-compact td {
        padding: .45rem .6rem !important;
        font-size: .86rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .thead-bisu {
        background: #003366;
        color: #fff;
        font-size: .78rem;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .badge-pill {
        padding: .35rem .55rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 600;
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
    <div>
        <h2 class="page-title-blue">Enrollment Records</h2>
        <div class="subtext">
            View enrollment records. Changes from System Admin (e.g., dropped) reflect here automatically.
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <span class="badge bg-light text-dark border">
            Total: <strong>{{ $records->total() }}</strong>
        </span>

        @if($currentSemester)
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                Current: <strong>{{ $currentSemester->term }} {{ $currentSemester->academic_year }}</strong>
            </span>
        @endif
    </div>
</div>

{{-- Filters Card --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong class="text-secondary">Filters</strong>
        {{-- <small class="text-muted">Use filters to narrow records</small> --}}
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('coordinator.enrollment-records') }}">
            <div class="row g-3">

                {{-- Semester --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Semester / Academic Year</label>
                    <select name="semester_id" class="form-select form-select-sm">
                        <option value="">All Semesters</option>
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}"
                                {{ (string)request('semester_id') === (string)$s->id ? 'selected' : '' }}>
                                {{ $s->term ?? $s->semester_name ?? 'Semester' }} {{ $s->academic_year ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- College --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">College</label>
                    <select name="college_id" id="college_id" class="form-select form-select-sm">
                        <option value="">All Colleges</option>
                        @foreach($colleges as $c)
                            <option value="{{ $c->id }}"
                                {{ (string)request('college_id') === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->college_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Course (dependent) --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Course</label>
                    <select name="course_id" id="course_id" class="form-select form-select-sm">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}"
                                data-college-id="{{ $course->college_id }}"
                                {{ (string)request('course_id') === (string)$course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Auto-filters by selected college.</div>
                </div>

                {{-- Year Level --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Year Level</label>
                    <select name="year_level_id" class="form-select form-select-sm">
                        <option value="">All Year Levels</option>
                        @foreach($yearLevels as $yl)
                            <option value="{{ $yl->id }}"
                                {{ (string)request('year_level_id') === (string)$yl->id ? 'selected' : '' }}>
                                {{ $yl->year_level_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="enrolled"  {{ request('status') === 'enrolled' ? 'selected' : '' }}>ENROLLED</option>
                        <option value="dropped"   {{ request('status') === 'dropped' ? 'selected' : '' }}>DROPPED</option>
                        <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>GRADUATED</option>
                        <option value="not_enrolled" {{ request('status') === 'not_enrolled' ? 'selected' : '' }}>NOT ENROLLED</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold text-secondary mb-1">Search Student</label>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control form-control-sm"
                           placeholder="Search last name, first name, or student ID...">
                </div>

                {{-- Buttons --}}
                <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-bisu-primary btn-sm w-100">
                        Apply
                    </button>
                    <a href="{{ route('coordinator.enrollment-records') }}" class="btn btn-outline-secondary btn-sm w-100">
                        Clear
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Add button --}}
<div class="d-flex justify-content-end mb-3">
    <button type="button"
            class="btn btn-bisu-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
        Enroll Student
    </button>
</div>

{{-- Table --}}
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <strong class="text-secondary">Records</strong>
            <div class="small text-muted">
                Showing <strong>{{ $records->count() }}</strong> of <strong>{{ $records->total() }}</strong>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 table-compact">
            <thead class="thead-bisu">
                <tr>
                    <th class="text-start">Student ID</th>
                    <th class="text-start">Last Name</th>
                    <th class="text-start">First Name</th>
                    <th class="text-start">College</th>
                    <th class="text-start">Course</th>
                    <th class="text-start">Year Level</th>
                    <th class="text-start">Status</th>
                </tr>
            </thead>

            <tbody>
                @if(($recordsMode ?? 'enrollments') === 'users')
                    @forelse($records as $u)
                        <tr>
                            <td class="text-start">{{ $u->student_id ?? 'N/A' }}</td>
                            <td class="text-start">{{ $u->lastname ?? 'N/A' }}</td>
                            <td class="text-start">{{ $u->firstname ?? 'N/A' }}</td>
                            <td class="text-start">{{ $u->college->college_name ?? 'N/A' }}</td>
                            <td class="text-start">{{ $u->course->course_name ?? 'N/A' }}</td>
                            <td class="text-start">{{ $u->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td class="text-start">
                                <span class="badge badge-pill bg-secondary-subtle text-secondary">
                                    NOT ENROLLED
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No students found.</td>
                        </tr>
                    @endforelse
                @else
                    @forelse($records as $row)
                        @php
                            $status = $row->status ?? 'N/A';
                            $badge = 'bg-secondary-subtle text-secondary';
                            if ($status === 'enrolled') $badge = 'bg-success-subtle text-success';
                            elseif ($status === 'dropped') $badge = 'bg-danger-subtle text-danger';
                            elseif ($status === 'graduated') $badge = 'bg-primary-subtle text-primary';
                        @endphp

                        <tr>
                            <td class="text-start">{{ $row->user->student_id ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->user->lastname ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->user->firstname ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->user->college->college_name ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->user->course->course_name ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->user->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td class="text-start">
                                <span class="badge badge-pill {{ $badge }}">
                                    {{ strtoupper(str_replace('_',' ', $status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No enrollment records found.</td>
                        </tr>
                    @endforelse
                @endif
                </tbody>

        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $records->appends(request()->query())->links() }}
</div>

{{-- ========================================================= --}}
{{-- ✅ BOOTSTRAP MODAL --}}
{{-- ========================================================= --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <div>
                    <div class="fw-bold">Enroll / Promote Student</div>
                    <small class="opacity-75">
                        Search a student, see latest enrollment, then enroll to a target semester.
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Search form --}}
                <form method="GET" action="{{ route('coordinator.enrollment-records') }}" class="mb-3">
                    <input type="hidden" name="show_add" value="1">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-secondary mb-1">Search student</label>
                            <input type="text"
                                   name="modal_q"
                                   value="{{ request('modal_q') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Type last name, first name, or student ID...">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-bisu-primary btn-sm w-100" type="submit">Search</button>
                            <a class="btn btn-outline-secondary btn-sm w-100" href="{{ route('coordinator.enrollment-records') }}">Clear</a>
                        </div>
                    </div>
                </form>

                {{-- Enroll one --}}
                <form method="POST" action="{{ route('coordinator.enrollment-records.enroll-one') }}">
                    @csrf

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary mb-1">Target semester</label>
                            <select name="semester_id" class="form-select form-select-sm" required>
                                @foreach($semesters as $s)
                                    <option value="{{ $s->id }}"
                                        {{ (string)($currentSemester?->id) === (string)$s->id ? 'selected' : '' }}>
                                        {{ $s->term ?? $s->semester_name ?? 'Semester' }} {{ $s->academic_year ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Students already enrolled in current semester are disabled.</div>
                        </div>
                    </div>

                    <div class="table-responsive border rounded">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:80px;">Select</th>
                                    <th>Student</th>
                                    <th style="width:160px;">Student ID</th>
                                    <th style="width:260px;">Latest Enrollment</th>
                                    <th style="width:180px;">Eligibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($modalCandidates as $c)
                                    @php
                                        $latestSem = $c->latest_enrollment?->semester;
                                        $latestLabel = $latestSem
                                            ? (($latestSem->term ?? $latestSem->semester_name ?? 'Semester') . ' ' . ($latestSem->academic_year ?? ''))
                                            : 'No previous record';
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="radio"
                                                   name="user_id"
                                                   value="{{ $c->user->id }}"
                                                   {{ $c->already_in_current ? 'disabled' : '' }}>
                                        </td>
                                        <td>{{ $c->user->lastname }}, {{ $c->user->firstname }}</td>
                                        <td>{{ $c->user->student_id ?? 'N/A' }}</td>
                                        <td class="text-muted">{{ $latestLabel }}</td>
                                        <td>
                                            @if($c->already_in_current)
                                                <span class="badge bg-danger-subtle text-danger">Already enrolled</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success">Eligible</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            Use search above to find a student.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @error('user_id')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('semester_id')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Enroll Selected Student</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Dependent Course dropdown (Bootstrap version, same logic)
    document.addEventListener('DOMContentLoaded', function () {
        const collegeSelect = document.getElementById('college_id');
        const courseSelect = document.getElementById('course_id');
        if (!collegeSelect || !courseSelect) return;

        const allCourseOptions = Array.from(courseSelect.options);

        function filterCourses() {
            const selectedCollege = collegeSelect.value;
            const currentCourse = @json(request('course_id'));

            courseSelect.innerHTML = '';
            allCourseOptions.forEach(opt => {
                if (!opt.value) {
                    courseSelect.appendChild(opt.cloneNode(true));
                    return;
                }
                const optCollegeId = opt.getAttribute('data-college-id');
                if (!selectedCollege || optCollegeId === selectedCollege) {
                    courseSelect.appendChild(opt.cloneNode(true));
                }
            });

            if (currentCourse && Array.from(courseSelect.options).some(o => o.value === currentCourse)) {
                courseSelect.value = currentCourse;
            } else {
                courseSelect.value = '';
            }
        }

        collegeSelect.addEventListener('change', function () {
            filterCourses();
            courseSelect.value = '';
        });

        filterCourses();

        // Auto-open modal after search
        @if(request('show_add') === '1')
            const modal = new bootstrap.Modal(document.getElementById('addModal'));
            modal.show();
        @endif
    });
</script>

@endsection
