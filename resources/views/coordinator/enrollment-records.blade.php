@extends('layouts.coordinator')

@section('page-content')
    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Enrollment Records</h2>
    </div>

    {{-- Add Enrollment --}}
    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-3">Add Enrollment Record</h3>

        <form action="{{ route('coordinator.enrollment-records.add') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf

            <div>
                <label class="text-sm font-medium">Student</label>
                <select name="user_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select student</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->lastname }}, {{ $u->firstname }} ({{ $u->student_id ?? 'No ID' }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Semester</label>
                <select name="semester_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select semester</option>
                    @foreach($semesters as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->semester_name ?? ('Semester #' . $s->id) }}
                        </option>
                    @endforeach
                </select>
                @error('semester_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Course</label>
                <select name="course_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="enrolled">Enrolled</option>
                    <option value="dropped">Dropped</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    Add Record
                </button>
            </div>
        </form>
    </div>

    {{-- Records Table --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Student</th>
                    <th class="px-4 py-3 text-left">Student ID</th>
                    <th class="px-4 py-3 text-left">Course</th>
                    <th class="px-4 py-3 text-left">Semester</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($enrolledUsers as $en)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $en->user->lastname ?? 'N/A' }}, {{ $en->user->firstname ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">{{ $en->user->student_id ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $en->course->course_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $en->semester->semester_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">
                                {{ $en->status ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
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
