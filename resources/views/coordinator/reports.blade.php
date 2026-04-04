@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --bisu-blue:#0b2e5e;
        --bisu-blue-2:#174a8b;
        --line:#e5e7eb;
        --soft:#f8fafc;
    }

    .reports-wrap{
        max-width: 920px;
        margin: 0 auto;
    }

    .reports-title{
        color: var(--bisu-blue);
        font-weight: 800;
        margin-bottom: 4px;
    }

    .reports-subtitle{
        color:#6b7280;
        font-size:.95rem;
    }

    .semester-box{
        background:#fff;
        border:1px solid var(--line);
        border-radius:12px;
        padding:14px 16px;
        margin-top:18px;
        margin-bottom:18px;
    }

    .semester-label{
        font-size:.82rem;
        color:#6b7280;
        margin-bottom:6px;
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .semester-value{
        color:var(--bisu-blue);
        font-weight:700;
    }

    .report-list{
        background:#fff;
        border:1px solid var(--line);
        border-radius:14px;
        overflow:hidden;
        box-shadow:0 4px 14px rgba(15,23,42,.05);
    }

    .report-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:18px;
        padding:18px 20px;
        border-bottom:1px solid var(--line);
    }

    .report-item:last-child{
        border-bottom:none;
    }

    .report-item:hover{
        background:#fbfdff;
    }

    .report-left{
        min-width:0;
    }

    .report-name{
        color:var(--bisu-blue);
        font-weight:700;
        margin-bottom:4px;
    }

    .report-desc{
        color:#6b7280;
        font-size:.92rem;
        margin:0;
    }

    .report-actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        justify-content:flex-end;
    }

    .btn-bisu{
        background:var(--bisu-blue);
        border-color:var(--bisu-blue);
        color:#fff;
        font-weight:600;
    }

    .btn-bisu:hover{
        background:var(--bisu-blue-2);
        border-color:var(--bisu-blue-2);
        color:#fff;
    }

    .tip-text{
        margin-top:12px;
        color:#6b7280;
        font-size:.9rem;
    }

    @media (max-width: 768px){
        .report-item{
            flex-direction:column;
            align-items:flex-start;
        }

        .report-actions{
            justify-content:flex-start;
        }
    }
</style>

<div class="reports-wrap py-4">
    <div>
        <h3 class="reports-title">Reports</h3>
        <div class="reports-subtitle">Generate official scholarship reports per semester.</div>
    </div>

    {{-- <div class="semester-box">
        <div class="semester-label">Active Semester</div>
        <div class="semester-value">
            {{ $activeSemester ? ($activeSemester->term . ' • ' . $activeSemester->academic_year) : 'No active semester set' }}
        </div>
    </div> --}}

    <div class="report-list">
        {{-- Summary of Scholarships --}}
        <div class="report-item">
            <div class="report-left">
                <div class="report-name">Summary of Scholarships</div>
                <p class="report-desc">
                    Official semester summary of scholarships and total scholars.
                </p>
            </div>

            <div class="report-actions">
                <a class="btn btn-bisu btn-sm"
                   href="{{ route('coordinator.reports.summary-of-scholarships', ['semester_id' => $activeSemesterId]) }}">
                    View Report
                </a>

                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('coordinator.reports.summary-of-scholarships.pdf', ['semester_id' => $activeSemesterId]) }}">
                    Download PDF
                </a>
            </div>
        </div>

        {{-- List of Scholars and Grantees --}}
        <div class="report-item">
            <div class="report-left">
                <div class="report-name">List of Scholars and Grantees</div>
                <p class="report-desc">
                    Official list of all scholars for the selected semester.
                </p>
            </div>

            <div class="report-actions">
                <a class="btn btn-bisu btn-sm"
                   href="{{ route('coordinator.reports.list-of-scholars', ['semester_id' => $activeSemesterId]) }}">
                    View Report
                </a>

                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('coordinator.reports.list-of-scholars.pdf', ['semester_id' => $activeSemesterId]) }}">
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    {{-- <div class="tip-text">
        Tip: Open the report first, then click <strong>Print / Save as PDF</strong> so the preview and printed format match.
    </div> --}}
</div>
@endsection