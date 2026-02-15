@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 1100px;">

@php
    $status = request('status');
    $q = request('q');
    $searchMode = request('search_mode', 'text');
    $threshold = request('threshold', 0.40);
    $isActive = fn($value) => $status === $value ? 'active' : '';
@endphp

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h2 class="page-title-blue mb-0">Student Inquiries</h2>
        <small class="text-muted">Open-thread style. Similar questions are grouped automatically.</small>
    </div>

    {{-- Filters --}}
    <div class="d-flex gap-2">
        <a href="{{ route('clusters.index', ['q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-bisu-secondary btn-sm {{ !$status ? 'active' : '' }}">
            All
        </a>

        <a href="{{ route('clusters.index', ['status' => 'unanswered', 'q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-bisu-secondary btn-sm {{ $isActive('unanswered') }}">
            Unanswered
        </a>

        <a href="{{ route('clusters.index', ['status' => 'answered', 'q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-bisu-secondary btn-sm {{ $isActive('answered') }}">
            Answered
        </a>
    </div>
</div>

{{-- Search + Similarity controls --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
            <form method="GET" action="{{ route('clusters.index') }}">
        <input type="hidden" name="status" value="{{ $status }}">

        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <label class="form-label small text-muted mb-1">Search</label>
                <input type="text" name="q" value="{{ $q }}" class="form-control"
                    placeholder="Type a question/topic (e.g., stipend requirements)">
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-bisu-primary" type="submit">Search</button>
                @if($q)
                    <a class="btn btn-outline-secondary" href="{{ route('clusters.index', ['status' => $status]) }}">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        <small class="text-muted d-block mt-2">
            Search is automatic: the system checks both keyword and “similar meaning”.
        </small>
    </form>

    </div>
</div>

{{-- Cards list --}}
@forelse ($clusters as $cluster)
    @php
        $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
        $hasNew = $isAnswered && ((int) ($cluster->new_unanswered_count ?? 0) > 0);


        // ✅ Black indicator for answered (adviser request)
        $dotStyle = $hasNew
            ? 'background:#dc3545;'      // red = answered but has new needs reply
            : ($isAnswered ? 'background:#111827;' : 'background:#f59e0b;');

        $badgeClass = $isAnswered ? 'bg-dark' : 'bg-warning text-dark';
        $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

        $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 52);
    @endphp

    <div class="card border-0 shadow-sm mb-2 {{ $hasNew ? 'border border-danger' : '' }}"
     style="{{ $hasNew ? 'border-left:6px solid #dc3545!important;' : '' }}">

        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">

                <div class="d-flex gap-3">
                    {{-- status dot --}}
                    <div class="rounded-circle flex-shrink-0"
                         title="{{ $badgeText }}"
                         style="width:12px;height:12px; margin-top:6px; {{ $dotStyle }}">
                    </div>

                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h5 class="fw-semibold mb-0" style="color:#003366;">
                                {{ $topic }}
                            </h5>
                            <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            @if($hasNew)
                                <span class="badge bg-danger">
                                    🔔 New: {{ (int) $cluster->new_unanswered_count }} need{{ (int)$cluster->new_unanswered_count === 1 ? 's' : '' }} reply
                                </span>
                            @endif
                        </div>

                        <div class="text-muted">
                            <span class="fw-semibold">Representative:</span>
                            <span style="white-space: pre-line;">
                                {{ \Illuminate\Support\Str::limit($cluster->representative_question, 150) }}
                            </span>
                        </div>

                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border">
                                👥 {{ $cluster->questions_count }} post{{ $cluster->questions_count == 1 ? '' : 's' }}
                            </span>
                            @if(!empty($cluster->created_at))
                                <span class="badge bg-light text-dark border">
                                    🕒 {{ $cluster->created_at->format('M d, Y') }}
                                </span>
                            @endif
                            <span class="badge bg-light text-dark border">🔒 Anonymous</span>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('clusters.show', [$cluster->id, 'threshold' => $threshold]) }}"
                       class="btn btn-bisu-primary btn-sm">
                        Open Thread
                    </a>
                    <div class="small text-muted mt-2">
                        @if($hasNew)
                            <span class="fw-semibold text-danger">New posts need reply</span>
                        @else
                            {{ $isAnswered ? 'Answer posted' : 'Reply needed' }}
                        @endif
                    </div>
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

@if(method_exists($clusters, 'links'))
    <div class="d-flex justify-content-center mt-4">
        {{ $clusters->links() }}
    </div>
@endif

</div>
@endsection
