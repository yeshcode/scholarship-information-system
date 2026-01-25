{{-- resources/views/super-admin/enrollments.blade.php --}}
@php $fullWidth = true; @endphp
@extends('layouts.app')

@section('content')

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.7rem;
        color: #003366;
        margin: 0;
    }
    .subtext { color: #6b7280; font-size: .9rem; }

    .table-card {
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    /* ✅ compact rows */
    .modern-table th,
    .modern-table td {
        border: 1px solid #e5e7eb;
        padding: 6px 8px !important;
        font-size: 0.82rem;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    .modern-table thead {
        background-color: #003366;
        color: #ffffff;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .modern-table tbody tr:nth-child(even) { background-color: #f9fafb; }
    .modern-table tbody tr:hover { background-color: #e8f1ff; transition: 0.15s ease-in-out; }

    .btn-bisu-primary {
        background-color: #003366;
        color: #ffffff;
        border: 1px solid #003366;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-primary:hover { background-color: #002244; border-color: #002244; color: #ffffff; }

    .btn-bisu-secondary {
        background-color: #6f42c1;
        color: #ffffff;
        border: 1px solid #6f42c1;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-secondary:hover { background-color: #59339b; border-color: #59339b; color: #ffffff; }

    .badge-status { font-size: 0.75rem; padding: 4px 8px; border-radius: 999px; }
</style>

<div class="container-fluid py-3">

    {{-- TITLE --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <h2 class="page-title-blue">Manage Enrollments</h2>
            <div class="subtext">Shows students and their status for the selected semester.</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-bisu-primary shadow-sm">
                + Add Enrollment
            </a>
            <a href="{{ route('admin.enrollments.enroll-students') }}" class="btn btn-bisu-secondary shadow-sm">
                📚 Enroll Students
            </a>
            <a href="{{ route('admin.enrollments.records') }}" class="btn btn-outline-secondary shadow-sm">
                📂 Records
            </a>
        </div>
    </div>

    {{-- FLASH --}}
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

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
        <input type="hidden" name="page" value="enrollments">

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Semester</label>
                <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($semesters ?? [] as $semester)
                        <option value="{{ $semester->id }}"
                            {{ (string)request('semester_id', $selectedSemesterId ?? '') === (string)$semester->id ? 'selected' : '' }}>
                            {{ $semester->term }} {{ $semester->academic_year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">College</label>
                <select name="college_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Colleges</option>
                    @foreach($colleges ?? [] as $college)
                        <option value="{{ $college->id }}" {{ request('college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->college_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Course</label>
                <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold text-secondary">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach($statuses ?? [] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ strtoupper(str_replace('_',' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>

                <div class="col-12">
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-semibold text-secondary">Search Student</label>
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control form-control-sm"
                                placeholder="Search last name, first name, student ID...">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-sm btn-bisu-primary w-100" type="submit">Search</button>
                        </div>
                    </div>
                </div>
        </div>

        @if(request('college_id') || request('course_id') || request('status'))
            <div class="mt-3">
                <a href="{{ route('admin.dashboard', ['page' => 'enrollments', 'semester_id' => $selectedSemesterId]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    ✖ Clear Filters
                </a>
            </div>
        @endif
    </form>

    {{-- TABLE --}}
    <div class="table-card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Semester</th>
                        <th>College</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th style="width:110px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($studentsForEnrollmentList ?? [] as $row)
                        @php
                            // Derived status:
                            $status = $row->enrollment_status ?? 'not_enrolled';

                            $badge = 'bg-secondary';
                            if ($status === 'enrolled') $badge = 'bg-success';
                            elseif ($status === 'dropped') $badge = 'bg-danger';
                            elseif ($status === 'graduated') $badge = 'bg-primary';
                            elseif ($status === 'not_enrolled') $badge = 'bg-secondary';
                        @endphp

                        <tr>
                            <td>{{ $row->student_id ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->lastname ?? 'N/A' }}</td>
                            <td class="text-start">{{ $row->firstname ?? 'N/A' }}</td>
                            <td>
                                {{ $row->sem_term ?? 'N/A' }}
                                {{ $row->sem_academic_year ?? '' }}
                            </td>
                            <td>{{ $row->college->college_name ?? 'N/A' }}</td>
                            <td>{{ $row->course->course_name ?? 'N/A' }}</td>
                            <td>{{ $row->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-status {{ $badge }}">
                                    {{ strtoupper(str_replace('_',' ', $status)) }}
                                </span>
                            </td>

                            <td>
                                @if(!empty($row->enrollment_id))
                                    <a href="{{ route('admin.enrollments.edit', $row->enrollment_id) }}"
                                    class="btn btn-sm btn-warning">
                                        Update
                                    </a>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4 text-center">
                                No students found for this filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($studentsForEnrollmentList))
        <div class="mt-4 d-flex justify-content-center">
            {{ $studentsForEnrollmentList->appends(request()->except('enrollments_page'))->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection
