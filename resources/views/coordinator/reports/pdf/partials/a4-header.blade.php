@php
    $bisuSeal = public_path('images/reports/bisu-seal.png');
    $bagong   = public_path('images/reports/bagong-pilipinas.png');
    $tuv      = public_path('images/reports/tuv.png');
@endphp

<table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
    <tr>
        <td style="width:90px; text-align:center; vertical-align:middle; border:none;">
            @if(file_exists($bisuSeal))
                <img src="{{ $bisuSeal }}" alt="BISU Seal" style="width:80px; height:auto;">
            @endif
        </td>

        <td style="text-align:center; vertical-align:middle; border:none; line-height:1.2; padding:0 8px;">
            <div style="font-size:12px;">Republic of the Philippines</div>
            <div style="font-size:15px; font-weight:bold; letter-spacing:.3px;">BOHOL ISLAND STATE UNIVERSITY</div>
            <div style="font-size:12px;">Cogtong, Candijay, Bohol, 6312, Philippines</div>
            <div style="font-size:12px; font-weight:bold; margin-top:2px;">Office of the Admission and Scholarship</div>
            <div style="font-size:11px; margin-top:4px;">Balance | Integrity | Stewardship | Uprightness</div>
        </td>

        <td style="width:90px; text-align:center; vertical-align:middle; border:none;">
            @if(file_exists($bagong))
                <img src="{{ $bagong }}" alt="Bagong Pilipinas" style="width:75px; height:auto; display:block; margin:0 auto 6px;">
            @endif

            @if(file_exists($tuv))
                <img src="{{ $tuv }}" alt="TUV" style="width:55px; height:auto; display:block; margin:0 auto;">
            @endif
        </td>
    </tr>
</table>

<hr style="border:0; border-top:2px solid #000; margin:6px 0 12px;">