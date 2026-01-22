@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 720px;">
    <div class="mb-3">
        <h2 class="page-title-blue mb-0">Notifications</h2>
        <small class="text-muted">Recent updates and alerts</small>
    </div>

    <hr class="mt-2 mb-3">

    @forelse($notifications as $notification)
        @php
            $isUnread = !$notification->is_read;

            // Default redirect (Announcements page)
            $redirectUrl = route('student.announcements.index');

            // If later you add per-announcement linking:
            // if($notification->announcement_id) {
            //     $redirectUrl = route('student.announcements.show', $notification->announcement_id);
            // }
        @endphp

        <a href="{{ $redirectUrl }}"
           class="text-decoration-none text-dark">

            <div class="card border-0 shadow-sm mb-2
                {{ $isUnread ? 'border-start border-4 border-primary bg-light' : '' }}">

                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-start gap-3">

                        {{-- Icon --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#003366;color:#fff;font-weight:700;">
                            🔔
                        </div>

                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h6 class="fw-semibold mb-1" style="color:#003366;">
                                    {{ $notification->title }}
                                </h6>

                                {{-- Unread dot --}}
                                @if($isUnread)
                                    <span class="badge bg-primary">New</span>
                                @endif
                            </div>

                            <p class="text-muted mb-1" style="white-space: pre-line;">
                                {{ \Illuminate\Support\Str::limit($notification->message, 140) }}
                            </p>

                            <small class="text-muted">
                                {{ $notification->sent_at
                                    ? $notification->sent_at->format('M d, Y • h:i A')
                                    : 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </a>

    @empty
        <div class="text-center py-5">
            <div class="mb-2" style="font-size: 2rem;">🔔</div>
            <h5 class="fw-semibold mb-1" style="color:#003366;">No notifications</h5>
            <p class="text-muted mb-0">You’re all caught up.</p>
        </div>
    @endforelse

    @if(method_exists($notifications, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
