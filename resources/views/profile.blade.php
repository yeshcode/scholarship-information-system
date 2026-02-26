@extends('layouts.app')

@section('content')
@php
    // Theme (same as your system)
    $theme = '#003366';
    $soft  = '#eaf2ff';
    $bg    = '#f4f7fb';
    $line  = '#e5e7eb';

    $fullName = trim(($user->firstname ?? '').' '.($user->lastname ?? ''));
    $initials = strtoupper(substr($user->firstname ?? 'U', 0, 1) . substr($user->lastname ?? 'S', 0, 1));
    $enrolledStatus = \App\Models\Enrollment::STATUS_ENROLLED;

    // Defaults (student-only values)
    $collegeName = 'N/A';
    $courseName  = 'N/A';
    $yearLevel   = 'N/A';
    $scholarshipName = '';
    $batchNumber = '';

    if ($isStudent && !$isAdminLike) {
        $courseName  = $activeEnrollment?->course?->course_name ?? 'N/A';
        $collegeName = $activeEnrollment?->course?->college?->college_name ?? 'N/A';
        $yearLevel   = $user->yearLevel?->year_level_name ?? 'N/A';

        $scholarshipName = $scholarRecord?->scholarship?->scholarship_name ?? '';
        $batchNumber     = $scholarRecord?->batch_number ?? '';
    }

    $isScholar = ($isStudent && !$isAdminLike && !is_null($scholarRecord));
@endphp

