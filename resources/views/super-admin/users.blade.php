{{-- resources/views/super-admin/users.blade.php --}}

<div class="p-4">

    {{-- PAGE TITLE --}}
    <h1 class="fw-bold mb-4" style="font-size: 2rem; color: #0d6efd;">
        Manage System Users
    </h1>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg shadow-sm border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="flex justify-end mb-6 space-x-4">
        <a href="{{ route('admin.users.create') }}" 
            class="inline-flex items-center bg-blue-600 text-white hover:bg-blue-700 
                   font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
            + Add User
        </a>

        <a href="{{ route('admin.users.bulk-upload-form') }}" 
            class="inline-flex items-center bg-blue-500 text-white hover:bg-blue-600 
                   font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
            📤 Bulk Upload Students
        </a>
    </div>

    {{-- FILTERS FORM --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6">
        <input type="hidden" name="page" value="manage-users">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- College Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    College
                </label>
                <select
                    name="college_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Colleges</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}"
                            {{ request('college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->college_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Course Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Course
                </label>
                <select
                    name="course_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}"
                            {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Year Level Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Year Level
                </label>
                <select
                    name="year_level_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Year Levels</option>
                    @foreach($yearLevels as $level)
                        <option value="{{ $level->id }}"
                            {{ request('year_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->year_level_name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- CLEAR FILTERS BUTTON --}}
        @if(request('college_id') || request('course_id') || request('year_level_id'))
            <div class="mt-4">
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                   class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow-sm 
                          hover:bg-gray-300 transition">
                    ✖ Clear Filters
                </a>
            </div>
        @endif

    </form>

    {{-- USERS TABLE --}}
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="table-auto w-full text-center">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-3 py-2">Last Name</th>
                    <th class="px-3 py-2">First Name</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">College</th>
                    <th class="px-3 py-2">Course</th>
                    <th class="px-3 py-2">Year Level</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">{{ $user->lastname }}</td>
                        <td class="px-3 py-2">{{ $user->firstname }}</td>
                        <td class="px-3 py-2">{{ $user->bisu_email }}</td>
                        <td class="px-3 py-2">{{ $user->college->college_name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $user->course->course_name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $user->status }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Edit
                            </a>
                            <a href="{{ route('admin.users.delete', $user->id) }}" 
                               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Delete
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-gray-500">
                            No users found.
                            <a href="{{ route('admin.users.create') }}" class="text-blue-500 underline hover:text-blue-700">
                                Add one now
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   

      {{-- PAGINATION --}}
    <div class="mt-4 flex justify-center">
        {{ $users->appends(request()->except('users_page'))->links() }}
    </div>

</div>
