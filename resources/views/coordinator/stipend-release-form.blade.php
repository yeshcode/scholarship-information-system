@php $fullWidth = true; @endphp
@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{ --bisu:#003366; --line:#e5e7eb; --muted:#6b7280; }
    .card-bisu{ border:1px solid var(--line); border-radius:14px; overflow:hidden; }
    .thead-bisu th{ background:var(--bisu); color:#fff; font-size:.78rem; text-transform:uppercase; letter-spacing:.03em; white-space:nowrap; }
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }} <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }} <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@php
    $batch = $release->scholarshipBatch;
    $schName = $batch?->scholarship?->scholarship_name ?? 'N/A';
    $batchLabel = $batch ? ('Batch ' . $batch->batch_number) : 'N/A';
    $semLabel = $release->semester
        ? (($release->semester->term ?? $release->semester->semester_name) . ' ' . $release->semester->academic_year)
        : 'N/A';
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Stipend Release / Liquidation Form</h4>
        <div class="text-muted small">
            {{ $schName }} • {{ $batchLabel }} • Release Semester: {{ $semLabel }} • Title:
            <span class="fw-semibold">{{ $release->title }}</span>
            • Amount: <span class="fw-semibold">₱ {{ number_format((float)$release->amount, 2) }}</span>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('coordinator.manage-stipend-releases') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        <a href="{{ route('coordinator.stipend-releases.form.print', $release->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">Print</a>
        <a href="{{ route('coordinator.stipend-releases.form.excel', $release->id) }}" class="btn btn-primary btn-sm">Download Excel</a>
    </div>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-header bg-white fw-semibold text-secondary">
        Scholars List (Preview)
        <span class="text-muted small">• liquidation columns</span>
    </div>

    <div class="table-responsive">
        
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-bisu">
                <tr>
                    @foreach($columns as $c)
                        <th>{{ $c->label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($scholars as $s)
                    @php
                        $u = $s->user;

                        // ✅ year level per semester (release semester)
                        $en = $s->enrollments->first();
                        $semYearLevel = $en?->yearLevel?->year_level ?? null;
                        $fallbackYearLevel = $u?->yearLevel?->year_level ?? $u?->year_level ?? '';

                        $yearLevel = $semYearLevel ?? $fallbackYearLevel;
                    @endphp
                    <tr>
                        @foreach($columns as $c)
                            @php
                                $val = match($c->key) {
                                    // ✅ added columns
                                    'student_id' => $u?->student_id ?? '',
                                    'amount'     => '₱ ' . number_format((float)$release->amount, 2),
                                    'remarks'    => '',

                                    // typical liquidation fields
                                    'printed_name'  => '',
                                    'date_received' => '',

                                    // existing
                                    'firstname'  => $u?->firstname ?? '',
                                    'middlename' => $u?->middlename ?? '',
                                    'lastname'   => $u?->lastname ?? '',
                                    'year_level' => $yearLevel,
                                    'course'     => $u?->course?->course_name ?? '',
                                    'college'    => $u?->college?->college_name ?? '',
                                    'signature'  => '',
                                    default      => '',
                                };
                            @endphp
                            <td>{{ $val }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $columns->count() }}" class="text-center text-muted py-4">
                            No scholars found in this batch.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection