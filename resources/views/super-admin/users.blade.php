{{-- resources/views/super-admin/users.blade.php --}}

<div class="p-4">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title-blue">
        Manage System Users
    </h1>
</div>


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
            class="inline-flex items-center btn-bisu-primary text-white hover:bg-blue-700 
                   font-bold py-2 px-4 rounded-lg shadow-md transition duration-200">
            + Add User
        </a>

        <a href="{{ route('admin.users.bulk-upload-form') }}" 
            class="inline-flex items-center btn-bisu-primary text-white hover:bg-blue-600 
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
<div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
    <table class="table-auto w-full text-center border-collapse">
        <thead>
            <tr class="bg-[#003366] text-white text-sm uppercase tracking-wide">
                <th class="px-4 py-3 border border-gray-300">Last Name</th>
                <th class="px-4 py-3 border border-gray-300">First Name</th>
                <th class="px-4 py-3 border border-gray-300">Email</th>
                <th class="px-4 py-3 border border-gray-300">College</th>
                <th class="px-4 py-3 border border-gray-300">Course</th>
                <th class="px-4 py-3 border border-gray-300">Year Level</th>
                <th class="px-4 py-3 border border-gray-300">Status</th>
                <th class="px-4 py-3 border border-gray-300">Actions</th>
            </tr>
        </thead>

        <tbody class="text-gray-700 text-sm">
            @forelse($users as $user)
                <tr class="hover:bg-gray-100 transition even:bg-gray-50">
                    <td class="px-4 py-3 border border-gray-200">{{ $user->lastname }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->firstname }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->bisu_email }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->college->college_name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->course->course_name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $user->status }}</td>

                    <td class="px-4 py-3 border border-gray-200 space-x-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                           class="btn btn-sm btn-primary text-white px-3 py-1 rounded shadow-sm"
                           style="background-color:#003366;">
                            Edit
                        </a>

                        <a href="{{ route('admin.users.delete', $user->id) }}" 
                           class="btn btn-sm btn-danger text-white px-3 py-1 rounded shadow-sm">
                            Delete
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-5 text-gray-500 text-center">
                        No users found.
                        <a href="{{ route('admin.users.create') }}" 
                           class="text-blue-600 underline hover:text-blue-800">
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
