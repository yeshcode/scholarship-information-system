@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Bulk Upload Students</h1>
<p class="mb-6">Upload a CSV file to add multiple students. CSV should have headers matching database fields (e.g., firstname, lastname, bisu_email). Extra columns are ignored; required fields are auto-set.</p>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.users.bulk-upload') }}" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded border">
    @csrf
    <div class="mb-4">
        <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File</label>
        <input type="file" name="csv_file" id="csv_file" class="border p-2 w-full" required>
        <p class="text-sm text-gray-500 mt-1">Example CSV format: firstname,lastname,bisu_email,contact_no,student_id,user_id</p>
    </div>
    
    <div class="mb-4">
        <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
        <select name="college_id" id="college_id" class="border p-2 w-full" required>
            <option value="">Select College</option>
            @foreach($colleges as $college)
                <option value="{{ $college->id }}">{{ $college->college_name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-4">
        <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
        <select name="year_level_id" id="year_level_id" class="border p-2 w-full" required>
            <option value="">Select Year Level</option>
            @foreach($yearLevels as $level)
                <option value="{{ $level->id }}">{{ $level->year_level_name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-4">
        <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
        <select name="section_id" id="section_id" class="border p-2 w-full" required>
            <option value="">Select Section</option>
            @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }})</option>
            @endforeach
        </select>
    </div>
    
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Upload Students</button>
    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="ml-4 text-gray-500">Back to Manage Users</a>
</form>
@endsection