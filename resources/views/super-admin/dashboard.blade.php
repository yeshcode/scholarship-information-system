@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="text-center text-primary mb-4">Super Admin Dashboard</h2>
    </div>
</div>

<!-- Bulk Upload Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Bulk Upload / Register Users</h5>
    </div>
    <div class="card-body">
        <p>Upload an Excel file with user details. BISU Email and Student ID will be used for login.</p>
        <form method="POST" action="{{ route('admin.bulk-upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Select Excel File (.xlsx or .xls)</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload and Register Users</button>
        </form>
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </div>
</div>

<!-- User Management Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Users & Roles</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>BISU Email</th>
                    <th>Student ID</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example rows (replace with real data later) -->
                <tr>
                    <td>John Doe</td>
                    <td>john@bisu.edu</td>
                    <td>12345</td>
                    <td>Student</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Colleges Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Colleges</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCollegeModal">Add College</button>
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>College of Engineering</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Year Levels Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Year Levels</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addYearLevelModal">Add Year Level</button>
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>1st Year</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Semesters Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Semesters</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSemesterModal">Add Semester</button>
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>Fall 2023</td><td>2023-09-01</td><td>2023-12-31</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Courses Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Courses</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>Computer Science</td><td>Intro to CS</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Manage Sections Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Sections</h5>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSectionModal">Add Section</button>
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>Section A</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Enrollment Management Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Manage Enrollments</h5>
    </div>
    <div class="card-body">
        <p>Enroll users in semesters and courses.</p>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEnrollmentModal">Add Enrollment</button>
        <table class="table table-striped">
            <thead><tr><th>User</th><th>Semester</th><th>Course</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <tr><td>John Doe</td><td>Fall 2023</td><td>Computer Science</td><td>Active</td><td><button class="btn btn-sm btn-warning">Edit</button> <button class="btn btn-sm btn-danger">Delete</button></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modals for Add/Edit Forms (one example; repeat for others) -->
<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.create-user') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name*</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">BISU Email*</label>
                        <input type="email" name="bisu_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student ID*</label>
                        <input type="text" name="student_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role*</label>
                        <select name="role" class="form-control" required>
                            <option value="Student">Student</option>
                            <option value="Scholarship Coordinator">Scholarship Coordinator</option>
                            <option value="Guest">Guest</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Repeat similar modals for Edit User, Add College, etc. (e.g., copy and adjust fields) -->
<!-- Example: Edit User Modal (similar structure) -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <!-- Similar form, but pre-fill fields and change action to update -->
</div>

@endsection