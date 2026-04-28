<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .report-wrap{
            width:100%;
        }

        .title {
            text-align:center;
            font-weight:bold;
            margin-bottom:8px;
            font-size:14px;
            letter-spacing:.3px;
            text-transform: uppercase;
        }

        .subtitle{
            text-align:center;
            font-size:12px;
            margin-bottom:12px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        th, td {
            border:1px solid #000;
            padding:6px;
        }

        th {
            background:#f2f2f2;
            text-align:center;
        }

        td {
            vertical-align: middle;
        }

        .text-left{ text-align:left; }
        .text-center{ text-align:center; }
        .text-right{ text-align:right; }

        tfoot th{
            font-weight:bold;
        }

        .meta-line{
            margin-top:8px;
            font-size:11px;
            text-align:right;
        }
    </style>
</head>

<body>
<div class="report-wrap">

    @include('coordinator.reports.pdf.partials.a4-header')

    <div class="title">
        SCHOLARSHIP SCHOLARS REPORT
    </div>

    <div class="subtitle">
        Candijay Campus • {{ $semester ? ($semester->term . ' • AY ' . $semester->academic_year) : 'N/A' }}
        @if($selectedScholarship)
            <br>{{ $selectedScholarship->scholarship_name }}
        @endif
    </div>

    @if($selectedScholarship)
        <div class="meta-line" style="text-align:left; margin-bottom:10px;">
            <strong>Total Scholars:</strong> {{ $selectedScholarship->scholars->count() }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:40%;">Name</th>
                    <th style="width:25%;">Course</th>
                    <th style="width:12%;">Year</th>
                    <th style="width:18%;">Sex</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedScholarship->scholars as $i => $scholar)
                    @php
                        $user = $scholar->user;
                        $yearLevel = $user?->yearLevel?->year_level_name ?? 'N/A';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-left">{{ $user?->lastname ?? '-' }}, {{ $user?->firstname ?? '-' }}</td>
                        <td class="text-left">{{ $user?->course?->course_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $yearLevel }}</td>
                        <td class="text-center">{{ $user?->sex ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @foreach($scholarships as $sch)
            <div style="margin-top:14px; margin-bottom:6px; font-weight:bold; font-size:12px;">{{ $sch->scholarship_name }} ({{ $sch->scholars->count() }} scholars)</div>
            @if($sch->scholars->isEmpty())
                <div style="margin-bottom:10px; font-size:11px; color:#555;">No scholars for this scholarship.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:6%;">#</th>
                            <th style="width:44%;">Name</th>
                            <th style="width:25%;">Course</th>
                            <th style="width:12%;">Year</th>
                            <th style="width:13%;">Sex</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sch->scholars as $i => $scholar)
                            @php
                                $user = $scholar->user;
                                $yearLevel = $user?->yearLevel?->year_level_name ?? 'N/A';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="text-left">{{ $user?->lastname ?? '-' }}, {{ $user?->firstname ?? '-' }}</td>
                                <td class="text-left">{{ $user?->course?->course_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $yearLevel }}</td>
                                <td class="text-center">{{ $user?->sex ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

</div>
</body>
</html>
