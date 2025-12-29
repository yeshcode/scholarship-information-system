@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

<!-- Buttons: Keep Add Enrollment for manual adds, add Enroll Students beside it -->
<a href="{{ route('admin.enrollments.create') }}" class="bg-white text-black border border-black hover:bg-gray-100 font-bold py-2 px-4 rounded mb-4 inline-block mr-4">
    Add Enrollment
</a>
<a href="{{ route('admin.enrollments.enroll-students') }}" class="bg-purple-600 hover:bg-purple-800 text-black font-bold py-2 px-4 rounded mb-4 inline-block border border-purple-600">
    Enroll Students
</a>

<!-- Optional Filters for Viewing Enrolled Students (keeps your page clean) -->
<form method="GET" action="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="bg-gray-50 p-4 rounded border mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="semester_id" class="block text-sm font-medium">Filter by Semester</label>
            <select name="semester_id" id="semester_id" class="border p-2 w-full">
                <option value="">All Semesters</option>
                @foreach($semesters ?? [] as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>{{ $semester->term }} {{ $semester->academic_year }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="section_id" class="block text-sm font-medium">Filter by Section</label>
            <select name="section_id" id="section_id" class="border p-2 w-full">
                <option value="">All Sections</option>
                @foreach($sections ?? [] as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded mt-4 hover:bg-blue-600">Apply Filters</button>
</form>

<!-- Table with Data (unchanged from your original) -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">User</th>
                <th class="border border-gray-300 px-4 py-2">Semester</th>
                <th class="border border-gray-300 px-4 py-2">Section</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments ?? [] as $enrollment)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $enrollment->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $enrollment->user->firstname ?? 'N/A' }} {{ $enrollment->user->lastname ?? '' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $enrollment->semester->term ?? 'N/A' }} {{ $enrollment->semester->academic_year ?? '' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $enrollment->section->section_name ?? 'N/A' }} ({{ $enrollment->section->course->course_name ?? '' }})</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $enrollment->status }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.enrollments.edit', $enrollment->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection