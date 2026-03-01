@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $bisuBlue = '#003366';

    // helper: safe parse
    $parseDate = function($dt){
        if(!$dt) return null;
        return $dt instanceof Carbon ? $dt : Carbon::parse($dt);
    };
@endphp

<style>
    :root{
        --bisu:#003366;
        --line:#e5e7eb;
        --muted:#6b7280;
        --soft:#f7faff;
    }

    .notif-wrap{ max-width: 820px; margin:0 auto; }

    .notif-card{
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 18px;
        background:#fff;
        box-shadow: 0 10px 22px rgba(15,23,42,.06);
        overflow:hidden;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .notif-card:hover{
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(15,23,42,.08);
    }
    @media (max-width: 991.98px){
        .notif-card:hover{ transform:none; }
    }

    /* left accent for unread */
    .notif-unread{
        border-left: 5px solid var(--bisu);
        background: linear-gradient(0deg, #ffffff, #fbfdff);
    }

    .notif-icon{
        width:44px; height:44px;
        border-radius: 16px;
        background: var(--bisu);
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight: 900;
        flex: 0 0 auto;
    }

    .notif-title{
        font-weight: 800;
        color: var(--bisu);
        line-height: 1.15;
        margin:0;
    }

    .notif-msg{
        color: #374151;
        font-size: .95rem;
        margin: .25rem 0 .35rem;
        white-space: pre-line;
    }

    .notif-time{
        color: var(--muted);
        font-size: .82rem;
    }

    /* Right-side indicators */
    .tag{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:.35rem;
        padding: .28rem .6rem;
        border-radius: 999px;
        font-weight: 900;
        font-size: .74rem;
        letter-spacing: .2px;
        border: 1px solid rgba(0,0,0,.08);
        white-space: nowrap;
    }
    .tag-new{ background:#ecfdf5; color:#166534; border-color:#bbf7d0; }
    .tag-due{ background:#fef2f2; color:#7f1d1d; border-color:#fecaca; }
    .tag-unread{ background:#eef6ff; color:#003366; border-color:#bfdbfe; }
    .tag-type{ background:#f8fafc; color:#334155; border-color:#e5e7eb; }

    /* Make layout responsive */
    .notif-row{
        display:flex;
        gap: 14px;
        align-items:flex-start;
    }

    .notif-right{
        margin-left:auto;
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap: 6px;
    }

    @media (max-width: 575.98px){
        .notif-icon{ width:40px; height:40px; border-radius: 14px; }
        .notif-msg{ font-size: .92rem; }

        /* On phones, move right tags under title area but keep right alignment */
        .notif-row{ gap: 12px; }
        .notif-right{ gap: 6px; }
        .tag{ font-size:.72rem; }
    }

    @media (max-width: 575.98px){
    .notif-right .btn{
        font-size: .72rem;
        padding: .22rem .55rem;
    }
}
</style>

<div class="notif-wrap">

    <div class="mb-3">
        <h2 class="page-title-blue mb-0">Notifications</h2>
        <small class="text-muted">Recent updates and alerts</small>
    </div>

    <hr class="mt-2 mb-3">

    @forelse($notifications as $notification)
        @php
            $isUnread = !$notification->is_read;

            // ✅ Use your OPEN route so it marks read + redirects correctly
            $redirectUrl = route('student.notifications.open', $notification->id);

            $sentAt = $parseDate($notification->sent_at) ?: $parseDate($notification->created_at);
            $now = now();

            $type = strtolower((string)($notification->type ?? ''));
            $isStipendNotif = ($type === 'stipend');

            /**
             * ✅ STATUS LOGIC (safe default)
             * NEW RELEASE (green): stipend notif created/sent within last 3 days
             * PAST DUE (red): stipend notif older than 7 days (meaning student should have claimed/received)
             *
             * If you have a REAL due date column (ex: $notification->due_at),
             * replace $pastDue logic below with comparison to that due date.
             */
            $isNewRelease = $isStipendNotif && $sentAt && $sentAt->gte($now->copy()->subDays(3));

            // default "past due" window
            $pastDue = $isStipendNotif && $sentAt && $sentAt->lt($now->copy()->subDays(7));

            // ✅ If you later add a real due date:
            // $dueAt = $parseDate($notification->due_at ?? null);
            // $pastDue = $isStipendNotif && $dueAt && $dueAt->isPast();

            // Message preview for list
            $preview = \Illuminate\Support\Str::limit((string)$notification->message, 160);

            // Small icon based on type
            $icon = $isStipendNotif ? '₱' : '🔔';

            $timeLabel = $sentAt ? $sentAt->format('M d, Y • h:i A') : 'N/A';
        @endphp

        <a href="{{ $redirectUrl }}" class="text-decoration-none text-dark">
            <div class="notif-card mb-2 {{ $isUnread ? 'notif-unread' : '' }}">
                <div class="p-3 p-md-4">

                    <div class="notif-row">
                        {{-- Icon --}}
                        <div class="notif-icon">{{ $icon }}</div>

                        {{-- Main text --}}
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <h6 class="notif-title">
                                    {{ $notification->title ?? 'Notification' }}
                                </h6>

                                {{-- Right-side tags + actions --}}
                                <div class="notif-right">
                                    @if($isNewRelease)
                                        <span class="tag tag-new">NEW RELEASE</span>
                                    @endif

                                    @if($pastDue)
                                        <span class="tag tag-due">PAST DUE</span>
                                    @endif

                                    @if($isUnread)
                                        <span class="tag tag-unread">UNREAD</span>

                                        {{-- ✅ Mark as read button (does NOT open the card) --}}
                                        <form action="{{ route('student.notifications.read', $notification->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onClick="event.stopPropagation();">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    style="border-radius:999px; font-weight:800; padding:.25rem .6rem;"
                                                    onClick="event.preventDefault(); event.stopPropagation(); this.closest('form').submit();">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Optional type tag --}}
                                    <span class="tag tag-type">
                                        {{ $isStipendNotif ? 'STIPEND' : strtoupper($type ?: 'INFO') }}
                                    </span>
                                </div>
                            </div>

                            <div class="notif-msg">
                                {{ $preview }}
                            </div>

                            <div class="notif-time">
                                {{ $timeLabel }}
                            </div>
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