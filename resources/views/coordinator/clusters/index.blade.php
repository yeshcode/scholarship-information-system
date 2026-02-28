@extends('layouts.app')

@section('content')
<style>
    :root{
        --brand:#0b2e5e;
        --brand2:#123f85;
        --muted:#6b7280;
        --bg:#f4f7fb;
        --line:#e5e7eb;

        --successSoft:#eaf7ef;
        --warningSoft:#fff7e6;
        --infoSoft:#e8f1ff;
    }

    body{ background: var(--bg); }

    .page-title-blue{
        font-weight: 800;
        font-size: 1.7rem;
        color: var(--brand);
        margin: 0;
        letter-spacing:.2px;
    }
    .subtext{ color: var(--muted); font-size: .92rem; }

    .filter-pill .btn{
        border: 1px solid var(--line);
        background: #fff;
        color: #111827;
    }
    .filter-pill .btn.active,
    .filter-pill .btn:focus{
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        box-shadow: none;
    }

    .card-soft{
        border: 1px solid var(--line);
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
    }

    /* Thread card states */
    .thread-card{
        border: 1px solid var(--line);
        border-radius: 14px;
        background: #fff;
        transition: transform .08s ease, box-shadow .08s ease;
    }
    .thread-card:hover{
        transform: translateY(-1px);
        box-shadow: 0 .5rem 1.2rem rgba(15, 23, 42, .08);
    }

    .thread-card.is-new{
        border-color: #198754;
        background: var(--successSoft);
    }
    .thread-card.is-new .left-accent{
        background: #198754;
    }

    .thread-card.is-answered{
        background: #fff;
    }
    .thread-card.is-answered .left-accent{
        background: #111827; /* answered indicator requested */
    }

    .thread-card.is-unanswered{
        background: var(--warningSoft);
        border-color: #f59e0b;
    }
    .thread-card.is-unanswered .left-accent{
        background: #f59e0b;
    }

    .left-accent{
        width: 6px;
        border-radius: 14px 0 0 14px;
        background: var(--line);
        flex-shrink: 0;
    }

    .meta-badge{
        background:#fff;
        border: 1px solid var(--line);
        color:#111827;
        font-weight: 600;
    }

    .btn-bisu-primary{
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }
    .btn-bisu-primary:hover{ background: var(--brand2); border-color: var(--brand2); color:#fff; }

    .btn-bisu-secondary{
        background: #fff;
        border: 1px solid var(--line);
        color: #111827;
    }
    .btn-bisu-secondary:hover{ background: #f8fafc; }
</style>

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
        <h2 class="page-title-blue mb-1">Student Inquiries</h2>
        {{-- <div class="subtext">Similar questions are grouped automatically. Open a thread to reply.</div> --}}
    </div>

    {{-- Filters --}}
    <div class="d-flex gap-2 filter-pill">
        <a href="{{ route('clusters.index', ['q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-sm {{ !$status ? 'active' : '' }}">
            All
        </a>

        <a href="{{ route('clusters.index', ['status' => 'unanswered', 'q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-sm {{ $isActive('unanswered') }}">
            Needs Reply
        </a>

        <a href="{{ route('clusters.index', ['status' => 'answered', 'q' => $q, 'search_mode' => $searchMode, 'threshold' => $threshold]) }}"
           class="btn btn-sm {{ $isActive('answered') }}">
            Answered
        </a>
    </div>
</div>

{{-- Search --}}
<div class="card-soft shadow-sm mb-3">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('clusters.index') }}">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="row g-2 align-items-end">
                <div class="col-12 col-md">
                    <label class="form-label small text-muted mb-1">Search inquiries</label>
                    <input type="text"
                        name="q"
                        value="{{ $q }}"
                        class="form-control"
                        placeholder="Search questions keywords . . .">
                </div>

                <div class="col-12 col-md-auto d-flex gap-2 align-items-end">
                    <button class="btn btn-bisu-primary px-4" type="submit">
                        Search
                    </button>

                    @if($q)
                        <a class="btn btn-bisu-secondary px-4"
                        href="{{ route('clusters.index', ['status' => $status]) }}">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            {{-- <div class="small text-muted mt-2">
                Tip: Search checks both <span class="fw-semibold">keywords</span> and <span class="fw-semibold">similar meaning</span>.
            </div> --}}
        </form>
    </div>
