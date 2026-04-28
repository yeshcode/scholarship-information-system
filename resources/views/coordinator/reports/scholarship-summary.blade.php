@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --bisu-blue:#0b2e5e;
        --line:#d1d5db;
        --paper-shadow:0 10px 25px rgba(0,0,0,.08);
    }

    body{
        background:#f3f4f6;
    }

    .no-print{
        margin-bottom: 18px;
    }

    .report-actions{
        max-width: 210mm;
        margin: 0 auto 16px auto;
        display:flex;
        gap:10px;
        align-items:center;
    }

    .btn-bisu{
        background:var(--bisu-blue);
        border-color:var(--bisu-blue);
        color:#fff;
        font-weight:600;
    }

    .btn-bisu:hover{
        background:#174a8b;
        border-color:#174a8b;
        color:#fff;
    }

    .report-wrap{
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 24px auto;
        padding: 12mm;
        background: #fff;
        box-shadow: var(--paper-shadow);
    }

    @page{
        size: A4;
        margin: 12mm;
    }

    .hr-line{
        border:0;
        border-top:2px solid #000;
        margin:10px 0 14px;
    }

    .report-title{
        text-align:center;
        font-weight:700;
        margin: 6px 0 10px;
        letter-spacing:.4px;
        text-transform: uppercase;
        font-size: 16px;
    }

    .report-subtitle{
        text-align:center;
        margin-top:2px;
        margin-bottom:10px;
        line-height:1.2;
        font-size:13px;
    }

    .report-subtitle .campus{
        font-weight:600;
    }

    .report-subtitle .ay{
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .table-report{
        width:100%;
        border-collapse:collapse;
    }

    .table-report th,
    .table-report td{
        border:1px solid #000;
        padding:6px 8px;
    }

    .table-report th{
        background:#f2f2f2 !important;
        font-size:11px;
        text-transform:uppercase;
        letter-spacing:.4px;
        vertical-align:middle;
        text-align:center;
    }

    .table-report td{
        font-size:11px;
        vertical-align:middle;
    }

    .meta-line{
        font-size:12px;
        margin-top:8px;
        text-align:right;
    }

    @media print{
        body{
            background:#fff !important;
        }

        .no-print,
        .sidebar,
        .navbar,
        .main-header,
        .app-header,
        .menu,
        .topbar,
        .footer,
        aside,
        nav{
            display:none !important;
        }

        .content-wrapper,
        .main-content,
        .container,
        .container-fluid,
        .page-content{
            margin:0 !important;
            padding:0 !important;
            width:100% !important;
            max-width:100% !important;
        }

        .report-wrap{
            width:100% !important;
            min-height:auto !important;
            margin:0 !important;
            padding:0 !important;
            box-shadow:none !important;
        }
    }
</style>

<div class="no-print">
    <div class="report-actions">
        <a href="{{ route('coordinator.reports') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        <button class="btn btn-sm btn-bisu" onclick="window.print()">Print</button>
    </div>
</div>

<div class="report-wrap">
    @include('coordinator.reports.partials.a4-header')

    <div class="report-title">SCHOLARSHIP SCHOLARS REPORT</div>

    <div class="report-subtitle">
        <div class="campus">Candijay Campus</div>
        <div class="ay">
            {{ $semester ? ($semester->term . ' • AY ' . $semester->academic_year) : 'Academic Year not set' }}
        </div>
    </div>

    <form method="GET" action="{{ route('coordinator.reports.scholarship-summary') }}">
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">

        <div class="mb-3" style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end;">
            <div style="flex:1; min-width:230px;">
                <label class="form-label" style="font-weight:600;">Choose Scholarship</label>
                <select name="scholarship_id" class="form-select form-select-sm">
                    <option value="">All Scholarships</option>
                    @foreach($scholarships as $sch)
                        <option value="{{ $sch->id }}" @selected($selectedScholarship && $selectedScholarship->id == $sch->id)>{{ $sch->scholarship_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-bisu btn-sm">Show Report</button>
            </div>
            <div style="margin-left:auto;">
                <a href="{{ route('coordinator.reports.scholarship-summary.pdf', ['semester_id' => $semesterId, 'scholarship_id' => $selectedScholarship?->id]) }}" class="btn btn-outline-secondary btn-sm">
                    Download PDF
                </a>
            </div>
        </div>
    </form>

    @if($selectedScholarship)
        <div class="meta-line" style="margin-bottom:12px;">
            <strong>Scholarship:</strong> {{ $selectedScholarship->scholarship_name }}
            &nbsp;&bull;&nbsp;
            <strong>Total Scholars:</strong> {{ $selectedScholarship->scholars->count() }}
        </div>

        @if($selectedScholarship->scholars->isEmpty())
            <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px;">No scholars found for this scholarship.</div>
        @else
            <table class="table-report">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Name</th>
                        <th style="width:100px;">Sex</th>
                        <th style="width:160px;">Course</th>
                        <th style="width:90px;">Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedScholarship->scholars as $i => $scholar)
                        @php
                            $user = $scholar->user;
                            $yearLevel = $user?->yearLevel?->year_level_name ?? ($scholar->scholarshipBatch?->semester?->term ? 'N/A' : 'N/A');
                        @endphp
                        <tr>
                            <td style="text-align:center;">{{ $i + 1 }}</td>
                            <td>{{ $user?->lastname ?? '-' }}, {{ $user?->firstname ?? '-' }}</td>
                            <td style="text-align:center;">{{ $user?->sex ?? '-' }}</td>
                            <td>{{ $user?->course?->course_name ?? '-' }}</td>
                            <td style="text-align:center;">{{ $yearLevel }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        @foreach($scholarships as $sch)
            <div style="margin-top:18px; margin-bottom:8px; font-weight:700;">
                {{ $sch->scholarship_name }} ({{ $sch->scholars->count() }} scholars)
            </div>

            @if($sch->scholars->isEmpty())
                <div style="padding:12px 14px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:8px; color:#6b7280;">No scholars for this scholarship.</div>
            @else
                <table class="table-report" style="margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>Name</th>
                            <th style="width:100px;">Sex</th>
                            <th style="width:160px;">Course</th>
                            <th style="width:90px;">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sch->scholars as $i => $scholar)
                            @php
                                $user = $scholar->user;
                                $yearLevel = $user?->yearLevel?->year_level_name ?? 'N/A';
                            @endphp
                            <tr>
                                <td style="text-align:center;">{{ $i + 1 }}</td>
                                <td>{{ $user?->lastname ?? '-' }}, {{ $user?->firstname ?? '-' }}</td>
                                <td style="text-align:center;">{{ $user?->sex ?? '-' }}</td>
                                <td>{{ $user?->course?->course_name ?? '-' }}</td>
                                <td style="text-align:center;">{{ $yearLevel }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif
</div>
@endsection
