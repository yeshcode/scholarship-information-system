{{-- resources/views/super-admin/users-bulk-upload.blade.php --}}
@php $fullWidth = true; @endphp
@extends('layouts.app')

@section('content')

<style>
    :root{
        --brand:#0b2e5e;
        --brand-2:#123f85;
        --muted:#6b7280;
        --bg:#f4f7fb;
        --line:#e5e7eb;
        --soft:#f8fafc;
        --success-soft:#eaf7ef;
        --danger-soft:#fdecec;
    }

    body{ background: var(--bg); }

    .page-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:1rem;
        flex-wrap:wrap;
        margin-bottom: 1rem;
    }
    .page-title{
        font-weight:900;
        letter-spacing:.2px;
        color:var(--brand);
        font-size:1.55rem;
        margin:0;
        line-height:1.15;
    }
    .page-sub{
        color:var(--muted);
        font-size:.92rem;
        margin-top:.25rem;
    }

    .upload-card{
        border: 1px solid var(--line);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(0,0,0,.06);
        overflow:hidden;
    }
    .upload-card .card-head{
        padding: 1.05rem 1.1rem;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }
    .upload-card .card-bodyy{
        padding: 1.1rem;
    }

    .hint-box{
        border: 1px dashed rgba(11,46,94,.25);
        background: #f6faff;
        border-radius: 14px;
        padding: .9rem 1rem;
        color: #1f2937;
        font-size: .92rem;
    }
    .hint-code{
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: .85rem;
        background: #eef2ff;
        border: 1px solid rgba(99,102,241,.18);
        padding: .12rem .45rem;
        border-radius: 999px;
        display:inline-block;
        margin-top:.35rem;
    }

    .alert-soft-success{
        background: var(--success-soft);
        border: 1px solid rgba(25,135,84,.25);
        color: #166534;
        border-radius: 14px;
        padding: .85rem 1rem;
        font-weight: 600;
    }
    .alert-soft-danger{
        background: var(--danger-soft);
        border: 1px solid rgba(220,53,69,.25);
        color: #991b1b;
        border-radius: 14px;
        padding: .85rem 1rem;
        font-weight: 600;
    }

    .form-label{
        font-weight: 800;
        color: #111827;
        font-size: .92rem;
    }
    .form-control{
        border-radius: 14px;
        border: 1px solid var(--line);
        padding: .75rem .9rem;
    }
    .form-control:focus{
        border-color: rgba(11,46,94,.35);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }
    .form-text{
        color: var(--muted);
    }

    .divider{
        border-top: 1px solid var(--line);
        margin: 1rem 0 0;
    }

    .btn-soft{
        background: var(--soft);
        border: 1px solid var(--line);
        font-weight: 800;
        border-radius: 14px;
        padding: .6rem .9rem;
    }
    .btn-soft:hover{ background:#eef2ff; }

    .btn-bisu{
        background: var(--brand);
        border: 1px solid rgba(11,46,94,.35);
        color: #fff;
        font-weight: 900;
        border-radius: 14px;
        padding: .65rem 1.05rem;
    }
    .btn-bisu:hover{ background: var(--brand-2); color:#fff; }

    .btn-success-soft{
        background: #198754;
        border: 1px solid rgba(25,135,84,.25);
        color: #fff;
        font-weight: 900;
        border-radius: 14px;
        padding: .65rem 1.05rem;
    }
    .btn-success-soft:hover{ filter: brightness(.95); color:#fff; }

    .back-link{
        text-decoration:none;
        font-weight:800;
        color: var(--brand);
    }
    .back-link:hover{ color: var(--brand-2); text-decoration: underline; }

    .step-pill{
        display:inline-flex;
        align-items:center;
        gap:.5rem;
        padding:.25rem .6rem;
        border-radius: 999px;
        border:1px solid var(--line);
        background:#fff;
        font-size:.82rem;
        color:#374151;
        font-weight:800;
    }
    .step-dot{
        width:.55rem; height:.55rem; border-radius:999px;
        background: #22c55e;
        box-shadow: 0 0 0 .15rem rgba(34,197,94,.15);
    }
</style>

<div class="container-fluid py-3 py-md-4">

    <div class="page-head">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="page-title">Bulk Upload Students</h1>
                <span class="step-pill"><span class="step-dot"></span>Step 1: Upload File</span>
            </div>
            <div class="page-sub">
                Upload a CSV/TXT file to register multiple students at once.
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="btn btn-soft">
                ← Back to Users
            </a>
        </div>
    </div>

    <div class="upload-card">
        <div class="card-head">
            <div class="fw-bold" style="color:var(--brand);">Upload File</div>
            <div class="small text-muted">Make sure your file follows the required headers.</div>
        </div>

        <div class="card-bodyy">

           
            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-soft-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-soft-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-soft-danger mb-3">
                    <div class="fw-bold mb-1">Please fix the following:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.users.bulk-upload.preview') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label">CSV / TXT File</label>
                    <input type="file"
                           name="file"
                           id="file"
                           class="form-control"
                           accept=".csv,.txt"
                           required>
                    <div class="form-text">
                        Allowed file types: <span class="fw-bold">.csv</span> and <span class="fw-bold">.txt</span>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="d-flex flex-wrap justify-content-end gap-2 pt-3">
                    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                       class="btn btn-soft">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-success-soft">
                        Upload & Preview
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection