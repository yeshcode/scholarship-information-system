@extends('layouts.app')

@section('content')
@php
    // Ensure the variable is always defined (fallback to null)
    $studentUserTypeId = $studentUserTypeId ?? null;
@endphp

<h1 class="text-2xl font-bold mb-4">Add User</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <!-- Your existing fields (user_id, bisu_email, firstname, lastname, middlename, contact_no, student_id) remain unchanged -->
    <div class="mb-4">
        <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
        <input type="text" name="user_id" id="user_id" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="bisu_email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="bisu_email" id="bisu_email" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
        <input type="text" name="firstname" id="firstname" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
        <input type="text" name="lastname" id="lastname" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
        <input type="text" name="middlename" id="middlename" class="border p-2 w-full">
    </div>
    <div class="mb-4">
        <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact No</label>
        <input type="text" name="contact_no" id="contact_no" class="border p-2 w-full" required>
    </div>
    <div class="mb-4">
        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
        <input type="text" name="student_id" id="student_id" class="border p-2 w-full" required>  <!-- Added required here for consistency -->
    </div>
    <div class="mb-4">
        <label for="user_type_id" class="block text-sm font-medium text-gray-700">User Type</label>
        <select name="user_type_id" id="user_type_id" class="border p-2 w-full" required>
            <option value="">Select User Type</option>
            @foreach($userTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
        <select name="college_id" id="college_id" class="border p-2 w-full">
            <option value="">Select College</option>
            @foreach($colleges as $college)
                <option value="{{ $college->id }}">{{ $college->college_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
        <select name="year_level_id" id="year_level_id" class="border p-2 w-full">
            <option value="">Select Year Level</option>
            @foreach($yearLevels as $level)
                <option value="{{ $level->id }}">{{ $level->year_level_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
        <select name="section_id" id="section_id" class="border p-2 w-full">
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }})</option>
            @endforeach
        </select>
    </div>
    <!-- Updated Password Field with Eye Icon -->
    <div class="mb-4 relative">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" class="border p-2 w-full pr-10" required>  <!-- pr-10 for padding-right to make space for icon -->
        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </button>
    </div>
    <button type="submit" class="bg-green-500 text-black px-4 py-2 rounded hover:bg-green-600">Add User</button>
    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="ml-4 text-gray-500">Cancel</a>
</form>

<script>
    // Safely pass the ID (now guaranteed to be defined)
    const studentUserTypeId = @json($studentUserTypeId);
    
    // Function to toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';  // Closed eye icon
        } else {
            passwordField.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';  // Open eye icon
        }
    });
    
    // Handle user type change (show/hide fields and adjust password)
    // ... (your existing JS)

document.getElementById('user_type_id').addEventListener('change', function() {
    const isStudent = this.value == studentUserTypeId && studentUserTypeId !== null;
    const fields = ['college_id', 'year_level_id', 'section_id'];
    const passwordField = document.getElementById('password');
    const passwordDiv = passwordField.parentElement;

    fields.forEach(id => {
        const field = document.getElementById(id);
        field.required = isStudent;
        field.parentElement.style.display = isStudent ? 'block' : 'none';
    });

    // For students: Hide password field and CLEAR any value (prevents leftover "adminpass")
    if (isStudent) {
        passwordDiv.style.display = 'none';
        passwordField.required = false;
        passwordField.value = '';  // Clear the field to ensure no leftover password
    } else {
        passwordDiv.style.display = 'block';
        passwordField.required = true;
    }
});
</script>
@endsection