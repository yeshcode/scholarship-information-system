{{-- resources/views/super-admin/enrollment-records-year.blade.php --}}
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
    .badge-status {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 999px;
    }
</style>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-1">
                Enrollment Records – AY {{ $academicYear }}
            </h2>
            <p class="text-muted mb-0">
                Showing all students enrolled in any semester of Academic Year {{ $academicYear }}.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.enrollments.records') }}" class="btn btn-bisu-outline-primary">
                ← Back to Academic Years
            </a>
            <a href="{{ route('admin.enrollments') }}" class="btn btn-outline-secondary">
                Back to Manage Enrollments
            </a>
        </div>
    </div>

    <div class="table-card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Course</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
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

                                    if ($status === 'enrolled')      $badgeClass = 'bg-success';
                                    elseif ($status === 'graduated') $badgeClass = 'bg-primary';
                                    elseif ($status === 'not_enrolled') $badgeClass = 'bg-danger';
                                @endphp
                                <span class="badge badge-status {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">
                                No enrollment records found for AY {{ $academicYear }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $enrollments->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
