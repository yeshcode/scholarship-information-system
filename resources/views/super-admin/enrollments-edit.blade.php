@extends('layouts.app')

@section('content')
<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --muted:#6b7280;
        --bg:#f4f7fb;
        --line:#e5e7eb;
        --soft:#f8fafc;
    }

    body{ background: var(--bg); }

    .page-wrap{
        max-width: 900px;
        margin: 0 auto;
    }

    .page-title{
        color: var(--bisu-blue);
        font-weight: 800;
        letter-spacing: .2px;
        margin: 0;
    }

    .subtext{
        color: var(--muted);
        font-size: .92rem;
        margin-top: .35rem;
    }

    .card-shell{
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
        background: #fff;
    }

    .card-head{
        background: linear-gradient(90deg, var(--bisu-blue), var(--bisu-blue-2));
        color:#fff;
        padding: 14px 18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .head-badge{
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        color:#fff;
        font-size: .78rem;
        padding: 6px 10px;
        border-radius: 999px;
        display:flex;
        align-items:center;
        gap: 8px;
        white-space: nowrap;
    }

    .form-label{
        font-size: .85rem;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: .35rem;
    }

    .readonly-field{
        background: var(--soft) !important;
        border-color: var(--line) !important;
        color: #111827;
    }

    .readonly-hint{
        font-size: .78rem;
        color: var(--muted);
        margin-top: .35rem;
        display:flex;
        align-items:center;
        gap: 8px;
    }

    .section-divider{
        border-top: 1px dashed var(--line);
        margin: 14px 0 10px;
    }

    .btn-soft{
        background: #eef2ff;
        border: 1px solid #dbe2ff;
        color: var(--bisu-blue);
    }
    .btn-soft:hover{
        background: #e3eaff;
        border-color:#cfd9ff;
        color: var(--bisu-blue);
    }

    .btn-bisu{
        background: var(--bisu-blue);
        border-color: var(--bisu-blue);
        color:#fff;
    }
    .btn-bisu:hover{
        background: var(--bisu-blue-2);
        border-color: var(--bisu-blue-2);
        color:#fff;
    }

    /* Make selects/inputs look a bit more modern */
    .form-control, .form-select{
        border-radius: 10px;
        border-color: var(--line);
        padding-top: .55rem;
        padding-bottom: .55rem;
    }
</style>

<div class="container py-3 page-wrap">

    {{-- Top Bar --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
            <h2 class="page-title">Update Enrollment Status</h2>
            {{-- <div class="subtext">
                Student details are locked for accuracy. You can only update the enrollment status.
            </div> --}}
        </div>

        <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="btn btn-soft btn-sm">
            ← Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-shell">
        {{-- Card Header --}}
        <div class="card-head">
            <div class="fw-semibold">Enrollment Record</div>

            {{-- ✅ Read-only sign --}}
            <div class="head-badge" title="Student information is read-only on this page.">
                <span aria-hidden="true"></span>
                Student info is not editable
            </div>
        </div>

        <div class="card-body p-3 p-md-4">

            <form method="POST" action="{{ route('admin.enrollments.update', $enrollment->id) }}">
                @csrf
                @method('PUT')

                {{-- Student (read-only) --}}
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $enrollment->user->lastname ?? '' }}, {{ $enrollment->user->firstname ?? '' }} ({{ $enrollment->user->student_id ?? 'N/A' }})"
                           readonly>
                    <div class="readonly-hint">
                        <span aria-hidden="true"></span>
                    </div>
                    <input type="hidden" name="user_id" value="{{ $enrollment->user_id }}">
                </div>

                {{-- Semester (read-only) --}}
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $enrollment->semester->term ?? 'N/A' }} {{ $enrollment->semester->academic_year ?? '' }}"
                           readonly>
                    <div class="readonly-hint">
                        <span aria-hidden="true"></span>
                    </div>
                    <input type="hidden" name="semester_id" value="{{ $enrollment->semester_id }}">
                </div>

                <div class="section-divider"></div>

                {{-- College (read-only) --}}
                <div class="mb-3">
                    <label class="form-label">College</label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $enrollment->user->college->college_name ?? 'N/A' }}"
                           readonly>
                    <div class="readonly-hint">
                        <span aria-hidden="true"></span>
                    </div>
                </div>

                {{-- Course (read-only) --}}
                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $enrollment->user->course->course_name ?? 'N/A' }}"
                           readonly>
                    <div class="readonly-hint">
                        <span aria-hidden="true"></span>
                    </div>
                    {{-- keep course_id submitted if your update validation requires it --}}
                    <input type="hidden" name="course_id" value="{{ $enrollment->course_id }}">
                </div>

                {{-- Year Level (read-only) --}}
                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <input type="text"
                           class="form-control readonly-field"
                           value="{{ $enrollment->user->yearLevel->year_level_name ?? 'N/A' }}"
                           readonly>
                    <div class="readonly-hint">
                        <span aria-hidden="true"></span>
                    </div>
                </div>

                <div class="section-divider"></div>

                {{-- ✅ Status (editable) --}}
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>

                    {{-- small visual cue that this is the only editable field --}}
                    <div class="alert alert-primary py-2 small mb-2" style="border-radius: 10px;">
                        <strong>Editable:</strong> Only the status can be changed on this page.
                    </div>

                    <select name="status" class="form-select" required>
                        <option value="enrolled" {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="dropped" {{ $enrollment->status === 'dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-bisu">
                        Update Status
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection