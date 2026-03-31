<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Form</title>
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; }
        .btns { margin-bottom: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .small { font-size: 10px; }
        .header-wrap { margin-bottom: 10px; }
        .logo-row {
            width: auto;
            margin: 0 auto; /* centers the whole header block */
            border-collapse: collapse;
        }

        .logo-row td {
            border: none;
            vertical-align: middle;
        }

        .logo-left,
        .logo-right {
            width: auto;
            text-align: center;
            padding: 0 10px; /* controls spacing between logo and text */
        }


        .logo-left {
            width: 100px;
            text-align: right;
            padding-right: 15px;
        }

        .logo-right {
            width: 100px;
            text-align: left;
            padding-left: 15px;
        }

        .logo-left img,
        .logo-right img {
            max-width: 65px;
            max-height: 65px;
        }

        .logo-row td:nth-child(2) {
            padding: 0 10px;
            text-align: center;
        }
        .main-title { font-size: 16px; font-weight: bold; }
        .sub-title { font-size: 12px; }
        .payroll-title { font-size: 15px; font-weight: bold; margin-top: 4px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }
        th {
            text-align: center;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        .signature-lines {
            margin-top: 20px;
            width: 100%;
        }
        .signature-lines td {
            border: none;
            vertical-align: top;
            text-align: center;
            padding-top: 20px;
        }
        @media print {
            .btns { display: none; }
        }
    </style>
</head>
<body>

<div class="btns">
    <button onclick="window.print()">Print</button>
</div>

<div class="header-wrap">
    @if($isTes)
        <table class="logo-row">
            <tr>
                <td class="logo-left">
                    <img src="{{ asset('images/unifast.png') }}" alt="UniFAST Logo">
                </td>
                <td class="text-center">
                    <div class="main-title">GENERAL PAYROLL</div>
                    <div class="sub-title">TES FORM</div>
                    <div class="sub-title">TERTIARY EDUCATION SUBSIDY (TES) GRANTEES</div>
                    <div class="sub-title">Academic Year {{ $academicYear }}</div>
                </td>
                <td class="logo-right">
                    <img src="{{ asset('images/CHED.png') }}" alt="CHED Logo">
                </td>
            </tr>
        </table>
    @elseif($isTdp)
        <table class="logo-row">
            <tr>
                <td class="logo-left">
                    <img src="{{ asset('images/CHED.png') }}" alt="CHED Logo">
                </td>
                <td class="text-center">
                    <div class="main-title">REPUBLIC OF THE PHILIPPINES</div>
                    <div class="sub-title">BOHOL ISLAND STATE UNIVERSITY - CANDIJAY CAMPUS</div>
                    <div class="sub-title">Cogtong, Candijay, Bohol</div>
                    <div class="sub-title">Academic Year {{ $academicYear }}</div>
                    <div class="payroll-title">TULONG DUNONG PROGRAM - TERTIARY EDUCATION SUBSIDY (TDP-TES) PAYROLL</div>
                </td>
                <td class="logo-right">
                    <img src="{{ asset('images/unifast.png') }}" alt="UniFAST Logo">
                </td>
            </tr>
        </table>
    @else
        <div class="text-center fw-bold">PAYROLL FORM</div>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th rowspan="2">Seq.<br>No.</th>
            <th colspan="7">Student's Profile</th>
            <th colspan="3">First Semester</th>
            <th colspan="3">Second Semester</th>
        </tr>
        <tr>
            <th>{{ $isTes ? 'TES Award No.' : 'TDP Award No.' }}</th>
            <th>Student ID No.</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>M.I.</th>
            <th>Degree Program</th>
            <th>Year Level</th>

            <th>Total Amount Received</th>
            <th>Date Received</th>
            <th>Student's Signature</th>

            <th>Total Amount Received</th>
            <th>Date Received</th>
            <th>Student's Signature</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $row)
            <tr>
                <td class="text-center">{{ $row->seq_no }}</td>
                <td>{{ $row->award_no }}</td>
                <td>{{ $row->student_id }}</td>
                <td>{{ $row->lastname }}</td>
                <td>{{ $row->firstname }}</td>
                <td>{{ $row->middlename }}</td>
                <td>{{ $row->course }}</td>
                <td class="text-center">{{ $row->year_level }}</td>

                <td class="text-right">
                    {{ $row->first_amount !== null ? number_format((float)$row->first_amount, 2) : '' }}
                </td>
                <td class="text-center">
                    {{ $row->first_date_received ? \Carbon\Carbon::parse($row->first_date_received)->format('m/d/y') : '' }}
                </td>
                <td></td>

                <td class="text-right">
                    {{ $row->second_amount !== null ? number_format((float)$row->second_amount, 2) : '' }}
                </td>
                <td class="text-center">
                    {{ $row->second_date_received ? \Carbon\Carbon::parse($row->second_date_received)->format('m/d/y') : '' }}
                </td>
                <td></td>
            </tr>
        @empty
            <tr>
                <td colspan="14" class="text-center">No scholars found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="signature-lines">
    <tr>
        <td>
            Prepared by:<br><br>
            ___________________________<br>
            Scholarship Coordinator
        </td>
        <td>
            Certified True and Correct by:<br><br>
            ___________________________<br>
            Accountant
        </td>
        <td>
            Approved by:<br><br>
            ___________________________<br>
            Campus Administrator / Head
        </td>
    </tr>
</table>

</body>
</html>