<style>
    body{ background: {{ $bg }}; }

    .profile-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }
    .card-soft{
        border: 1px solid {{ $line }};
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(0,0,0,.06);
        overflow: hidden;
        background: #fff;
    }
    .profile-hero{
        background: linear-gradient(135deg, {{ $theme }} 0%, #0b3d8f 55%, #1b5fbf 100%);
        color: #fff;
    }
    .avatar{
        width: 72px; height: 72px;
        border-radius: 20px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.22);
        display:flex; align-items:center; justify-content:center;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: .04em;
    }
    .pill{
        display:inline-flex;
        align-items:center;
        padding: .35rem .65rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,.22);
        background: rgba(255,255,255,.12);
        color:#fff;
        gap:.35rem;
    }
    .pill-light{
        border: 1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: {{ $theme }};
    }
    .label{
        font-size: .78rem;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: .35rem;
    }
    .value-box{
        background: #f9fbff;
        border: 1px solid {{ $line }};
        border-radius: 12px;
        padding: .65rem .8rem;
        min-height: 44px;
        display:flex;
        align-items:center;
        color: #111827;
        font-weight: 600;
        font-size: .95rem;
    }
    .value-box.muted{ color:#6b7280; font-weight:600; }
    .nav-pills .nav-link{
        font-weight: 800;
        color: {{ $theme }};
        border-radius: 12px;
    }
    .nav-pills .nav-link.active{
        background: {{ $theme }};
        color:#fff;
    }
    .btn-brand{
        background: {{ $theme }};
        color:#fff;
        font-weight: 800;
        border-radius: 12px;
        padding: .55rem 1rem;
    }
    .btn-brand:hover{ opacity: .92; color:#fff; }
    .table thead th{
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
        border-bottom: 1px solid {{ $line }};
    }
</style>

<div class="container py-4 profile-wrap">
    <div class="mx-auto" style="max-width: 1050px;">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <strong>There were some issues:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- HERO HEADER --}}
        <div class="card-soft profile-hero mb-4">
            <div class="p-4 p-md-5">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar">{{ $initials }}</div>

                        <div>
                            <div class="fw-bold" style="font-size:1.25rem; line-height:1.2;">
                                {{ $fullName ?: 'N/A' }}
                            </div>
                            <div style="opacity:.9; font-size:.92rem;">
                                {{ $user->bisu_email ?? $user->email ?? 'N/A' }}
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <span class="pill">
                                    {{ $user->userType->name ?? 'User' }}
                                </span>

                                @if($isStudent && !$isAdminLike)
                                    <span class="pill">
                                        Semester: {{ $semesterLabel ?? 'N/A' }}
                                    </span>
                                @endif

                                @if($isScholar)
                                    <span class="pill" style="background: rgba(34,197,94,.20); border-color: rgba(34,197,94,.25);">
                                        Scholar
                                    </span>
                                @else
                                    @if($isStudent && !$isAdminLike)
                                        <span class="pill" style="background: rgba(255,255,255,.10);">
                                            Non-Scholar
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-start text-md-end" style="max-width: 320px;">
                        <div class="fw-bold" style="font-size:1rem;">My Profile</div>
                        <div style="opacity:.9; font-size:.9rem;">
                            @if($isStudent && !$isAdminLike)
                                Your academic information is updated from your enrollment record.
                            @else
                                Account overview and security settings.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="card-soft">
            <div class="p-3 p-md-4">

                {{-- Tabs --}}
                <ul class="nav nav-pills gap-2 mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabOverview" type="button">
                            Overview
                        </button>
                    </li>

                    @if($isStudent && !$isAdminLike)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabAcademic" type="button">
                                Academic
                            </button>
                        </li>
                    @endif

                    @if($isStudent && !$isAdminLike)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabContact" type="button">
                                Contact
                            </button>
                        </li>
                    @endif

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabSecurity" type="button">
                            Security
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- ================= OVERVIEW ================= --}}
                    <div class="tab-pane fade show active" id="tabOverview">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0" style="background:#fff;">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="fw-bold" style="color:{{ $theme }};">Account Information</div>
                                            <span class="pill-light">Read-only</span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="label">Complete Name</div>
                                                <div class="value-box">{{ $fullName ?: 'N/A' }}</div>
                                            </div>

                                            @if($isStudent && !$isAdminLike)
                                                <div class="col-md-6">
                                                    <div class="label">Student ID</div>
                                                    <div class="value-box">{{ $user->student_id ?? 'N/A' }}</div>
                                                </div>
                                            @endif

                                            <div class="col-md-6">
                                                <div class="label">Role</div>
                                                <div class="value-box">{{ $user->userType->name ?? 'N/A' }}</div>
                                            </div>

                                            <div class="col-12">
                                                <div class="label">Email</div>
                                                <div class="value-box">{{ $user->bisu_email ?? $user->email ?? 'N/A' }}</div>
                                            </div>

                                            @if($isStudent && !$isAdminLike)
                                                <div class="col-12">
                                                    <div class="label">Year Level</div>
                                                    <div class="value-box">{{ $yearLevel }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Quick summary card --}}
                            <div class="col-lg-6">
                                <div class="card border-0" style="background:#fff;">
                                    <div class="card-body p-0">
                                        <div class="fw-bold mb-3" style="color:{{ $theme }};">Quick Summary</div>

                                        <div class="row g-3">
                                            @if($isStudent && !$isAdminLike)
                                                <div class="col-12">
                                                    <div class="label">Current Semester</div>
                                                    <div class="value-box">{{ $semesterLabel ?? 'N/A' }}</div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">College</div>
                                                    <div class="value-box">{{ $collegeName }}</div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">Course</div>
                                                    <div class="value-box" style="white-space:normal;">{{ $courseName }}</div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="label">Scholar Status</div>
                                                    <div class="value-box {{ $isScholar ? '' : 'muted' }}">
                                                        {{ $isScholar ? 'Scholar' : 'Non-Scholar' }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-12">
                                                    <div class="value-box muted">
                                                        No academic profile is shown for this role.
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= ACADEMIC ================= --}}
                    @if($isStudent && !$isAdminLike)
                    <div class="tab-pane fade" id="tabAcademic">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="fw-bold mb-3" style="color:{{ $theme }};">Current Academic Details</div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="label">Current Semester</div>
                                        <div class="value-box">{{ $semesterLabel ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">College</div>
                                        <div class="value-box">{{ $collegeName }}</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">Course</div>
                                        <div class="value-box" style="white-space:normal;">{{ $courseName }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="label">Year Level</div>
                                        <div class="value-box">{{ $yearLevel }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="label">Status</div>
                                        <div class="value-box {{ $isScholar ? '' : 'muted' }}">
                                            {{ $isScholar ? 'Scholar' : 'Non-Scholar' }}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="label">Scholarship</div>
                                            @if($isScholar)
                                                <div class="value-box" style="background:#ecfdf5; border:1px solid #bbf7d0;">
                                                    <div>
                                                        <div class="fw-bold text-success">
                                                            {{ $scholarshipName }}
                                                        </div>
                                                        @if(!empty($batchNumber))
                                                            <div class="small text-muted">
                                                                Batch: {{ $batchNumber }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="value-box muted">
                                                    No active scholarship
                                                </div>
                                            @endif
                                    </div>

                                    @if(!empty($batchNumber))
                                        <div class="col-12">
                                            <div class="label">Batch Number</div>
                                            <div class="value-box">{{ $batchNumber }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="fw-bold" style="color:{{ $theme }};">Enrollment History</div>
                                    {{-- <span class="pill-light">{{ $enrollmentHistory->count() }} record(s)</span> --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Semester</th>
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($enrollmentHistory as $enr)
                                            @php
                                                $sem = $enr->semester
                                                    ? ($enr->semester->term . ' ' . $enr->semester->academic_year)
                                                    : 'N/A';
                                                $crs = $enr->course?->course_name ?? 'N/A';
                                                $st  = $enr->status ?? 'N/A';
                                                $isActive = $activeEnrollment && $enr->id === $activeEnrollment->id;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $sem }}</div>
                                                    @if($isActive)
                                                        <div class="small text-success">Current</div>
                                                    @endif
                                                </td>
                                                <td style="white-space:normal;">{{ $crs }}</td>
                                                <td>
                                                    <span class="badge {{ strtolower($st) === 'enrolled' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ strtoupper($st) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-muted small py-3">
                                                    No enrollment history available.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- <div class="text-muted small mt-2">
                                    Academic info is pulled automatically from your enrollment records.
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ================= CONTACT ================= --}}
                    @if($isStudent && !$isAdminLike)
                    <div class="tab-pane fade" id="tabContact">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="fw-bold mb-3" style="color:{{ $theme }};">Contact Information</div>

                                <form action="{{ route('profile.update-contact') }}" method="POST" class="card border-0" style="background:#fff;">
                                    @csrf
                                    <div class="card-body p-0">

                                        <div class="mb-3">
                                            <div class="label">Contact Number</div>
                                            <input type="text"
                                                name="contact_no"
                                                class="form-control"
                                                value="{{ old('contact_no', $user->contact_no ?? '') }}"
                                                placeholder="Enter contact number">

                                            @error('contact_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button class="btn btn-brand">
                                            Save Changes
                                        </button>

                                        <div class="text-muted small mt-2">
                                            This information is visible for contact and coordination purposes.
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <div class="fw-bold mb-3" style="color:{{ $theme }};">Current Contact</div>
                                <div class="value-box {{ $user->contact_no ? '' : 'muted' }}">
                                    {{ $user->contact_no ?: 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ================= SECURITY ================= --}}
                    <div class="tab-pane fade" id="tabSecurity">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div class="fw-bold mb-3" style="color:{{ $theme }};">Change Password</div>

                                <form action="{{ route('profile.update-password') }}" method="POST" class="card border-0" style="background:#fff;">
                                    @csrf
                                    <div class="card-body p-0">

                                        <div class="mb-3">
                                            <div class="label">Current Password</div>
                                            <input type="password" name="current_password" class="form-control" required>
                                            @error('current_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="label">New Password</div>
                                            <input type="password" name="password" class="form-control" required>
                                            <div class="text-muted small mt-1">Minimum 8 characters, with letters & numbers.</div>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="label">Confirm New Password</div>
                                            <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>

                                        <button class="btn btn-brand">
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-5">
                                <div class="fw-bold mb-3" style="color:{{ $theme }};">Security Tips</div>
                                <div class="value-box muted" style="white-space:normal; align-items:flex-start;">
                                    Use a password you don’t reuse elsewhere, and avoid sharing it with anyone.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- tab-content --}}

            </div>
        </div>

    </div>
</div>
@endsection