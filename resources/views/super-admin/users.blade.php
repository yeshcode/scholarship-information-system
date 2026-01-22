{{-- resources/views/super-admin/users.blade.php --}}

<style>
    /* Compact table (more rows visible) */
    .table-compact th,
    .table-compact td {
        padding: 0.35rem 0.45rem !important;
        font-size: 0.82rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-compact thead th {
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    /* Make action buttons smaller */
    .btn-compact {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    /* Blue header like your design */
    .thead-bisu {
        background-color: #003366;
        color: #fff;
    }
</style>

<div class="p-3">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title-blue mb-0">Manage System Users</h1>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger py-2 mb-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('admin.users.create') }}"
           class="btn btn-primary btn-sm"
           style="background-color:#003366; border-color:#003366;">
            + Add User
        </a>

        <a href="{{ route('admin.users.bulk-upload-form') }}"
           class="btn btn-primary btn-sm"
           style="background-color:#003366; border-color:#003366;">
            📤 Bulk Upload Students
        </a>
    </div>

    {{-- FILTERS FORM --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
        <input type="hidden" name="page" value="manage-users">

        <div class="row g-2">
            {{-- College Filter --}}
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">College</label>
                <select name="college_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Colleges</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}" {{ request('college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->college_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Course Filter --}}
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Course</label>
                <select name="course_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Year Level Filter --}}
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Year Level</label>
                <select name="year_level_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Year Levels</option>
                    @foreach($yearLevels as $level)
                        <option value="{{ $level->id }}" {{ request('year_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->year_level_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- CLEAR FILTERS BUTTON --}}
        @if(request('college_id') || request('course_id') || request('year_level_id'))
            <div class="mt-2">
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                   class="btn btn-secondary btn-sm">
                    ✖ Clear Filters
                </a>
            </div>
        @endif
    </form>

    {{-- USERS TABLE --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm table-compact text-center mb-0">
                <thead class="thead-bisu">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>College</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th style="min-width:140px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->student_id ?? 'N/A' }}</td>
                            <td>{{ $user->bisu_email }}</td>
                            <td>{{ $user->college->college_name ?? 'N/A' }}</td>
                            <td>{{ $user->course->course_name ?? 'N/A' }}</td>
                            <td>{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-primary btn-compact text-white"
                                   style="background-color:#003366; border-color:#003366;">
                                    Edit
                                </a>

                                <a href="{{ route('admin.users.delete', $user->id) }}"
                                   class="btn btn-danger btn-compact text-white">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted py-3">
                                No users found.
                                <a href="{{ route('admin.users.create') }}" class="text-primary text-decoration-underline">
                                    Add one now
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3 d-flex justify-content-center">
        {{ $users->appends(request()->except('users_page'))->links() }}
    </div>

</div>
