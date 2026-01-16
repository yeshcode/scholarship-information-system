{{-- resources/views/super-admin/enrollment-records.blade.php --}}
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
</style>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title-blue mb-0">
            Enrollment Records by Academic Year
        </h2>
    </div>

    <a href="{{ route('admin.enrollments') }}" class="btn btn-bisu-outline-primary mb-3">
        ← Back to Manage Enrollments
    </a>

    @if($academicYears->isEmpty())
        <div class="alert alert-info">
            No academic years found yet. Please add semesters first.
        </div>
    @else
        <div class="table-card shadow-sm">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($academicYears as $year)
                            <tr>
                                <td>{{ $year }}</td>
                                <td>
                                    <a href="{{ route('admin.enrollments.records.year', $year) }}"
                                       class="btn btn-bisu-primary btn-sm">
                                        View Records
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@endsection
