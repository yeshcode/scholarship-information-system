<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .title { text-align:center; font-weight:bold; margin-bottom:10px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:6px; text-align:center; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>
    <div class="title">
        SUMMARY OF SCHOLARSHIPS<br>
        Candijay Campus • AY {{ $academicYear ?? 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Scholarship</th>
                <th>1st Sem</th>
                <th>2nd Sem</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
                <tr>
                    <td style="text-align:left">{{ $r->scholarship_name }}</td>
                    <td>{{ $r->total_sem1 }}</td>
                    <td>{{ $r->total_sem2 }}</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align:right">TOTAL</th>
                <th>{{ $grandSem1 }}</th>
                <th>{{ $grandSem2 }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>