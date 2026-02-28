<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .title { text-align:center; font-weight:bold; margin-bottom:10px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:5px; }
        th { background:#f2f2f2; text-align:center; }
        td { vertical-align: top; }
    </style>
</head>
<body>
    <div class="title">
        LIST OF SCHOLARS AND GRANTEES<br>
        {{ $semester ? ($semester->term.' • '.$semester->academic_year) : 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Scholarship</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scholars as $i => $s)
                @php
                    $u = $s->user;
                    $en = $u?->enrollments?->first(); // filtered in controller
                    $yl = $en?->yearLevel?->year_level_name ?? $u?->yearLevel?->year_level_name ?? 'N/A';
                @endphp
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $u?->lastname }}, {{ $u?->firstname }}</td>
                    <td>{{ $u?->course?->course_name ?? 'N/A' }}</td>
                    <td style="text-align:center">{{ $yl }}</td>
                    <td>{{ $s->scholarship?->scholarship_name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>