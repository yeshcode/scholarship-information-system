{{-- resources/views/super-admin/users-edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Edit User</h1>
                <p class="text-sm text-gray-500">Update user information and academic details.</p>
            </div>
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
               class="text-sm text-blue-600 hover:text-blue-800 underline">
                ← Back to Users
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded border border-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded border border-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Basic info (no user_id) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">BISU Email</label>
                    <input type="email" name="bisu_email"
                           value="{{ old('bisu_email', $user->bisu_email) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact No</label>
                    <input type="text" name="contact_no"
                           value="{{ old('contact_no', $user->contact_no) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="firstname"
                           value="{{ old('firstname', $user->firstname) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="lastname"
                           value="{{ old('lastname', $user->lastname) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student ID (optional)</label>
                    <input type="text" name="student_id"
                           value="{{ old('student_id', $user->student_id) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <input type="text" name="status"
                           value="{{ old('status', $user->status) }}"
                           class="mt-1 border rounded w-full p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- User type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="user_type_id"
                            class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select User Type</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type->id }}" {{ old('user_type_id', $user->user_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Academic info --}}
            <div class="border-t border-gray-200 pt-4 mt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Academic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">College</label>
                        <select name="college_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select College</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ old('college_id', $user->college_id) == $college->id ? 'selected' : '' }}>
                                    {{ $college->college_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select name="year_level_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Year Level</option>
                            @foreach($yearLevels as $level)
                                <option value="{{ $level->id }}" {{ old('year_level_id', $user->year_level_id) == $level->id ? 'selected' : '' }}>
                                    {{ $level->year_level_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course</label>
                        <select name="course_id" id="course_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                        {{ old('course_id', $user->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-4">
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 btn-bisu-primary hover:bg-blue-700 text-white text-sm font-semibold rounded shadow-sm">
                    Update User
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // No JavaScript needed since course is selected directly
</script>
@endsection
