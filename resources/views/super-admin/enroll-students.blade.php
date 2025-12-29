@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Update Student Enrollments to New Semester</h1>
<p class="mb-6">Search for students by section, course, or year level. Select students, confirm your selection, then update their enrollment to a new semester.</p>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
@endif

<!-- Search Bar -->
<form method="GET" action="{{ route('admin.enrollments.enroll-students') }}" class="bg-gray-50 p-4 rounded border mb-6">
    <div class="flex gap-4">
        <input type="text" name="search" value="{{ $request->search }}" placeholder="Search by section, course, or year level (e.g., 'Computer Science', 'Section A', or '1st Year')" class="border p-2 flex-1">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
        <a href="{{ route('admin.enrollments.enroll-students') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Clear</a>
    </div>
</form>

<!-- Selectable Table of Students -->
<form id="selection-form">
    <div class="overflow-x-auto mb-4">
        <table class="table-auto w-full border-collapse border border-gray-300 text-center">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2"><input type="checkbox" id="select-all"></th>
                    <th class="border border-gray-300 px-4 py-2">Student ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Section</th>
                    <th class="border border-gray-300 px-4 py-2">Course</th>
                    <th class="border border-gray-300 px-4 py-2">Year Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="checkbox" name="selected_users[]" value="{{ $student->id }}" class="user-checkbox">
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->student_id ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->firstname }} {{ $student->lastname }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->bisu_email }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->section->section_name ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->section->course->course_name ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $student->yearLevel->year_level_name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">No students found. Try a different search term.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    {{ $students->links() }}

    <button type="button" id="proceed-btn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Proceed to Update Selected Students</button>
    <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="ml-4 text-gray-500 hover:text-gray-700">Back to Enrollments</a>
</form>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/2">
        <h2 class="text-xl font-bold mb-4">Confirm Selected Students</h2>
        <ul id="selected-list" class="mb-4"></ul>
        <form method="POST" action="{{ route('admin.enrollments.store-enroll-students') }}">
            @csrf
            <input type="hidden" id="selected-ids" name="selected_users[]" multiple>
            <div class="mb-4">
                <label for="semester_id" class="block text-sm font-medium">Update to New Semester</label>
                <select name="semester_id" id="semester_id" class="border p-2 w-full" required>
                    <option value="">Select New Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->term }} {{ $semester->academic_year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="section_id" class="block text-sm font-medium">Update to New Section (Optional)</label>
                <select name="section_id" id="section_id" class="border p-2 w-full">
                    <option value="">Select New Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->course->course_name ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Update Enrollments</button>
            <button type="button" id="cancel-btn" class="ml-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
        </form>
    </div>
</div>

<!-- ... (rest of the view remains the same) -->

<script>
    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
    
    // Proceed to confirmation
    document.getElementById('proceed-btn').addEventListener('click', function() {
        const selected = document.querySelectorAll('.user-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one student.');
            return;
        }
        const list = document.getElementById('selected-list');
        const form = document.querySelector('#confirmation-modal form'); // Target the form in the modal
        list.innerHTML = '';
        
        // Clear any existing hidden inputs for selected_users
        const existing = form.querySelectorAll('input[name="selected_users[]"]');
        existing.forEach(input => input.remove());
        
        selected.forEach(cb => {
            const studentName = cb.closest('tr').querySelector('td:nth-child(3)').textContent; // Name column
            list.innerHTML += `<li>${studentName}</li>`;
            
            // Add a hidden input for each selected ID
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selected_users[]';
            hiddenInput.value = cb.value;
            form.appendChild(hiddenInput);
        });
        
        document.getElementById('confirmation-modal').classList.remove('hidden');
    });
    
    // Cancel modal
    document.getElementById('cancel-btn').addEventListener('click', function() {
        document.getElementById('confirmation-modal').classList.add('hidden');
    });
    </script>
    @endsection