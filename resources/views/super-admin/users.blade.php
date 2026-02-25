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

    /* Make modal body scroll reliably */
    .modal .modal-content{
        max-height: calc(100vh - 2rem);
    }
    .modal .modal-body{
        overflow-y: auto;
        max-height: calc(100vh - 190px);
    }

    @media (min-width: 992px){
        .modal.modal-wide .modal-dialog{
            max-width: 1100px;
        }
    }
</style>

<div class="p-3">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title-blue mb-0">Manage System Users</h1>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="d-flex justify-content-end gap-2 mb-3">
        <button type="button"
                class="btn btn-primary btn-sm"
                style="background-color:#003366; border-color:#003366;"
                data-bs-toggle="modal"
                data-bs-target="#addUserModal">
            Add User
        </button>

        <button type="button"
            class="btn btn-primary btn-sm"
            style="background-color:#003366; border-color:#003366;"
            data-bs-toggle="modal"
            data-bs-target="#bulkUploadModal">
        Bulk Upload Students
    </button>
    </div>

    {{-- FILTERS FORM --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
        <input type="hidden" name="page" value="manage-users">

        <div class="row g-2">
            {{-- College Filter --}}
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">College</label>
                <select name="college_id" class="form-select form-select-sm"
                    onchange="
                        const courseSelect = this.form.querySelector('select[name=course_id]');
                        if (courseSelect) courseSelect.selectedIndex = 0;
                        this.form.submit();
                    ">
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
                <select name="course_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()"
                        @if(!request('college_id')) disabled @endif>
                    <option value="">
                        {{ request('college_id') ? 'All Courses' : 'Select a college first' }}
                    </option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @if(!request('college_id'))
                    {{-- <small class="text-muted">Choose a college to load courses.</small> --}}
                @endif
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

        {{-- SEARCH BOX --}}
        <div class="row mt-2">
            <div class="col-12">
                {{-- <label class="form-label mb-1">Search</label> --}}
                <div class="input-group input-group-sm">
                    <span class="input-group-text">🔎</span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search Student ID, Last Name, First Name, Email..."
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" style="background-color:#003366; border-color:#003366;" type="submit">
                        Search
                    </button>
                </div>
                {{-- <small class="text-muted">Tip: Press Enter to search quickly.</small> --}}
            </div>
        </div>

        {{-- CLEAR FILTERS BUTTON --}}
        @if(request('college_id') || request('course_id') || request('year_level_id'))
            <div class="mt-2">
                <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="btn btn-secondary btn-sm">
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
                        <th>Middle Name</th>
                        <th>Suffix</th>
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
                        @php
                            // ✅ SAFER: build the payload first to avoid ParseError
                            $editPayload = [
                                'id' => $user->id,
                                'bisu_email' => $user->bisu_email,
                                'firstname' => $user->firstname,
                                'middlename' => $user->middlename,
                                'lastname' => $user->lastname,
                                'suffix' => $user->suffix,
                                'contact_no' => $user->contact_no,
                                'student_id' => $user->student_id,
                                'status' => $user->status,
                                'user_type_id' => $user->user_type_id,
                                'college_id' => $user->college_id,
                                'course_id' => $user->course_id,
                                'year_level_id' => $user->year_level_id,
                            ];

                            $deletePayload = [
                                'id' => $user->id,
                                'name' => trim(($user->lastname ?? '').', '.($user->firstname ?? '')),
                                'email' => $user->bisu_email,
                                'student_id' => $user->student_id,
                            ];
                        @endphp

                        <tr>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->middlename ?? '—' }}</td>
                            <td>{{ $user->suffix ?? '—' }}</td>
                            <td>{{ $user->student_id ?? 'N/A' }}</td>
                            <td>{{ $user->bisu_email }}</td>
                            <td>{{ $user->college->college_name ?? 'N/A' }}</td>
                            <td>{{ $user->course->course_name ?? 'N/A' }}</td>
                            <td>{{ $user->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <button type="button"
                                    class="btn btn-primary btn-compact text-white"
                                    style="background-color:#003366; border-color:#003366;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal"
                                    data-user='@json($editPayload)'>
                                    Edit
                                </button>

                                <button type="button"
                                    class="btn btn-danger btn-compact text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal"
                                    data-user='@json($deletePayload)'>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-muted py-3">
                                No users found.
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

{{-- =========================
    ADD USER MODAL
========================= --}}
<div class="modal fade modal-wide" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    @if($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- LEFT: Account Info --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold text-primary mb-2">Account Info</div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">BISU Email</label>
                                        <input type="email" name="bisu_email" class="form-control form-control-sm"
                                               value="{{ old('bisu_email') }}" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact No</label>
                                        <input type="text" name="contact_no" class="form-control form-control-sm"
                                               value="{{ old('contact_no') }}">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="firstname" class="form-control form-control-sm"
                                               value="{{ old('firstname') }}" required>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middlename" class="form-control form-control-sm"
                                               value="{{ old('middlename') }}">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Suffix (optional)</label>
                                        <input type="text" name="suffix" class="form-control form-control-sm"
                                               value="{{ old('suffix') }}" placeholder="Jr, Sr, III...">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="lastname" class="form-control form-control-sm"
                                               value="{{ old('lastname') }}" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">User Type</label>
                                        <select name="user_type_id" id="m_user_type_id" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            @foreach($userTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('user_type_id')==$type->id?'selected':'' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Student ID (only for Students)</label>
                                        <input type="text" name="student_id" id="m_student_id"
                                               class="form-control form-control-sm"
                                               value="{{ old('student_id') }}">
                                        {{-- <div class="form-text">For students, this will be their default password.</div> --}}
                                    </div>

                                    <div class="col-12" id="m_password_wrapper">
                                        <label class="form-label">Password (for non-students)</label>

                                        <div class="input-group input-group-sm">
                                            <input type="password" name="password" id="m_password" class="form-control form-control-sm">
                                            <button class="btn btn-outline-secondary" type="button" id="m_toggle_password">
                                                <span id="m_eye_text">Show</span>
                                            </button>
                                        </div>

                                        {{-- <div class="form-text">If Student, password will be Student ID.</div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: Academic Info --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold text-primary mb-2">Academic Info (Students)</div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">College</label>
                                        <select name="college_id" id="m_college_id" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->id }}" {{ old('college_id')==$college->id?'selected':'' }}>
                                                    {{ $college->college_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Course</label>
                                        <select name="course_id" id="m_course_id" class="form-select form-select-sm" disabled>
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Year Level</label>
                                        <select name="year_level_id" id="m_year_level_id" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            @foreach($yearLevels as $level)
                                                <option value="{{ $level->id }}" {{ old('year_level_id')==$level->id?'selected':'' }}>
                                                    {{ $level->year_level_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="col-12">
                                        <div class="alert alert-light border small mb-0">
                                            Tip: For non-student users, Academic Info can be left empty.
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                    </div><!-- row -->
                </div><!-- modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background:#003366;border-color:#003366;">
                        Save User
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- =========================
    EDIT USER MODAL
========================= --}}
<div class="modal fade modal-wide" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" id="editUserForm" action="#">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- LEFT: Account --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold text-primary mb-2">Account Info</div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">BISU Email</label>
                                        <input type="email" name="bisu_email" id="e_bisu_email" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Contact No</label>
                                        <input type="text" name="contact_no" id="e_contact_no" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="firstname" id="e_firstname" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middlename" id="e_middlename" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Suffix</label>
                                        <input type="text" name="suffix" id="e_suffix" class="form-control form-control-sm" placeholder="Jr, Sr, III...">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="lastname" id="e_lastname" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">User Type</label>
                                        <select name="user_type_id" id="e_user_type_id" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            @foreach($userTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" id="e_status" class="form-select form-select-sm" required>
                                            <option value="active">active</option>
                                            <option value="inactive">inactive</option>
                                            <option value="graduated">graduated</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" name="student_id" id="e_student_id" class="form-control form-control-sm">
                                        {{-- <div class="form-text">Tip: You can reset password to Student ID below.</div> --}}
                                    </div>

                                    <div class="col-12">
                                        <div class="border rounded-3 p-2">
                                            <div class="fw-semibold small mb-2">Password</div>

                                            <div class="input-group input-group-sm mb-2">
                                                <input type="password" name="password" id="e_password" class="form-control" placeholder="Leave blank to keep current">
                                                <button class="btn btn-outline-secondary" type="button" id="e_toggle_password">
                                                    <span id="e_eye_text">Show</span>
                                                </button>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="e_reset_pass" name="reset_password_to_student_id">
                                                <label class="form-check-label" for="e_reset_pass">
                                                    Reset password to Student ID
                                                </label>
                                            </div>

                                            <small class="text-muted d-block mt-1">
                                                Note: We cannot display the old password (it is encrypted).
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- RIGHT: Academic --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold text-primary mb-2">Academic Info</div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">College</label>
                                        <select name="college_id" id="e_college_id" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Course</label>
                                        <select name="course_id" id="e_course_id" class="form-select form-select-sm" disabled>
                                            <option value="">Select college first</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Year Level</label>
                                        <select name="year_level_id" id="e_year_level_id" class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            @foreach($yearLevels as $level)
                                                <option value="{{ $level->id }}">{{ $level->year_level_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <div class="alert alert-light border small mb-0">
                                            You may leave academic fields empty for non-student users.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- row -->
                </div><!-- modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background:#003366;border-color:#003366;">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- =========================
    DELETE USER MODAL
========================= --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" id="deleteUserForm" action="#">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete this user? This action cannot be undone.</p>

                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold" id="d_name">—</div>
                        <div class="small text-muted" id="d_email">—</div>
                        <div class="small text-muted" id="d_student_id">—</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- =========================
    BULK UPLOAD MODAL (Step 1)
========================= --}}
<div class="modal fade modal-wide" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">

            <form method="POST"
                  action="{{ route('admin.users.bulk-upload.preview') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-header" style="background:#f4f7fb;">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color:#003366;">Bulk Upload Students</h5>
                        {{-- <div class="small text-muted">Upload an Excel/CSV file, then review the preview before saving.</div> --}}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Alerts --}}
                    @if(session('error'))
                        <div class="alert alert-danger py-2 small mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger small mb-3">
                            <div class="fw-bold mb-1">Please fix the following:</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="border rounded-4 p-3" style="background:#f6faff; border-color:rgba(0,51,102,.18) !important;">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <span class="badge rounded-pill text-bg-primary">Step 1</span>
                            <div>
                                <div class="fw-semibold" style="color:#003366;">Choose your file</div>
                                <div class="small text-muted">Accepted: .xlsx, .xls, .csv</div>
                            </div>
                        </div>

                        <input type="file"
                               name="file"
                               id="bulk_file"
                               class="form-control"
                               accept=".xlsx,.xls,.csv"
                               required>

                        {{-- <div class="form-text mt-2">
                            Tip: Make sure columns match your template format.
                        </div> --}}
                    </div>

                </div>

                <div class="modal-footer" style="background:#fff;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-primary btn-sm"
                            style="background:#003366;border-color:#003366;">
                        Upload
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Auto-open Add modal if validation errors happened --}}
@if($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('addUserModal');
    if(modalEl){
      const m = new bootstrap.Modal(modalEl);
      m.show();
    }
  });
</script>
@endif

@php
  $studentUserTypeId = $studentUserTypeId ?? null;
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

  const studentUserTypeId = @json($studentUserTypeId ?? null);

  // -----------------------------
  // Helpers
  // -----------------------------
  function isStudentType(selectedValue){
    return (studentUserTypeId !== null) && (String(selectedValue) === String(studentUserTypeId));
  }

  async function loadCoursesByCollege(selectCourseEl, collegeId, selectedCourseId = null) {
    selectCourseEl.innerHTML = '';
    selectCourseEl.disabled = true;

    if (!collegeId) {
      selectCourseEl.innerHTML = `<option value="">Select college first</option>`;
      return;
    }

    selectCourseEl.innerHTML = `<option value="">Loading...</option>`;

    try {
      const url = `{{ route('admin.ajax.coursesByCollege') }}?college_id=${encodeURIComponent(collegeId)}`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      const courses = await res.json();

      selectCourseEl.innerHTML = `<option value="">Select</option>`;

      courses.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.course_name;
        if (selectedCourseId && String(selectedCourseId) === String(c.id)) opt.selected = true;
        selectCourseEl.appendChild(opt);
      });

      selectCourseEl.disabled = false;
    } catch (e) {
      selectCourseEl.innerHTML = `<option value="">Failed to load courses</option>`;
    }
  }

  // -----------------------------
  // ADD MODAL: student mode + toggle password + load courses
  // -----------------------------
  const m_userType   = document.getElementById('m_user_type_id');
  const m_passWrap   = document.getElementById('m_password_wrapper');
  const m_passInp    = document.getElementById('m_password');
  const m_toggleBtn  = document.getElementById('m_toggle_password');
  const m_eyeText    = document.getElementById('m_eye_text');
  const m_collegeSel = document.getElementById('m_college_id');
  const m_courseSel  = document.getElementById('m_course_id');

  function syncAddStudentMode(){
    const student = isStudentType(m_userType?.value);
    if(student){
      m_passWrap.style.display = 'none';
      m_passInp.required = false;
      m_passInp.value = '';
    }else{
      m_passWrap.style.display = 'block';
      m_passInp.required = true;
    }
  }

  m_userType?.addEventListener('change', syncAddStudentMode);
  syncAddStudentMode();

  m_toggleBtn?.addEventListener('click', function () {
    if (m_passInp.type === 'password') {
      m_passInp.type = 'text';
      m_eyeText.textContent = 'Hide';
    } else {
      m_passInp.type = 'password';
      m_eyeText.textContent = 'Show';
    }
  });

  m_collegeSel?.addEventListener('change', function(){
    loadCoursesByCollege(m_courseSel, this.value);
  });

  // restore old selections on validation
  const oldCollegeId = @json(old('college_id'));
  const oldCourseId  = @json(old('course_id'));
  if(oldCollegeId){
    loadCoursesByCollege(m_courseSel, oldCollegeId, oldCourseId);
  }

  // -----------------------------
  // EDIT MODAL: fill inputs + load courses + toggle password
  // -----------------------------
  const editModalEl = document.getElementById('editUserModal');
  editModalEl?.addEventListener('show.bs.modal', async function (event) {
    const button = event.relatedTarget;
    const payload = button?.getAttribute('data-user');
    if(!payload) return;

    const user = JSON.parse(payload);

    // set form action
    const form = document.getElementById('editUserForm');
    form.action = `{{ url('/admin/users') }}/${user.id}`;

    // fill values
    document.getElementById('e_bisu_email').value = user.bisu_email ?? '';
    document.getElementById('e_contact_no').value = user.contact_no ?? '';
    document.getElementById('e_firstname').value = user.firstname ?? '';
    document.getElementById('e_middlename').value = user.middlename ?? '';
    document.getElementById('e_lastname').value = user.lastname ?? '';
    document.getElementById('e_suffix').value = user.suffix ?? '';
    document.getElementById('e_student_id').value = user.student_id ?? '';
    document.getElementById('e_status').value = user.status ?? 'active';
    document.getElementById('e_user_type_id').value = user.user_type_id ?? '';

    // academic
    const e_college = document.getElementById('e_college_id');
    const e_course  = document.getElementById('e_course_id');
    const e_year    = document.getElementById('e_year_level_id');

    e_college.value = user.college_id ?? '';
    e_year.value    = user.year_level_id ?? '';

    // load courses + select current
    await loadCoursesByCollege(e_course, e_college.value, user.course_id ?? null);

    // clear password inputs
    document.getElementById('e_password').value = '';
    document.getElementById('e_reset_pass').checked = false;
  });

  // toggle edit password show/hide
  const e_toggleBtn = document.getElementById('e_toggle_password');
  const e_passInp   = document.getElementById('e_password');
  const e_eyeText   = document.getElementById('e_eye_text');

  e_toggleBtn?.addEventListener('click', function(){
    if(e_passInp.type === 'password'){
      e_passInp.type = 'text';
      e_eyeText.textContent = 'Hide';
    }else{
      e_passInp.type = 'password';
      e_eyeText.textContent = 'Show';
    }
  });

  // edit college change -> reload courses
  document.getElementById('e_college_id')?.addEventListener('change', function(){
    loadCoursesByCollege(document.getElementById('e_course_id'), this.value);
  });

  // -----------------------------
  // DELETE MODAL: fill details + set action
  // -----------------------------
  const deleteModalEl = document.getElementById('deleteUserModal');
  deleteModalEl?.addEventListener('show.bs.modal', function(event){
    const button = event.relatedTarget;
    const payload = button?.getAttribute('data-user');
    if(!payload) return;

    const user = JSON.parse(payload);

    const form = document.getElementById('deleteUserForm');
    form.action = `{{ url('/admin/users') }}/${user.id}`;

    document.getElementById('d_name').textContent = user.name ?? '—';
    document.getElementById('d_email').textContent = `Email: ${user.email ?? '—'}`;
    document.getElementById('d_student_id').textContent = `Student ID: ${user.student_id ?? 'N/A'}`;
  });

});
</script>