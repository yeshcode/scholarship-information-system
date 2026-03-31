@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 820px;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0">My Questions</h2>
            <small class="text-muted">All questions you have submitted.</small>
        </div>

        <a href="{{ route('questions.create') }}" class="btn btn-bisu-secondary">
            Back to Ask
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @forelse($myQuestions as $q)
        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="w-100">
                        <div class="text-muted small mb-1">
                            {{ $q->created_at ? $q->created_at->format('M d, Y • h:i A') : '' }}
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <div class="fw-semibold" style="color:#003366;">
                                Question
                            </div>

                            @php
                                $status = strtolower($q->status ?? 'pending');
                                $badge = match(true) {
                                    str_contains($status, 'answered') => 'bg-success',
                                    str_contains($status, 'pending') => 'bg-warning text-dark',
                                    str_contains($status, 'closed') => 'bg-secondary',
                                    default => 'bg-light text-dark border',
                                };
                            @endphp

                            <span class="badge {{ $badge }}">
                                {{ ucfirst($q->status ?? 'Pending') }}
                            </span>

                            @if(is_null($q->cluster_id))
                                <span class="badge bg-secondary">
                                    Thread removed by coordinator
                                </span>
                            @endif
                        </div>

                        <div class="text-muted" style="white-space: pre-line;">
                            {{ $q->question_text }}
                        </div>
                    </div>

                    <form action="{{ route('questions.destroy', $q->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this question?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>

                @if(!empty($q->answer))
                    <div class="mt-3 p-3 rounded" style="background:#f8f9fa;">
                        <div class="small fw-semibold mb-1" style="color:#003366;">Answer</div>
                        <div class="text-muted small" style="white-space: pre-line;">
                            {{ $q->answer }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            You have no questions yet.
        </div>
    @endforelse

    @if(method_exists($myQuestions, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $myQuestions->links() }}
        </div>
    @endif

</div>
@endsection