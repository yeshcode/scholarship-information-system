@extends('layouts.app')

@section('content')
<div class="mx-auto" style="max-width: 760px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h2 class="page-title-blue mb-0">Notifications</h2>
            <small class="text-muted">Recent updates and alerts</small>
        </div>

        @php
            $hasUnread = collect($notifications)->contains(function ($notification) {
                return isset($notification->is_read) && !$notification->is_read;
            });
        @endphp

        @if($hasUnread)
            <form action="{{ route('student.notifications.markAllRead') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm rounded-pill px-3 fw-semibold"
                        style="border:1px solid #003366; color:#003366; background:#fff;">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <hr class="mt-2 mb-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .notif-card{
            border: 0;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(0,0,0,.06);
            transition: all .18s ease;
            overflow: hidden;
        }

        .notif-card:hover{
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(0,0,0,.09);
        }

        .notif-unread{
            border-left: 5px solid #0d6efd;
            background: #f8fbff;
        }

        .notif-type-box{
            width: 52px;
            min-width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            line-height: 1.05;
            box-shadow: 0 6px 14px rgba(0,0,0,.10);
        }

        .notif-type-stipend{
            background: linear-gradient(135deg, #dc3545, #b02a37);
        }

        .notif-type-announcement{
            background: linear-gradient(135deg, #0d6efd, #003f88);
        }

        .notif-type-scholars{
            background: linear-gradient(135deg, #6f42c1, #533089);
        }

        .notif-type-personal{
            background: linear-gradient(135deg, #198754, #146c43);
        }

        .notif-type-default{
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .notif-title{
            color: #003366;
            font-weight: 700;
            margin-bottom: .25rem;
        }

        .notif-message{
            color: #6c757d;
            margin-bottom: .45rem;
            white-space: pre-line;
        }

        .notif-meta{
            font-size: .86rem;
            color: #6c757d;
        }

        .notif-pill{
            display: inline-block;
            font-size: .70rem;
            font-weight: 700;
            padding: .28rem .55rem;
            border-radius: 999px;
        }

        .notif-pill-new{
            background: #e8f1ff;
            color: #0d6efd;
        }

        .notif-pill-stipend{
            background: #fdeaea;
            color: #b02a37;
        }

        .notif-pill-announcement{
            background: #eaf2ff;
            color: #003f88;
        }

        .notif-pill-scholars{
            background: #f1ebff;
            color: #6f42c1;
        }

        .notif-pill-personal{
            background: #e9f7ef;
            color: #146c43;
        }
    </style>

    @forelse($notifications as $notification)
        @php
            $isUnread = isset($notification->is_read) ? !$notification->is_read : false;
            $openUrl = route('student.notifications.open', $notification->id);

            $title = strtolower($notification->title ?? '');
            $message = strtolower($notification->message ?? '');
            $type = strtolower($notification->type ?? '');

            // default UI values
            $boxClass = 'notif-type-default';
            $boxLabel = 'INFO';
            $pillClass = '';
            $categoryLabel = 'General Notification';

            // stipend notifications
            if ($type === 'stipend' || str_contains($title, 'stipend') || str_contains($message, 'stipend')) {
                $boxClass = 'notif-type-stipend';
                $boxLabel = '₱';
                $pillClass = 'notif-pill-stipend';
                $categoryLabel = 'Stipend Update';
            }

            // announcements for scholars
            elseif (str_contains($title, 'scholar') || str_contains($message, 'scholar')) {
                $boxClass = 'notif-type-scholars';
                $boxLabel = 'SCH';
                $pillClass = 'notif-pill-scholars';
                $categoryLabel = 'Scholar Announcement';
            }

            // announcements for all students
            elseif (str_contains($title, 'all students') || str_contains($message, 'all students')) {
                $boxClass = 'notif-type-announcement';
                $boxLabel = 'ALL';
                $pillClass = 'notif-pill-announcement';
                $categoryLabel = 'Announcement for All Students';
            }

            // personal / direct student notification
            elseif ($type === 'announcement' || str_contains($title, 'you') || str_contains($message, 'you')) {
                $boxClass = 'notif-type-personal';
                $boxLabel = 'YOU';
                $pillClass = 'notif-pill-personal';
                $categoryLabel = 'Personal Notification';
            }
        @endphp

        <a href="{{ $openUrl }}" class="text-decoration-none text-dark d-block">
            <div class="card notif-card mb-3 {{ $isUnread ? 'notif-unread' : '' }}">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-start gap-3">

                        {{-- LEFT TYPE UI --}}
                        <div class="notif-type-box {{ $boxClass }}">
                            {{ $boxLabel }}
                        </div>

                        {{-- CONTENT --}}
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                                <div>
                                    <div class="notif-title">
                                        {{ $notification->title }}
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="notif-pill {{ $pillClass }}">
                                            {{ $categoryLabel }}
                                        </span>

                                        @if($isUnread)
                                            <span class="notif-pill notif-pill-new">New</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="notif-message">
                                {{ \Illuminate\Support\Str::limit($notification->message, 170) }}
                            </div>

                            <div class="notif-meta">
                                {{ $notification->sent_at
                                    ? $notification->sent_at->format('M d, Y • h:i A')
                                    : 'N/A' }}
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