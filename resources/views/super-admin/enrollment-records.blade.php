@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Enrollment Records</h2>
</div>

{{-- Add Enrollment Form --}}
<div class="bg-white border border-gray-200 rounded p-4 mb-4">
    <div class="font-semibold text-[#003366] mb-3">Add Student Enrollment</div>

    <form action="{{ route('coordinator.enrollment-records.add') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        @csrf

        <div>
            <label class="text-sm font-semibold text-gray-700">Student</label>
            <select name="user_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select student</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">
                        {{ $u->lastname }}, {{ $u->firstname }} ({{ $u->student_id ?? 'No ID' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-700">Semester</label>
            <select name="semester_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select semester</option>
                @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}">{{ $sem->semester_name ?? ('Semester #' . $sem->id) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-700">Course</label>
            <select name="course_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">
                        {{ $course->course_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-700">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="enrolled">Enrolled</option>
                <option value="dropped">Dropped</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="md:col-span-4 flex justify-end">
            <button class="bg-[#003366] hover:opacity-90 text-white px-4 py-2 rounded font-semibold">
                Add Record
            </button>
        </div>
    </form>
</div>

{{-- Records Table --}}
<div class="bg-white border border-gray-200 rounded overflow-hidden">
    <div class="px-4 py-3 border-b font-semibold text-[#003366]">Enrollment List</div>

    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 text-sm">
                <th class="px-4 py-2 text-left">Student</th>
                <th class="px-4 py-2 text-left">Student ID</th>
                <th class="px-4 py-2 text-left">Course</th>
                <th class="px-4 py-2 text-left">Semester</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
        </thead>

        <tbody class="text-sm">
            @forelse($enrolledUsers as $e)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        {{ $e->user->firstname ?? 'N/A' }} {{ $e->user->lastname ?? '' }}
                    </td>
                    <td class="px-4 py-2">{{ $e->user->student_id ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $e->course->course_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $e->semester->semester_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $e->status ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-600">
                        No enrollment records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $enrolledUsers->links() }}
</div>
@endsection
