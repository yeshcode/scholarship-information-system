@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 920px;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Scholarships</h2>
            <small class="text-muted">Browse available scholarships and view details.</small>
        </div>

        {{-- Optional: search/filter later --}}
        {{--
        <form class="d-flex" method="GET" action="{{ route('student.scholarships.index') }}">
            <input class="form-control me-2" type="search" name="q" value="{{ request('q') }}" placeholder="Search scholarship...">
            <button class="btn btn-bisu-primary" type="submit">Search</button>
        </form>
        --}}
    </div>

    <hr class="mt-2 mb-3">

    <div class="row g-3">
        @forelse($scholarships as $scholarship)
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="w-100">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                    <h5 class="mb-0 fw-semibold" style="color:#003366;">
                                        {{ $scholarship->scholarship_name }}
                                    </h5>

                                    {{-- Status badge --}}
                                    @php
                                        $status = strtolower($scholarship->status ?? '');
                                        $badge = match(true) {
                                            str_contains($status, 'open') => 'bg-success',
                                            str_contains($status, 'ongoing') => 'bg-success',
                                            str_contains($status, 'available') => 'bg-success',
                                            str_contains($status, 'closed') => 'bg-danger',
                                            str_contains($status, 'pending') => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    @if(!empty($scholarship->status))
                                        <span class="badge {{ $badge }}">{{ $scholarship->status }}</span>
                                    @endif
                                </div>

                                {{-- Short description preview --}}
                                @if(!empty($scholarship->description))
                                    <p class="text-muted mb-2" style="white-space: pre-line;">
                                        {{ \Illuminate\Support\Str::limit($scholarship->description, 160) }}
                                    </p>
                                @endif

                                {{-- Key info row --}}
                                <div class="d-flex flex-wrap gap-2">
                                    @if(!empty($scholarship->benefactor))
                                        <span class="badge bg-light text-dark border">
                                            Benefactor: {{ \Illuminate\Support\Str::limit($scholarship->benefactor, 40) }}
                                        </span>
                                    @endif

                                    @if(!empty($scholarship->requirements))
                                        <span class="badge bg-light text-dark border">
                                            Requirements: {{ \Illuminate\Support\Str::limit($scholarship->requirements, 50) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- View details --}}
                            <div class="text-end flex-shrink-0">
                                <a href="{{ route('student.scholarships.show', $scholarship->id) }}"
                                   class="btn btn-bisu-primary btn-sm">
                                    View details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-2" style="font-size: 2rem;">🎓</div>
                <h5 class="fw-semibold mb-1" style="color:#003366;">No scholarships posted yet</h5>
                <p class="text-muted mb-0">Please check again later.</p>
            </div>
        @endforelse
    </div>

    @if(method_exists($scholarships, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $scholarships->links() }}
        </div>
    @endif
</div>
@endsection
