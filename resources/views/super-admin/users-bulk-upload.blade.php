{{-- resources/views/super-admin/users-bulk-upload.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Bulk Upload Students</h1>
                <p class="text-sm text-gray-500">
                    Upload a CSV file to register multiple students at once.
                </p>
            </div>
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
               class="text-sm text-blue-600 hover:text-blue-800 underline">
                ← Back to Users
            </a>
        </div>

        <p class="mb-4 text-sm text-gray-600">
            CSV should include headers like:
            <span class="font-mono bg-gray-100 px-1 py-0.5 rounded">
                firstname, lastname, bisu_email, contact_no, student_id
            </span>.
            Extra columns will be ignored.
        </p>

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

        <form method="POST" action="{{ route('admin.users.bulk-upload') }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf

            <div>
                <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File</label>
                <input type="file" name="csv_file" id="csv_file"
                       class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">
                    Only <span class="font-mono">.csv</span> and <span class="font-mono">.txt</span> files are allowed.
                </p>
            </div>

            <div class="border-t border-gray-200 pt-4 mt-2">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Default Academic Assignment</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="college_id" class="block text-sm font-medium text-gray-700">College</label>
                        <select name="college_id" id="college_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select College</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                    {{ $college->college_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select name="year_level_id" id="year_level_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select Year Level</option>
                            @foreach($yearLevels as $level)
                                <option value="{{ $level->id }}" {{ old('year_level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->year_level_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
                        <select name="section_id" id="section_id"
                                class="mt-1 border rounded w-full p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                        data-course="{{ $section->course->course_name ?? 'N/A' }}"
                                        {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->section_name }}
                                    @if($section->course)
                                        ({{ $section->course->course_name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course (based on Section)</label>
                        <div id="coursePreview"
                             class="mt-1 w-full p-2 text-sm border rounded bg-gray-50 text-gray-700">
                            —
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            All uploaded students will be tagged with this course &amp; section.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 mt-4">
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                   class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded shadow-sm">
                    Upload Students
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sectionSelect = document.getElementById('section_id');
        const coursePreview = document.getElementById('coursePreview');

        if (sectionSelect && coursePreview) {
            const updateCourse = () => {
                const sel = sectionSelect.options[sectionSelect.selectedIndex];
                const courseName = sel ? sel.getAttribute('data-course') : '—';
                coursePreview.textContent = courseName || '—';
            };

            sectionSelect.addEventListener('change', updateCourse);
            updateCourse();
        }
    });
</script>
@endsection
