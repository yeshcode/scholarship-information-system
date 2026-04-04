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
            margin-bottom:10px;
            font-size:14px;
            letter-spacing:.3px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        th, td {
            border:1px solid #000;
            padding:5px;
        }

        th {
            background:#f2f2f2;
            text-align:center;
        }

        td {
            vertical-align: middle;
        }

        .text-center{ text-align:center; }
    </style>
</head>

<body>
<div class="report-wrap">

    {{-- ✅ HEADER HERE --}}
    @include('coordinator.reports.pdf.partials.a4-header')

    {{-- TITLE --}}
    <div class="title">
        LIST OF SCHOLARS AND GRANTEES<br>
        {{ $semester ? ($semester->term.' • '.$semester->academic_year) : 'N/A' }}
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:30%">Name</th>
                <th style="width:25%">Course</th>
                <th style="width:15%">Year Level</th>
                <th style="width:25%">Scholarship</th>
            </tr>
        </thead>

        <tbody>
            @foreach($scholars as $i => $s)
                @php
                    $u = $s->user;
                    $en = $u?->enrollments?->first();
                    $yl = $en?->yearLevel?->year_level_name ?? $u?->yearLevel?->year_level_name ?? 'N/A';
                @endphp

                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $u?->lastname }}, {{ $u?->firstname }}</td>
                    <td>{{ $u?->course?->course_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $yl }}</td>
                    <td>{{ $s->scholarship?->scholarship_name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</body>
</html>