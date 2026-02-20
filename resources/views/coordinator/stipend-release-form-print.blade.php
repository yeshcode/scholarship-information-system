<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stipend Release Form</title>
    <style>
        body{ font-family: Arial, sans-serif; font-size: 12px; color:#111; }
        .meta{ margin-bottom: 10px; }
        table{ width:100%; border-collapse: collapse; }
        th, td{ border:1px solid #000; padding:6px; }
        th{ background:#f2f2f2; text-transform:uppercase; font-size:11px; letter-spacing:.4px; }
        .btns{ margin: 12px 0; }
        @media print { .btns{ display:none; } }
    </style>
</head>
<body>

@php
    $batch = $release->scholarshipBatch;
    $schName = $batch?->scholarship?->scholarship_name ?? 'N/A';
    $batchLabel = $batch ? ('Batch ' . $batch->batch_number) : 'N/A';
    $semLabel = $release->semester
        ? (($release->semester->term ?? $release->semester->semester_name) . ' ' . $release->semester->academic_year)
        : 'N/A';
@endphp

<div class="btns">
    <button onclick="window.print()">Print</button>
</div>

<div class="meta">
    <div><b>Scholarship:</b> {{ $schName }}</div>
    <div><b>Batch:</b> {{ $batchLabel }}</div>
    <div><b>Release Semester:</b> {{ $semLabel }}</div>
    <div><b>Title:</b> {{ $release->title }}</div>
    <div><b>Amount:</b> ₱ {{ number_format((float)$release->amount, 2) }}</div>
</div>

<table>
    <thead>
        <tr>
            @foreach($columns as $c)
                <th>{{ $c->label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($scholars as $s)
            @php $u = $s->user; @endphp
            <tr>
                @foreach($columns as $c)
                    @php
                        $val = match($c->key) {
                            'firstname'  => $u?->firstname ?? '',
                            'middlename' => $u?->middlename ?? '',
                            'lastname'   => $u?->lastname ?? '',
                            'year_level' => $u?->yearLevel?->year_level ?? $u?->year_level ?? '',
                            'course'     => $u?->course?->course_name ?? '',
                            'college'    => $u?->college?->college_name ?? '',
                            'student_id' => $u?->student_id ?? '',
                            'amount'     => number_format((float)$release->amount, 2),
                            'remarks'    => '',
                            'printed_name'  => '',
                            'date_received' => '',
                            'signature'  => '',
                            default      => '',
                        };
                    @endphp
                    <td>{{ $val }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>