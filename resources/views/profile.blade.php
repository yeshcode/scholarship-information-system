@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile</h1>

    <!-- Display User Info (Read-Only) -->
    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Scholarship Coordinator'))
        <p><strong>Name:</strong> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
        <p><strong>Position/Role:</strong> {{ auth()->user()->userType->name ?? 'N/A' }}</p>
    @else
        <!-- For Students -->
        <p><strong>Name:</strong> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
        <p><strong>Section:</strong> {{ auth()->user()->section->section_name ?? 'N/A' }}</p>
        <p><strong>College Department:</strong> {{ auth()->user()->college->college_name ?? 'N/A' }}</p>
        <p><strong>Semester:</strong> {{ auth()->user()->enrollments->where('status', 'active')->first()->semester->term ?? 'N/A' }} {{ auth()->user()->enrollments->where('status', 'active')->first()->semester->academic_year ?? '' }}</p>
        <p><strong>Year Level:</strong> {{ auth()->user()->yearLevel->year_level_name ?? 'N/A' }}</p>
        <p><strong>Course:</strong> {{ auth()->user()->section->course->course_name ?? 'N/A' }}</p>
        @if(auth()->user()->isScholar())
            <p><strong>Scholarship Name:</strong> {{ auth()->user()->scholarsAsStudent->first()->scholarship->name ?? 'N/A' }}</p>
            <p><strong>Batch Number:</strong> {{ auth()->user()->scholarsAsStudent->first()->batch_number ?? 'N/A' }}</p>
        @endif
    @endif

    <hr>

    <!-- Password Change Form -->
    <h3>Change Password</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('profile.update-password') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>
@endsection