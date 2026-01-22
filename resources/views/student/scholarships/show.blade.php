@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 920px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0">{{ $scholarship->scholarship_name }}</h2>
            <small class="text-muted">Full scholarship details</small>
        </div>

        <a href="{{ route('student.scholarships.index') }}" class="btn btn-bisu-secondary">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">

            @if(!empty($scholarship->status))
                <div class="mb-3">
                    <span class="badge bg-light text-dark border">
                        <strong>Status:</strong> {{ $scholarship->status }}
                    </span>
                </div>
            @endif

            @if(!empty($scholarship->benefactor))
                <p class="mb-2">
                    <strong>Benefactor:</strong> {{ $scholarship->benefactor }}
                </p>
            @endif

            @if(!empty($scholarship->description))
                <div class="mb-3">
                    <strong>Description</strong>
                    <div class="text-muted mt-1" style="white-space: pre-line;">
                        {{ $scholarship->description }}
                    </div>
                </div>
            @endif

            @if(!empty($scholarship->requirements))
                <div class="mb-3">
                    <strong>Requirements</strong>
                    <div class="text-muted mt-1" style="white-space: pre-line;">
                        {{ $scholarship->requirements }}
                    </div>
                </div>
            @endif

            {{-- Add more fields here if you have them (deadline, eligibility, contact, etc.) --}}
            {{--
            @if(!empty($scholarship->deadline))
                <p class="mb-2"><strong>Deadline:</strong> {{ $scholarship->deadline }}</p>
            @endif
            --}}
        </div>
    </div>
</div>
@endsection