</div>

{{-- Threads --}}
@forelse ($clusters as $cluster)
    @php
        $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
        $hasNew = $isAnswered && ((int) ($cluster->new_unanswered_count ?? 0) > 0);

        // ✅ Card state priority: NEW (green) > UNANSWERED (yellow) > ANSWERED (neutral)
        $stateClass = $hasNew ? 'is-new' : ($isAnswered ? 'is-answered' : 'is-unanswered');

        $badgeClass = $hasNew ? 'bg-success' : ($isAnswered ? 'bg-dark' : 'bg-warning text-dark');
        $badgeText  = $hasNew ? 'New Posts' : ($isAnswered ? 'Answered' : 'Needs Reply');

        $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 52);
    @endphp

    <div class="thread-card shadow-sm mb-2 d-flex {{ $stateClass }}">
        <div class="left-accent"></div>

        <div class="p-3 p-md-4 w-100">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="me-2">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h5 class="mb-0 fw-semibold" style="color: var(--brand);">
                            {{ $topic }}
                        </h5>
                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>

                        @if($hasNew)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                {{ (int) $cluster->new_unanswered_count }} new need{{ (int)$cluster->new_unanswered_count === 1 ? 's' : '' }} reply
                            </span>
                        @endif
                    </div>
                    {{-- Representative of each thread --}}
                    {{-- <div class="text-muted">
                        <span class="fw-semibold">Representative:</span>
                        <span style="white-space: pre-line;">
                            {{ \Illuminate\Support\Str::limit($cluster->representative_question, 150) }}
                        </span>
                    </div> --}}

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="badge meta-badge">{{ $cluster->questions_count }} post{{ $cluster->questions_count == 1 ? '' : 's' }}</span>
                        @if(!empty($cluster->created_at))
                            <span class="badge meta-badge">{{ $cluster->created_at->format('M d, Y') }}</span>
                        @endif
                        <span class="badge meta-badge">Anonymous</span>
                    </div>
                </div>

                <div class="text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('clusters.show', [$cluster->id, 'threshold' => $threshold]) }}"
                        class="btn btn-bisu-primary btn-sm">
                            Open Thread
                        </a>

                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteThreadModal-{{ $cluster->id }}">
                            Delete
                        </button>
                    </div>

                    {{-- <div class="small text-muted mt-2">
                        @if($hasNew)
                            <span class="fw-semibold text-success">New posts waiting</span>
                        @else
                            {{ $isAnswered ? 'Answer already posted' : 'Waiting for reply' }}
                        @endif
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Delete Thread Modal --}}
    @if($cluster->questions_count == 0)
<div class="modal fade" id="deleteThreadModal-{{ $cluster->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;">
      <div class="modal-header">
        <h5 class="modal-title">Delete this thread?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-2 text-muted">
          This will permanently delete the entire thread and all posts under it.
        </div>

        <div class="p-3 rounded border" style="background:#fff;">
          <div class="fw-semibold" style="color: var(--brand);">
            {{ $topic }}
          </div>
          <div class="small text-muted mt-1">
            {{ $cluster->questions_count }} post{{ $cluster->questions_count == 1 ? '' : 's' }}
            @if(!empty($cluster->created_at))
              • Created {{ $cluster->created_at->format('M d, Y') }}
            @endif
          </div>
        </div>

        <div class="alert alert-warning mt-3 mb-0">
          <span class="fw-semibold">Note:</span> This action cannot be undone.
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-bisu-secondary" data-bs-dismiss="modal">
          Cancel
        </button>

        <form method="POST" action="{{ route('clusters.destroy', $cluster->id) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            Yes, Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@empty
    <div class="text-center py-5">
        <div class="mb-2" style="font-size:2rem;">📭</div>
        <h5 class="fw-semibold mb-1" style="color:var(--brand);">No inquiries yet</h5>
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