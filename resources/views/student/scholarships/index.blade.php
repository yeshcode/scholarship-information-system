@extends('layouts.app')

@section('content')
<div class="container-fluid container-xl py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Scholarships</h2>
            <small class="text-muted">Browse available scholarships and view details.</small>
        </div>
    </div>

    <div class="row g-3">
        @forelse($scholarships as $scholarship)
            @php
                $statusRaw = strtolower(trim((string)($scholarship->status ?? '')));
                $statusLabel = $scholarship->status ?? 'N/A';

                // Badge color (Bootstrap)
                $badgeClass = match(true) {
                    str_contains($statusRaw, 'open') => 'bg-success-subtle text-success border border-success-subtle',
                    str_contains($statusRaw, 'ongoing') => 'bg-success-subtle text-success border border-success-subtle',
                    str_contains($statusRaw, 'available') => 'bg-success-subtle text-success border border-success-subtle',
                    str_contains($statusRaw, 'closed') => 'bg-danger-subtle text-danger border border-danger-subtle',
                    str_contains($statusRaw, 'pending') => 'bg-warning-subtle text-warning border border-warning-subtle',
                    default => 'bg-light text-dark border',
                };

                $desc = trim((string)($scholarship->description ?? ''));
                $benefactor = trim((string)($scholarship->benefactor ?? ''));
            @endphp

            {{-- ✅ 3 cards per row on lg, 2 on md, 1 on sm --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 scholarship-card">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">

                        {{-- Top row: title + status --}}
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <div class="fw-bold text-bisu wrap-anywhere" style="font-size:1.05rem;">
                                {{ $scholarship->scholarship_name }}
                            </div>

                            @if(!empty($scholarship->status))
                                <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            @endif
                        </div>

                        {{-- Benefactor --}}
                        @if($benefactor !== '')
                            <div class="small text-muted mb-2 wrap-anywhere">
                                <i class="bi bi-building me-1"></i>
                                <span class="fw-semibold">Benefactor:</span>
                                {{ \Illuminate\Support\Str::limit($benefactor, 55) }}
                            </div>
                        @endif

                        {{-- Description preview --}}
                        <div class="text-muted small mb-3 clamp-3" style="white-space: pre-line;">
                            {{ $desc !== '' ? $desc : 'No description provided yet.' }}
                        </div>

                        {{-- Footer pinned to bottom --}}
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Tap to view full details
                            </div>

                            <a href="{{ route('student.scholarships.show', $scholarship->id) }}"
                               class="btn btn-bisu-primary btn-sm rounded-pill px-3">
                                View
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-2" style="font-size: 2.2rem;">🎓</div>
                    <h5 class="fw-semibold mb-1 text-bisu">No scholarships posted yet</h5>
                    <p class="text-muted mb-0">Please check again later.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($scholarships, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $scholarships->links() }}
        </div>
    @endif
</div>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --bisu-soft:#eef6ff;
    }

    .text-bisu{ color: var(--bisu-blue) !important; }

    .page-title-blue{
        font-weight: 800;
        color: var(--bisu-blue);
    }

    .scholarship-card{
        transition: transform .15s ease, box-shadow .15s ease;
        border: 1px solid rgba(0,0,0,.06);
    }
    .scholarship-card:hover{
        transform: translateY(-2px);
        box-shadow: 0 .9rem 1.6rem rgba(0,0,0,.08) !important;
    }

    .wrap-anywhere{
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /* clamp description to 3 lines */
    .clamp-3{
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 991.98px){
        .scholarship-card:hover{ transform: none; }
    }
</style>
@endsection