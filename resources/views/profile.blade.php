@extends('layouts.app')

@section('content')
@php
    // Theme
    $theme = '#003366';
    $soft  = '#e3f2fd';

    $fullName = trim(($user->firstname ?? '').' '.($user->lastname ?? ''));
    $initials = strtoupper(substr($user->firstname ?? 'U', 0, 1) . substr($user->lastname ?? 'S', 0, 1));

    // Safe defaults
    $collegeName = 'N/A';
    $courseName  = 'N/A';
    $yearLevel   = 'N/A';
    $scholarshipName = '';
    $batchNumber = '';

    if ($isStudent && !$isAdminLike) {
        // Enrollment is source of truth for Course + Semester (real-time)
        $courseName  = $activeEnrollment?->course?->course_name ?? 'N/A';

        // College derived from course->college (make sure Course has college() relationship)
        $collegeName = $activeEnrollment?->course?->college?->college_name ?? 'N/A';

        // Year level from users table (because Enrollment model currently has no year_level_id)
        $yearLevel   = $user->yearLevel?->year_level_name ?? 'N/A';

        // Scholar info (blank if none)
        $scholarshipName = $scholarRecord?->scholarship?->name ?? '';
        $batchNumber     = $scholarRecord?->batch_number ?? '';
    }
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-center">
        <div class="w-100" style="max-width: 1050px;">

            {{-- HEADER --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width:64px;height:64px;background:{{ $soft }};color:{{ $theme }};font-weight:800;font-size:1.4rem;">
                                {{ $initials }}
                            </div>

                            <div>
                                <div class="fw-bold" style="color:{{ $theme }}; font-size:1.25rem;">
                                    {{ $fullName ?: 'N/A' }}
                                </div>
                                <div class="text-muted small">{{ $user->bisu_email ?? 'N/A' }}</div>

                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill" style="background:{{ $theme }};">
                                        {{ $user->userType->name ?? 'User' }}
                                    </span>

                                    @if($isStudent && !$isAdminLike && $scholarRecord)
                                        <span class="badge rounded-pill bg-success">Scholar</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="fw-bold" style="color:{{ $theme }};">My Profile</div>
                            <div class="text-muted small">
                                @if($isStudent && !$isAdminLike)
                                    Academic information is managed by the administration.
                                @else
                                    Account information overview.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERTS --}}
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

            <div class="row g-4">

                {{-- LEFT: DETAILS (READ-ONLY) --}}
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="color:{{ $theme }};">
                                    {{ ($isStudent && !$isAdminLike) ? 'Account & Academic Details' : 'Account Details' }}
                                </h5>
                                <span class="badge rounded-pill" style="background:{{ $soft }}; color:{{ $theme }};">
                                    Read-only
                                </span>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="text-muted small fw-semibold">Complete Name</label>
                                    <input class="form-control" value="{{ $fullName ?: 'N/A' }}" disabled>
                                </div>

                                {{-- Show Student ID only if student --}}
                                @if($isStudent && !$isAdminLike)
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Student ID</label>
                                        <input class="form-control" value="{{ $user->student_id ?? 'N/A' }}" disabled>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="text-muted small fw-semibold">Role</label>
                                    <input class="form-control" value="{{ $user->userType->name ?? 'N/A' }}" disabled>
                                </div>

                                {{-- STUDENT ONLY: Academic fields --}}
                                @if($isStudent && !$isAdminLike)

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">Current Semester</label>
                                        <input class="form-control" value="{{ $semesterLabel ?? 'N/A' }}" disabled>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">College</label>
                                        <input class="form-control" value="{{ $collegeName }}" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Course</label>
                                        <div class="form-control bg-light" style="white-space: normal; height:auto;">
                                            {{ $courseName }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-muted small fw-semibold">Year Level</label>
                                        <input class="form-control" value="{{ $yearLevel }}" disabled>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-muted small fw-semibold">Scholarship</label>
                                        <input class="form-control"
                                               value="{{ $scholarshipName }}"
                                               placeholder="(Blank if not a scholar)"
                                               disabled>
                                    </div>

                                    @if(!empty($batchNumber))
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-semibold">Batch Number</label>
                                            <input class="form-control" value="{{ $batchNumber }}" disabled>
                                        </div>
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                @if($isStudent && !$isAdminLike)
                {{-- RIGHT: CONTACT + PASSWORD --}}
                <div class="col-lg-6">

                    {{-- CONTACT (EDITABLE) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="color:{{ $theme }};">Contact Information</h5>
                                <span class="badge rounded-pill" style="background:{{ $soft }}; color:{{ $theme }};">
                                    Editable
                                </span>
                            </div>

                            <form action="{{ route('profile.update-contact') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Contact Number</label>

                                    <input type="text"
                                        name="contact_no"
                                        class="form-control"
                                        value="{{ old('contact_no', $user->contact_no ?? '') }}"
                                        placeholder="Enter contact number">

                                    <div class="small mt-1">
                                        <span class="text-muted">Current:</span>
                                        <span class="fw-semibold" style="color:#003366;">
                                            {{ $user->contact_no ? $user->contact_no : 'N/A' }}
                                        </span>
                                    </div>

                                    @error('contact_no')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn fw-semibold px-4" style="background:#003366; color:#fff;">
                                        Save Contact
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                    @endif

                    {{-- CHANGE PASSWORD --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color:{{ $theme }};">Change Password</h5>

                            <form action="{{ route('profile.update-password') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                    @error('current_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                    <div class="text-muted small mt-1">Minimum 8 characters, with letters & numbers.</div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn fw-semibold px-4"
                                            style="background:{{ $theme }}; color:#fff;">
                                        Update Password
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
