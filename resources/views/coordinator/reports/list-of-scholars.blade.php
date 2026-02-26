@extends('layouts.app')

@section('content')
<style>
@media print {
    body { background:#fff !important; }
    .no-print { display:none !important; }
}
.report-wrap{
    max-width: 210mm;
    margin: 0 auto;
    padding: 12mm;
    background: #fff;
}
@page { size: A4; margin: 12mm; }

.hr-line{ border:0; border-top:2px solid #000; margin:10px 0 14px; }

.report-title{
    text-align:center;
    font-weight:700;
    margin: 6px 0 10px;
    letter-spacing:.4px;
    text-transform: uppercase;
}

.table-report th{
    background:#f2f2f2;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing:.4px;
    vertical-align: middle;
    text-align: center;
}
.table-report td{
    font-size: 11px;
    vertical-align: middle;
}
.meta-line{ font-size:12px; margin-bottom:10px; }

.footer-block{
    margin-top: 26px;
    font-size: 12px;
}
.footer-row{
    display:flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 18px;
}
.footer-sign{
    width: 55%;
    text-align: center;
}
.footer-date{
    width: 30%;
    text-align: center;
}
.footer-line{
    border-top: 1px solid #000;
    margin-top: 26px;
}
.doc-code{
    margin-top: 8px;
    font-size: 11px;
    text-align: right;
}
</style>

<div class="container py-3 no-print">
    <a href="{{ route('coordinator.reports') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    <button class="btn btn-sm btn-primary" style="background:#0b2e5e;border-color:#0b2e5e;" onclick="window.print()">
        Print / Save as PDF
    </button>
</div>

<div class="report-wrap">
    @include('coordinator.reports.partials.a4-header')

    <div class="report-title">LIST OF SCHOLARS AND GRANTEES</div>

        <div class="text-center" style="margin-top:2px; margin-bottom:10px; line-height:1.2;">
            <div style="font-weight:600;">Candijay Campus</div>
            <div style="text-decoration: underline; text-underline-offset: 3px;">
                {{ $semester ? ($semester->term . ', AY ' . $semester->academic_year) : 'Semester not set' }}
            </div>
        </div>

        <div class="meta-line">
            <span><strong>Total:</strong> {{ $scholars->count() }}</span>
    </div>

    <table class="table table-bordered table-report">
        <thead>
            <tr>
                <th rowspan="2" style="width:38px;">No.</th>
                <th rowspan="2" style="width:160px;">Scholarship Program</th>

                <th colspan="3">Name</th>

                <th rowspan="2" style="width:55px;">Sex</th>
                <th rowspan="2" style="width:140px;">Course</th>
                <th rowspan="2" style="width:85px;">Year Level</th>
            </tr>
            <tr>
                <th style="width:120px;">Last</th>
                <th style="width:120px;">First</th>
                <th style="width:45px;">MI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scholars as $i => $s)
                @php
                    $u = $s->user;

                    // Find enrollment for the selected semester
                    $en = $u?->enrollments?->firstWhere('semester_id', $semesterId)
                        ?? $u?->enrollments?->first();

                    // ✅ FIX: use the correct YearLevel column name
                    $yearLevelLabel =
                        $en?->yearLevel?->year_level_name
                        ?? $u?->yearLevel?->year_level_name
                        ?? '-';

                    $sex = $u?->sex ?? '-';

                    $miRaw = $u?->middlename ?? '';
                    $mi = $miRaw ? strtoupper(mb_substr($miRaw, 0, 1)) : '';
                @endphp

                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $s->scholarship->scholarship_name ?? '-' }}</td>

                    <td>{{ $u->lastname ?? '-' }}</td>
                    <td>{{ $u->firstname ?? '-' }}</td>
                    <td class="text-center">{{ $mi }}</td>

                    <td class="text-center">{{ $sex }}</td>
                    <td>{{ $u->course->course_name ?? '-' }}</td>
                    <td class="text-center">{{ $yearLevelLabel }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No scholars found for this semester.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer like DOCX --}}
    <div class="footer-block">
        <div class="footer-row">
            <div class="footer-sign">
                <div class="footer-line"></div>
                <div>Admission &amp; Scholarship Director</div>
            </div>

            <div class="footer-date">
                <div class="footer-line"></div>
                <div>Date</div>
            </div>
        </div>

        <div class="doc-code">
            F-SAS-ADS-007 &nbsp; | &nbsp; Rev. 2 &nbsp; | &nbsp; 07/01/24 &nbsp; | &nbsp; Page 1 of 1
        </div>
    </div>
</div>
@endsection