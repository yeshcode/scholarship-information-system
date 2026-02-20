{{-- resources/views/super-admin/courses.blade.php --}}
@php $fullWidth = true; @endphp
@extends('layouts.app')

@section('content')

<style>
    :root{
        --brand:#0b2e5e;
        --brand2:#123f85;
        --muted:#6b7280;
        --bg:#f4f7fb;
        --line:#e5e7eb;
        --danger:#b30000;
    }

    body{ background: var(--bg); }

    .page-title{
        font-weight:900;
        font-size:1.85rem;
        color:var(--brand);
        letter-spacing:.2px;
        margin:0;
    }
    .page-sub{ color:var(--muted); font-size:.92rem; }

    .table-card{
        background:#fff;
        border:1px solid var(--line);
        border-radius:16px;
        overflow:hidden;
        box-shadow: 0 14px 34px rgba(15,23,42,.07);
    }

    .modern-table thead{
        background: var(--brand);
        color:#fff;
    }
    .modern-table th, .modern-table td{
        border: 1px solid var(--line);
        padding: 12px 14px;
        font-size: .92rem;
        vertical-align: middle;
    }
    .modern-table tbody tr:nth-child(even){ background:#f9fafb; }
    .modern-table tbody tr:hover{ background:#eef6ff; transition:.12s ease; }

    .btn-bisu{
        font-weight:800;
        border-radius:12px;
        padding:.45rem .85rem;
        font-size:.85rem;
        white-space: nowrap;
    }
    .btn-bisu-primary{
        background: var(--brand);
        border:1px solid rgba(11,46,94,.35);
        color:#fff;
    }
    .btn-bisu-primary:hover{ background: var(--brand2); color:#fff; }

    .btn-bisu-outline{
        background:#fff;
        border:1px solid rgba(11,46,94,.35);
        color:var(--brand);
    }
    .btn-bisu-outline:hover{ background: var(--brand); color:#fff; }

    .btn-bisu-danger{
        background:#fff;
        border:1px solid rgba(179,0,0,.35);
        color: var(--danger);
    }
    .btn-bisu-danger:hover{ background: var(--danger); color:#fff; }

    .actions-wrap{
        display:flex;
        justify-content:center;
        gap:.4rem;
        flex-wrap:wrap;
    }

    /* Modal polish */
    .modal-content{
        border:1px solid var(--line);
        border-radius:18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
    }
    .modal-header{
        border-bottom:1px solid var(--line);
        background: linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
    }
    .modal-title{
        font-weight:900;
        color:var(--brand);
        letter-spacing:.2px;
    }
    .form-label{
        font-weight:800;
        font-size:.9rem;
        color:#0f172a;
    }
    .form-control, .form-select{
        border-radius:14px;
        border:1px solid var(--line);
        padding:.7rem .9rem;
    }
    .form-control:focus, .form-select:focus{
        border-color: rgba(11,46,94,.35);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }
    .hint{ font-size:.84rem; color:var(--muted); }

    .danger-box{
        border:1px solid rgba(179,0,0,.25);
        background:#fdecec;
        border-radius:14px;
        padding:.9rem 1rem;
    }

    /* ===== Responsive ===== */
    @media (max-width: 576px){
        .page-title{ font-size: 1.45rem; }
        .page-sub{ font-size: .86rem; }
        .modern-table th, .modern-table td{
            padding: 10px 10px;
            font-size: .88rem;
        }
        .modal-dialog{ margin: .75rem; }
        .modal-content{ border-radius: 16px; }
    }

    /* ===== Mobile table -> cards ===== */
    @media (max-width: 768px){
        .modern-table thead{ display:none; }
        .modern-table, .modern-table tbody, .modern-table tr, .modern-table td{
            display:block; width:100%;
        }
        .modern-table tr{
            background:#fff;
            margin: .65rem .65rem;
            border:1px solid var(--line);
            border-radius: 14px;
            overflow:hidden;
            box-shadow: 0 10px 22px rgba(15,23,42,.06);
        }
        .modern-table td{
            border:0;
            border-bottom:1px solid var(--line);
            padding: .7rem .9rem;
            text-align:left !important;
        }
        .modern-table td:last-child{
            border-bottom:0;
            background:#f9fafb;
        }
        .modern-table td[data-label]::before{
            content: attr(data-label);
            display:block;
            font-size:.78rem;
            font-weight:800;
            color: var(--muted);
            margin-bottom:.2rem;
            letter-spacing:.2px;
            text-transform: uppercase;
        }
        .actions-wrap{ justify-content:flex-start; }
    }
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h2 class="page-title">Manage Courses</h2>
            <div class="page-sub">Create, update, and remove courses linked to colleges.</div>
        </div>

        {{-- force right alignment --}}
        <div class="d-flex justify-content-end w-100 w-md-auto">
            <button type="button"
                    class="btn btn-bisu btn-bisu-primary shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createCourseModal">
                + Add Course
            </button>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-responsive" style="max-height: calc(100vh - 300px);">
            <table class="table modern-table mb-0" id="coursesTable">
                <thead class="sticky-top">
                    <tr>
                        <th style="width: 24%;">Course Name</th>
                        <th>Course Description</th>
                        <th style="width: 22%;">College</th>
                        <th class="text-center" style="width: 240px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($courses ?? [] as $course)
                    <tr>
                        <td data-label="Course Name" class="fw-semibold text-dark">
                            {{ $course->course_name }}
                        </td>

                        <td data-label="Description" class="text-secondary">
                            {{ $course->course_description ?? 'N/A' }}
                        </td>

                        <td data-label="College" class="text-secondary">
                            {{ $course->college->college_name ?? 'N/A' }}
                        </td>

                        <td data-label="Actions" class="text-center">
                            <div class="actions-wrap">

                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-outline"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCourseModal"
                                        data-id="{{ $course->id }}"
                                        data-name="{{ $course->course_name }}"
                                        data-description="{{ $course->course_description }}"
                                        data-college_id="{{ $course->college_id }}">
                                    ✏️ Edit
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-bisu btn-bisu-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseModal"
                                        data-id="{{ $course->id }}"
                                        data-name="{{ $course->course_name }}"
                                        data-description="{{ $course->course_description }}"
                                        data-college="{{ $course->college->college_name ?? 'N/A' }}">
                                    🗑️ Delete
                                </button>

                            </div>
                        </td>
                    </tr>
               @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            No courses found. Click <strong>+ Add Course</strong> to create one.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

{{-- =========================
    CREATE MODAL
========================= --}}
<div class="modal fade" id="createCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Add Course</h5>
            <div class="hint">Create a new course and assign it to a college.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('admin.courses.store') }}">
        @csrf

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="create_course_name">Course Name</label>
            <input type="text"
                   name="course_name"
                   id="create_course_name"
                   class="form-control"
                   placeholder="e.g. Bachelor of Science in Computer Science"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="create_course_description">Course Description</label>
            <textarea name="course_description"
                      id="create_course_description"
                      class="form-control"
                      rows="3"
                      placeholder="Optional"></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label" for="create_college_id">College</label>
            <select name="college_id" id="create_college_id" class="form-select" required>
                <option value="">Select College</option>
                @foreach(($colleges ?? []) as $college)
                    <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">✅ Add Course</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- =========================
    EDIT MODAL
========================= --}}
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0">Edit Course</h5>
            <div class="hint">Update course details and assigned college.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="editCourseForm" action="">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="edit_course_name">Course Name</label>
            <input type="text" name="course_name" id="edit_course_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label" for="edit_course_description">Course Description</label>
            <textarea name="course_description" id="edit_course_description" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label" for="edit_college_id">College</label>
            <select name="college_id" id="edit_college_id" class="form-select" required>
                <option value="">Select College</option>
                @foreach(($colleges ?? []) as $college)
                    <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-bisu btn-bisu-primary">💾 Save Changes</button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- =========================
    DELETE MODAL
========================= --}}
<div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-0" style="color:#b30000;">Confirm Deletion</h5>
            <div class="hint">This action cannot be undone.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" id="deleteCourseForm" action="">
        @csrf
        @method('DELETE')

        <div class="modal-body">
            <div class="danger-box">
                <div class="fw-bold mb-1" id="delete_course_name">Course</div>
                <div class="text-muted small mb-2" id="delete_course_desc">No description</div>
                <div class="text-muted small">
                    <strong>College:</strong> <span id="delete_course_college">N/A</span>
                </div>
            </div>

            <div class="mt-3 text-muted small">
                Deleting this course may affect students assigned to it.
            </div>
        </div>

        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-bisu btn-bisu-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger fw-bold" style="border-radius:14px; padding:.6rem 1rem;">
            Yes, Delete
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // EDIT MODAL FILL
    const editModal = document.getElementById('editCourseModal');
    const editForm  = document.getElementById('editCourseForm');

    editModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';
        const desc = btn.getAttribute('data-description') || '';
        const collegeId = btn.getAttribute('data-college_id') || '';

        editForm.action = `{{ url('/admin/courses') }}/${id}`;

        document.getElementById('edit_course_name').value = name;
        document.getElementById('edit_course_description').value = desc;

        const select = document.getElementById('edit_college_id');
        if(select){
            select.value = collegeId;
        }
    });

    // DELETE MODAL FILL
    const deleteModal = document.getElementById('deleteCourseModal');
    const deleteForm  = document.getElementById('deleteCourseForm');

    deleteModal?.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if(!btn) return;

        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || 'Course';
        const desc = btn.getAttribute('data-description') || 'No description provided.';
        const college = btn.getAttribute('data-college') || 'N/A';

        deleteForm.action = `{{ url('/admin/courses') }}/${id}`;

        document.getElementById('delete_course_name').textContent = name;
        document.getElementById('delete_course_desc').textContent = desc;
        document.getElementById('delete_course_college').textContent = college;
    });

});
</script>

@endsection