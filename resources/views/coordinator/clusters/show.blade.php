@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 1100px;">

@php
    $isAnswered = !is_null($cluster->cluster_answer) && trim($cluster->cluster_answer) !== '';
    $badgeClass = $isAnswered ? 'bg-dark' : 'bg-warning text-dark';
    $badgeText  = $isAnswered ? 'Answered' : 'Needs reply';

    $topic = $cluster->label ?: \Illuminate\Support\Str::limit($cluster->representative_question, 60);
    $total = $cluster->questions->count();

    $threshold = $threshold ?? 0.40;
@endphp

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h2 class="page-title-blue mb-0">Student Inquiry Thread</h2>
        <small class="text-muted">Open forum style. Similarity threshold: <span class="fw-semibold">{{ number_format((float)$threshold, 2) }}</span></small>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('clusters.index', request()->only('status','q','search_mode','threshold')) }}"
           class="btn btn-bisu-secondary btn-sm">
            ← Back
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <div class="fw-semibold mb-1">Please fix the errors below.</div>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Topic Card --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div class="d-flex gap-3">

                {{-- answered dot (black if answered) --}}
                <div class="rounded-circle flex-shrink-0"
                     style="width:12px;height:12px;margin-top:7px; {{ $isAnswered ? 'background:#111827;' : 'background:#f59e0b;' }}">
                </div>

                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h5 class="fw-semibold mb-0" style="color:#003366;">{{ $topic }}</h5>
                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                    </div>

                    <div class="text-muted">
                        <span class="fw-semibold">Representative question:</span><br>
                        <span style="white-space: pre-line;">{{ $cluster->representative_question }}</span>
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border">👥 {{ $total }} post{{ $total == 1 ? '' : 's' }}</span>
                        <span class="badge bg-light text-dark border">🔒 Anonymous</span>
                        @if(!empty($cluster->created_at))
                            <span class="badge bg-light text-dark border">🕒 {{ $cluster->created_at->format('M d, Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-end">
                <div class="small text-muted">Thread ID</div>
                <div class="fw-semibold" style="color:#003366;">#{{ $cluster->id }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Answer Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-semibold mb-0" style="color:#003366;">Coordinator Answer</h5>
            <small class="text-muted">Saved answer can be applied to selected new posts.</small>
        </div>

        <form action="{{ route('clusters.answer', $cluster->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <textarea name="cluster_answer"
                          rows="5"
                          class="form-control @error('cluster_answer') is-invalid @enderror"
                          placeholder="Type your official answer here..."
                          required>{{ old('cluster_answer', $cluster->cluster_answer) }}</textarea>

                @error('cluster_answer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="form-text text-muted mt-1">
                    Tip: Put the official steps, requirements, deadlines, and where to submit.
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('clusters.index', request()->only('status','q','search_mode','threshold')) }}" class="btn btn-bisu-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-bisu-primary">
                    Save / Update Answer
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $answeredAt = $cluster->cluster_answered_at ?? null;

    // New questions = created AFTER cluster_answered_at (if answered exists)
    $newQuestions = collect($cluster->questions)->filter(function($x) use ($answeredAt, $isAnswered){
        if (!$isAnswered || !$answeredAt) return false;
        return $x->created_at && $x->created_at->gt($answeredAt);
    });
@endphp

{{-- New Questions (checkbox selection) --}}
@if($isAnswered)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h5 class="fw-semibold mb-1" style="color:#003366;">New questions after your answer</h5>
                    <div class="text-muted">
                        Select which ones should receive the saved answer.
                    </div>
                </div>

                <div class="text-end small text-muted">
                    {{ $newQuestions->count() }} new
                </div>
            </div>

            @if($newQuestions->count() > 0)
                <form method="POST" action="{{ route('clusters.answer-selected', $cluster->id) }}">
                    @csrf

                    <div class="mt-3 d-flex flex-column gap-2">
                        @foreach($newQuestions as $nq)
                            <label class="d-flex gap-2 align-items-start p-2 rounded border bg-light">
                                <input type="checkbox" name="question_ids[]" value="{{ $nq->id }}" class="form-check-input mt-1">
                                <div class="flex-grow-1">
                                    <div class="small text-muted mb-1">
                                        Posted {{ $nq->created_at ? $nq->created_at->format('M d, Y • h:i A') : '' }}
                                    </div>
                                    <div style="white-space: pre-line;">{{ $nq->question_text }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-dark">
                            Apply Saved Answer to Selected
                        </button>
                    </div>
                </form>
            @else
                <div class="mt-3 text-muted">No new questions found.</div>
            @endif
        </div>
    </div>
@endif

{{-- Questions List (forum style) --}}
<div class="d-flex align-items-center justify-content-between mb-2">
    <h5 class="fw-semibold mb-0" style="color:#003366;">Thread Posts</h5>
    <small class="text-muted">{{ $total }} total</small>
</div>

@forelse ($cluster->questions as $q)
    @php
        $qAnswered = !empty($q->answer) && trim($q->answer) !== '';

        // ✅ similarity score from controller
        $sim = (float) ($q->sim_score ?? 0);

        // ✅ Mark "similar" in RED (adviser request)
        // Use threshold as the cut line
        $isSimilarMarked = $sim >= (float)$threshold;

        // ✅ answered indicator black (dot)
        $dot = $qAnswered ? 'background:#111827;' : 'background:#f59e0b;';

        $border = $isSimilarMarked ? 'border border-danger' : 'border';
        $bg = $isSimilarMarked ? 'bg-danger bg-opacity-10' : 'bg-white';
    @endphp

    <div class="card {{ $border }} shadow-sm mb-2 {{ $bg }}" style="border-radius:14px;">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-start gap-3">

                <div class="d-flex gap-3">
                    <div class="rounded-circle flex-shrink-0" style="width:12px;height:12px;margin-top:7px; {{ $dot }}"></div>

                    <div>
                        <div class="small text-muted mb-1">
                            Post #{{ $loop->iteration }}
                            @if($q->created_at) • {{ $q->created_at->format('M d, Y • h:i A') }} @endif
                            <span class="ms-2 badge bg-light text-dark border">
                                Similarity: {{ number_format($sim, 2) }}
                            </span>
                            @if($isSimilarMarked)
                                <span class="ms-1 badge bg-danger">Marked similar</span>
                            @endif
                        </div>

                        <div style="white-space: pre-line;">{{ $q->question_text }}</div>
                    </div>
                </div>

                <div class="text-end">
                    <span class="badge {{ $qAnswered ? 'bg-dark' : 'bg-warning text-dark' }}">
                        {{ $qAnswered ? 'Answered' : 'Unanswered' }}
                    </span>
                </div>
            </div>

            {{-- Manual answer --}}
            <div class="mt-3">
                <form method="POST" action="{{ route('clusters.questions.answer', $q->id) }}">
                    @csrf

                    <textarea name="answer"
                              rows="3"
                              class="form-control @error('answer') is-invalid @enderror"
                              placeholder="Write an answer for this specific post...">{{ old('answer', $q->answer) }}</textarea>

                    @error('answer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <small class="text-muted">
                            @if(!empty($q->answered_at))
                                Answered: {{ $q->answered_at->format('M d, Y • h:i A') }}
                            @else
                                Not answered yet
                            @endif
                        </small>

                        <button type="submit" class="btn btn-bisu-primary btn-sm">
                            Save Answer
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@empty
    <div class="text-center py-5 text-muted">
        No posts in this thread yet.
    </div>
@endforelse

</div>
@endsection
