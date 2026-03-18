@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Announcement</h2>
            <small class="text-muted">
                {{ $announcement->posted_at ? $announcement->posted_at->format('M d, Y • h:i A') : '' }}
            </small>
        </div>

        <a href="{{ route('student.announcements') }}" class="btn btn-bisu-secondary btn-sm">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <h4 class="fw-semibold mb-2" style="color:#003366;">
                {{ $announcement->title }}
            </h4>

            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($announcement->scholarship)
                    <span class="badge rounded-pill text-bg-light border">
                        {{ $announcement->scholarship->scholarship_name }}
                    </span>
                @endif

                @if(in_array($announcement->audience, ['specific_students', 'specific_scholars']))
                    <span class="badge rounded-pill text-bg-light border">
                        Personal
                    </span>
                @endif
            </div>

            <div class="text-muted" style="white-space: pre-line;">
                {{ $announcement->description }}
            </div>

            @if(!empty($announcement->image_path))
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $announcement->image_path) }}"
                         alt="Announcement image"
                         class="img-fluid rounded-4 border">
                </div>
            @endif
        </div>
    </div>
</div>
@endsection