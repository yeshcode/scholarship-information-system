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
        --warning-soft:#fff6e5;
    }

    body{ background: var(--bg); }

    /* Typography */
    .ui-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
    }
    .page-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:1rem;
        flex-wrap:wrap;
        margin-bottom: 1.1rem;
    }
    .page-title{
        font-weight: 900;
        letter-spacing: .2px;
        color: var(--brand);
        font-size: clamp(1.35rem, 1.1rem + 1vw, 1.75rem);
        margin:0;
        line-height:1.15;
    }
    .page-sub{
        color: var(--muted);
        font-size: .95rem;
        margin-top: .35rem;
        max-width: 62ch;
    }

    /* Badge / Step */
    .step-pill{
        display:inline-flex;
        align-items:center;
        gap:.55rem;
        padding:.32rem .75rem;
        border-radius: 999px;
        border:1px solid var(--line);
        background:#fff;
        font-size:.82rem;
        color:#374151;
        font-weight:900;
    }
    .step-dot{
        width:.55rem; height:.55rem; border-radius:999px;
        background: #22c55e;
        box-shadow: 0 0 0 .15rem rgba(34,197,94,.15);
    }

    /* Card */
    .upload-card{
        border: 1px solid rgba(229,231,235,.9);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(2, 6, 23, .06);
        overflow: hidden;
    }
    .upload-card .card-head{
        padding: 1.15rem 1.2rem;
        border-bottom: 1px solid var(--line);
        background:
            radial-gradient(1200px 200px at 0% 0%, rgba(11,46,94,.08), transparent 60%),
            linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }
    .card-title{
        margin:0;
        font-weight: 900;
        color: var(--brand);
        font-size: 1.05rem;
        letter-spacing:.2px;
    }
    .card-sub{
        margin-top:.2rem;
        color: var(--muted);
        font-size: .9rem;
    }
    .upload-card .card-bodyy{
        padding: 1.15rem 1.2rem;
    }

    /* Alerts */
    .alert-soft{
        border-radius: 14px;
        padding: .9rem 1rem;
        font-weight: 700;
        border: 1px solid transparent;
    }
    .alert-soft-success{
        background: var(--success-soft);
        border-color: rgba(25,135,84,.25);
        color: #166534;
    }
    .alert-soft-danger{
        background: var(--danger-soft);
        border-color: rgba(220,53,69,.25);
        color: #991b1b;
    }

    /* Upload box */
    .upload-box{
        border: 1px dashed rgba(11,46,94,.35);
        background: #f6faff;
        border-radius: 16px;
        padding: 1rem;
    }
    .upload-box .upload-help{
        color:#334155;
        font-size:.92rem;
        margin:0 0 .6rem;
        font-weight:800;
    }

    /* Form */
    .form-label{
        font-weight: 900;
        color: #0f172a;
        font-size: .92rem;
        margin-bottom: .4rem;
    }
    .form-control{
        border-radius: 14px;
        border: 1px solid var(--line);
        padding: .78rem .95rem;
        font-size: .95rem;
    }
    .form-control:focus{
        border-color: rgba(11,46,94,.40);
        box-shadow: 0 0 0 .2rem rgba(11,46,94,.12);
    }
    .form-text{
        color: var(--muted);
        font-size: .88rem;
        margin-top: .35rem;
    }

    .divider{
        border-top: 1px solid var(--line);
        margin: 1.05rem 0 0;
    }

    /* Buttons */
    .btn{
        border-radius: 14px !important;
        font-weight: 900;
        padding: .65rem 1.05rem;
        letter-spacing: .2px;
    }

    .btn-soft{
        background: var(--soft);
        border: 1px solid var(--line);
        color: #0f172a;
    }
    .btn-soft:hover{
        background: #eef2ff;
        border-color: rgba(11,46,94,.18);
    }

    .btn-bisu{
        background: var(--brand);
        border: 1px solid rgba(11,46,94,.35);
        color: #fff;
        box-shadow: 0 10px 20px rgba(11,46,94,.18);
    }
    .btn-bisu:hover{
        background: var(--brand-2);
        color:#fff;
        box-shadow: 0 12px 24px rgba(11,46,94,.22);
        transform: translateY(-1px);
    }

    .btn-primary-modern{
        background: var(--brand);
        border: 1px solid rgba(11,46,94,.35);
        color: #fff;
        font-weight: 500; /* not bold */
        padding: .6rem 1rem;
        border-radius: 12px;
        font-size: .95rem;
        letter-spacing: .2px;
        transition: all .2s ease;
    }

    .btn-primary-modern:hover{
        background: var(--brand-2);
        color: #fff;
    }

    .btn-primary-modern:active{
        transform: scale(.98);
    }

    /* Small helper for icons (no external libs needed) */
    .btn-ic{
        display:inline-flex;
        align-items:center;
        gap:.5rem;
        white-space: nowrap;
    }
</style>

<div class="container-fluid py-3 py-md-4 ui-wrap">

    <div class="page-head">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="page-title">Bulk Upload Students</h1>
                <span class="step-pill"><span class="step-dot"></span>Step 1: Upload File</span>
            </div>
            {{-- <div class="page-sub">
                Upload a CSV/TXT file to register multiple students at once.
                After upload, you’ll preview the data before saving.
            </div> --}}
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="btn btn-soft btn-ic">
                <span>←</span> <span>Back to Users</span>
            </a>
        </div>
    </div>

    <div class="upload-card">
        <div class="card-head">
            <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                <div>
                    <p class="card-title">Upload File</p>
                    {{-- <div class="card-sub">Make sure your file follows the required headers.</div> --}}
                </div>

                {{-- optional: a subtle action button if you later add template download --}}
                {{-- <a href="#" class="btn btn-bisu btn-sm btn-ic">⬇ Download Template</a> --}}
            </div>
        </div>

        <div class="card-bodyy">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-soft alert-soft-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-soft alert-soft-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-soft alert-soft-danger mb-3">
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

                <div class="upload-box mb-3">
                    <p class="upload-help mb-2">Choose your file</p>

                    {{-- <label for="file" class="form-label">Excel / CSV File</label> --}}
                            <input type="file"
                                name="file"
                                id="file"
                                class="form-control"
                                accept=".xlsx,.xls,.csv"
                                required>

                            {{-- <div class="form-text">
                                Allowed file types: <strong>.xlsx</strong>, <strong>.xls</strong>, and <strong>.csv</strong>
                            </div> --}}
                </div>

                <div class="divider"></div>

                <div class="d-flex flex-wrap justify-content-end gap-2 pt-3">
                    <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}"
                       class="btn btn-soft btn-ic">
                        <span>✕</span> <span>Cancel</span>
                    </a>

                    <button type="submit" class="btn btn-primary-modern">
                        Upload
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection