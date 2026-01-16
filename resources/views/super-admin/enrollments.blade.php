{{-- resources/views/super-admin/enrollments.blade.php --}}
@php $fullWidth = true; @endphp
@extends('layouts.app')

@section('content')

<style>
    .page-title-blue {
        font-weight: 700;
        font-size: 1.9rem;
        color: #003366;
    }

    .table-card {
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .modern-table thead {
        background-color: #003366;
        color: #ffffff;
    }

    .modern-table th,
    .modern-table td {
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        font-size: 0.9rem;
        vertical-align: middle;
        text-align: center;
    }

    .modern-table tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .modern-table tbody tr:hover {
        background-color: #e8f1ff;
        transition: 0.15s ease-in-out;
    }

    .btn-bisu-primary {
        background-color: #003366;
        color: #ffffff;
        border: 1px solid #003366;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-primary:hover {
        background-color: #002244;
        border-color: #002244;
        color: #ffffff;
    }

    .btn-bisu-secondary {
        background-color: #6f42c1;
        color: #ffffff;
        border: 1px solid #6f42c1;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
    }
    .btn-bisu-secondary:hover {
        background-color: #59339b;
        border-color: #59339b;
        color: #ffffff;
    }

    .btn-bisu-outline-primary {
        color: #003366;
        border: 1px solid #003366;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .btn-bisu-outline-primary:hover {
        background-color: #003366;
        color: #ffffff;
    }

    .btn-bisu-outline-danger {
        color: #b30000;
        border: 1px solid #b30000;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .btn-bisu-outline-danger:hover {
        background-color: #b30000;
        color: #ffffff;
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 999px;
    }
</style>

<div class="container-fluid py-3">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title-blue mb-0">
            Manage Enrollments
        </h2>
    </div>

    {{-- FLASH MESSAGES --}}
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

    {{-- ACTION BUTTONS --}}
<div class="d-flex justify-content-end mb-3 gap-2">
    <a href="{{ route('admin.enrollments.create') }}"
       class="btn btn-bisu-primary shadow-sm">
        + Add Enrollment
    </a>

    <a href="{{ route('admin.enrollments.enroll-students') }}"
       class="btn btn-bisu-secondary shadow-sm">
        📚 Enroll Students
    </a>

    {{-- NEW: Records button --}}
    <a href="{{ route('admin.enrollments.records') }}"
       class="btn btn-outline-secondary shadow-sm">
        📂 Records
    </a>
</div>


    {{-- FILTERS --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
        <input type="hidden" name="page" value="enrollments">

        <div class="row g-3">
            <div class="col-md-4">
                <label for="semester_id" class="form-label mb-1 fw-semibold text-secondary">
                    Semester
                </label>
                <select name="semester_id" id="semester_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    @foreach($semesters ?? [] as $semester)
                        <option value="{{ $semester->id }}"
                            {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->term }} {{ $semester->academic_year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="course_id" class="form-label mb-1 fw-semibold text-secondary">
                    Course
                </label>
                <select name="course_id" id="course_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}"
                            {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label mb-1 fw-semibold text-secondary">
                    Status
                </label>
                <select name="status" id="status"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($statuses ?? [] as $status)
                        <option value="{{ $status }}"
                            {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(request('semester_id') || request('course_id') || request('status'))
            <div class="mt-3">
                <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}"
                   class="btn btn-sm btn-outline-secondary">
                    ✖ Clear Filters
                </a>
            </div>
        @endif
    </form>

    {{-- TABLE CARD --}}
    <div class="table-card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead class="sticky-top">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($enrollments ?? [] as $enrollment)
                        <tr>
                            <td>{{ $enrollment->user->lastname ?? 'N/A' }}</td>
                            <td>{{ $enrollment->user->firstname ?? 'N/A' }}</td>
                            <td>{{ $enrollment->user->middlename ?? 'N/A' }}</td>
                            <td>
                                {{ $enrollment->semester->term ?? 'N/A' }}
                                {{ $enrollment->semester->academic_year ?? '' }}
                            </td>
                            <td>
                                {{ $enrollment->section->section_name ?? 'N/A' }}
                                ({{ $enrollment->section->course->course_name ?? '' }})
                            </td>
                            <td>{{ $enrollment->course->course_name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $status = strtolower($enrollment->status ?? '');
                                    $badgeClass = 'bg-secondary';
                                    if ($status === 'active')  $badgeClass = 'bg-success';
                                    elseif ($status === 'inactive') $badgeClass = 'bg-danger';
                                    elseif ($status === 'pending')  $badgeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge badge-status {{ $badgeClass }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.enrollments.edit', $enrollment->id) }}"
                                   class="btn btn-bisu-outline-primary btn-sm me-1">
                                    ✏️ Edit
                                </a>
                                <a href="{{ route('admin.enrollments.delete', $enrollment->id) }}"
                                   class="btn btn-bisu-outline-danger btn-sm">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">
                                No enrollments found.
                                <a href="{{ route('admin.enrollments.create') }}" class="text-primary fw-semibold">
                                    Add one now
                                </a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($enrollments))
        <div class="mt-4 d-flex justify-content-center">
            {{ $enrollments->appends(request()->except('enrollments_page'))->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection
