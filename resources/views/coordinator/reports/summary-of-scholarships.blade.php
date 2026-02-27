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
    text-align:center;
}
.table-report td{
    font-size: 11px;
    vertical-align: middle;
}
.meta-line{ font-size:12px; margin-bottom:10px; }
</style>

<div class="container py-3 no-print">
    <a href="{{ route('coordinator.reports') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    <button class="btn btn-sm btn-primary" style="background:#0b2e5e;border-color:#0b2e5e;" onclick="window.print()">
        Print / Save as PDF
    </button>
</div>

<div class="report-wrap">
    @include('coordinator.reports.partials.a4-header')

    <div class="report-title">SUMMARY OF SCHOLARSHIPS</div>

    {{-- DOCX-style campus + academic year line --}}
    <div class="text-center" style="margin-top:2px; margin-bottom:10px; line-height:1.2;">
        <div style="font-weight:600;">Candijay Campus</div>
        <div style="text-decoration: underline; text-underline-offset: 3px;">
            1st and 2nd Semester, {{ $academicYear ? ('AY ' . $academicYear) : 'AY not set' }}
        </div>
    </div>

    <table class="table table-bordered table-report">
        <thead>
            <tr>
                <th rowspan="2" style="width:50px;">No.</th>
                <th rowspan="2">Scholarship Program</th>
                <th colspan="2" style="width:220px;">Number of Scholars</th>
            </tr>
            <tr>
                <th style="width:110px;">
                    {{ $sem1?->term ?? '1st Sem' }}
                </th>
                <th style="width:110px;">
                    {{ $sem2?->term ?? '2nd Sem' }}
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $r->scholarship_name }}</td>
                    <td class="text-end">{{ (int) $r->total_sem1 }}</td>
                    <td class="text-end">{{ (int) $r->total_sem2 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No data found for this academic year.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Grand Total</th>
                <th class="text-end">{{ $grandSem1 }}</th>
                <th class="text-end">{{ $grandSem2 }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="meta-line text-end" style="margin-top:6px;">
        <strong>Overall Total:</strong> {{ (int)$grandSem1 + (int)$grandSem2 }}
    </div>
</div>
@endsection