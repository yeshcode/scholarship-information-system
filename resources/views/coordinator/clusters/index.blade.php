@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 1100px;">

    @php
        $status = request('status'); // answered | unanswered | null
        $q = request('q');

        $isActive = fn($value) => $status === $value ? 'active' : '';
    @endphp

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Student Inquiries</h2>
            <small class="text-muted">Grouped similar questions for faster replying.</small>
        </div>

        {{-- Filters --}}
        <div class="d-flex gap-2">
            <a href="{{ route('clusters.index', ['q' => $q]) }}"
               class="btn btn-bisu-secondary btn-sm {{ !$status ? 'active' : '' }}">
                All
            </a>

            <a href="{{ route('clusters.index', ['status' => 'unanswered', 'q' => $q]) }}"
               class="btn btn-bisu-secondary btn-sm {{ $isActive('unanswered') }}">
                Unanswered
            </a>

            <a href="{{ route('clusters.index', ['status' => 'answered', 'q' => $q]) }}"
               class="btn btn-bisu-secondary btn-sm {{ $isActive('answered') }}">
                Answered
            </a>
        </div>
    </div>

    {{-- Search bar (functional) --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('clusters.index') }}">
                {{-- keep current tab when searching --}}
                <input type="hidden" name="status" value="{{ $status }}">

                <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                    <div class="flex-grow-1">
                        <input type="text"
                               name="q"
                               value="{{ $q }}"
                               class="form-control"
                               placeholder="Search a topic or keyword (e.g., stipend, requirements)">
                    </div>

                    <button class="btn btn-bisu-primary" type="submit">
                        Search
                    </button>

                    @if($q)
                        <a class="btn btn-outline-secondary"
                           href="{{ route('clusters.index', ['status' => $status]) }}">
                            Clear
                        </a>
                    @endif
                </div>

                <small class="text-muted d-block mt-2">
                    Tip: Use short labels like “TDP Requirements”, “Stipend Release”, “Eligibility”, etc.
                </small>
            </form>
        </div>
    </div>

    {{-- Cards list --}}
    @forelse ($clusters as $cluster)
        @php
            // treat NULL + empty string as unanswered
            $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
            $badgeClass = $isAnswered ? 'bg-success' : 'bg-warning text-dark';
            $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

            $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 45);
        @endphp

        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-start justify-content-between gap-3">

                    {{-- Left: icon + topic --}}
                    <div class="d-flex gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:42px;height:42px;background:#003366;color:#fff;font-weight:700;">
                            ?
                        </div>

                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h5 class="fw-semibold mb-0" style="color:#003366;">
                                    {{ $topic }}
                                </h5>

                                <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>

                            <div class="text-muted">
                                <span class="fw-semibold">Example:</span>
                                <span style="white-space: pre-line;">
                                    {{ \Illuminate\Support\Str::limit($cluster->representative_question, 140) }}
                                </span>
                            </div>

                            <div class="mt-2 d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border">
                                    👥 {{ $cluster->questions_count }} student{{ $cluster->questions_count == 1 ? '' : 's' }}
                                </span>

                                @if(!empty($cluster->created_at))
                                    <span class="badge bg-light text-dark border">
                                        🕒 {{ $cluster->created_at->format('M d, Y') }}
                                    </span>
                                @endif

                                <span class="badge bg-light text-dark border">
                                    🔒 Names hidden
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: action --}}
                    <div class="text-end">
                        <a href="{{ route('clusters.show', $cluster->id) }}"
                           class="btn btn-bisu-primary btn-sm">
                            Open Thread
                        </a>

                        @if($isAnswered)
                            <div class="small text-muted mt-2">Reply already posted</div>
                        @else
                            <div class="small text-muted mt-2">Write one reply for all</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    @empty
        <div class="text-center py-5">
            <div class="mb-2" style="font-size:2rem;">📭</div>
            <h5 class="fw-semibold mb-1" style="color:#003366;">No inquiries yet</h5>
            <p class="text-muted mb-0">Student questions will appear here once submitted.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if(method_exists($clusters, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $clusters->links() }}
        </div>
    @endif

</div>
@endsection
