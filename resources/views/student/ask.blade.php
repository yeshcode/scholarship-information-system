@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 820px;">

    {{-- Page header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Ask a Question</h2>
            <small class="text-muted">Send your inquiry to the Scholarship Office. Please be clear and specific.</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('questions.my') }}" class="btn btn-bisu-secondary">
                My Questions
            </a>
        </div>

    </div>

    {{-- Success alert --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error summary --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-semibold mb-1">Please fix the following:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ask form card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('questions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="question_text" class="form-label fw-semibold" style="color:#003366;">
                        Your question
                    </label>
                    <textarea
                        name="question_text"
                        id="question_text"
                        rows="5"
                        class="form-control @error('question_text') is-invalid @enderror"
                        placeholder="Example: What are the requirements for TDP scholarship this semester?"
                        required
                    >{{ old('question_text') }}</textarea>

                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-text text-muted mt-1">
                        Tip: Include scholarship name, semester, and your concern to get a faster answer.
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="reset" class="btn btn-bisu-secondary">
                        Clear
                    </button>
                    <button type="submit" class="btn btn-bisu-primary">
                        Submit Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- My Questions section (same page) --}}
    @isset($myQuestions)
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-semibold mb-0" style="color:#003366;">My Questions</h5>
            <small class="text-muted">Your recent inquiries</small>
        </div>

        @forelse($myQuestions as $q)
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="w-100">
                            <div class="text-muted small mb-1">
                                {{ $q->created_at ? $q->created_at->format('M d, Y • h:i A') : '' }}
                            </div>
                            <div style="white-space: pre-line;">
                                {{ $q->question_text }}
                            </div>
                        </div>

                        {{-- Status badge (adjust values based on your DB) --}}
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
                    </div>

                    {{-- Optional answer preview if you store answers --}}
                    @if(!empty($q->answer))
                        <div class="mt-2 p-2 rounded" style="background:#f8f9fa;">
                            <div class="small fw-semibold" style="color:#003366;">Answer:</div>
                            <div class="text-muted small" style="white-space: pre-line;">
                                {{ $q->answer }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                You have no questions yet.
            </div>
        @endforelse

        @if(method_exists($myQuestions, 'links'))
            <div class="d-flex justify-content-center mt-3">
                {{ $myQuestions->links() }}
            </div>
        @endif
    @endisset

</div>
@endsection
