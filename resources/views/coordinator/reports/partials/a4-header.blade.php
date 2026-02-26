{{-- resources/views/coordinator/reports/partials/a4-header.blade.php --}}

@php
    $bisuSeal = asset('images/reports/bisu-seal.png');
    $bagong   = asset('images/reports/bagong-pilipinas.png');
    $tuv      = asset('images/reports/tuv.png');
@endphp

<style>
    .report-header{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:6px;
    }
    .report-header .logo{
        width:90px;
        flex:0 0 90px;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .report-header .logo img{
        max-width:90px;
        height:auto;
        display:block;
    }
    .report-header .center{
        flex:1 1 auto;
        text-align:center;
        line-height:1.15;
    }
    .report-header .center .line.small{ font-size:12px; }
    .report-header .center .line.title{ font-size:15px; font-weight:700; letter-spacing:.3px; }
    .report-header .center .line.office{ font-size:12px; font-weight:600; margin-top:2px; }
    .report-header .center .line.motto{ font-size:11px; margin-top:4px; }

    .report-header .logo.right{
        display:flex;
        flex-direction:column;
        gap:6px;
    }
    .report-header .logo.right img{
        max-width:90px;
    }
    .report-header .logo.right img.tuv{
        max-width:70px;
    }
</style>

<div class="report-header">
    <div class="logo left">
        <img src="{{ $bisuSeal }}" alt="BISU Seal">
    </div>

    <div class="center">
        <div class="line small">Republic of the Philippines</div>
        <div class="line title">BOHOL ISLAND STATE UNIVERSITY</div>
        <div class="line small">Cogtong, Candijay, Bohol, 6312, Philippines</div>
        <div class="line office">Office of the Admission and Scholarship</div>
        <div class="line motto">Balance | Integrity | Stewardship | Uprightness</div>
    </div>

    <div class="logo right">
        <img src="{{ $bagong }}" alt="Bagong Pilipinas">
        <img class="tuv" src="{{ $tuv }}" alt="TUV">
    </div>
</div>

<hr class="hr-line">