@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $theme = '#003366';
    $brand2 = '#0b3d8f';
    $bg = '#f4f7fb';
    $line = '#e5e7eb';
    $muted = '#6b7280';

    $items = method_exists($announcements, 'getCollection')
        ? $announcements->getCollection()
        : collect($announcements);

    $grouped = $items->groupBy(function ($a) {
        if (empty($a->posted_at)) return 'Date Unknown';

        $dt = $a->posted_at instanceof Carbon ? $a->posted_at : Carbon::parse($a->posted_at);

        if ($dt->isToday()) return 'Today';
        if ($dt->isYesterday()) return 'Yesterday';

        return $dt->format('M d, Y');
    });

    $viewedIds = $viewedIds ?? [];
@endphp

<style>
    body{ background: {{ $bg }}; }

    .page-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .page-shell{
        max-width: 860px;
        margin: 0 auto;
    }

    .page-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom: 16px;
    }

    .page-title{
        font-weight: 900;
        color: {{ $theme }};
        letter-spacing: .2px;
        margin: 0;
    }

    .subtext{
        color: {{ $muted }};
        font-size: .92rem;
    }

    .head-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .btn-ask{
        background: linear-gradient(135deg, {{ $theme }}, {{ $brand2 }});
        color:#fff;
        border:none;
        border-radius:14px;
        padding:.62rem 1rem;
        font-weight:800;
        box-shadow: 0 10px 22px rgba(11,46,94,.14);
        text-decoration:none;
    }
    .btn-ask:hover{
        color:#fff;
        transform: translateY(-1px);
    }

    .timeline-label{
        display:flex;
        align-items:center;
        gap:12px;
        margin: 18px 0 12px;
    }

    .timeline-label .line{
        flex: 1;
        height:1px;
        background: {{ $line }};
    }

    .timeline-label .tag{
        color: {{ $muted }};
        font-weight: 800;
        font-size: .86rem;
        padding: .25rem .7rem;
        border-radius: 999px;
        border: 1px solid {{ $line }};
        background: rgba(255,255,255,.86);
        white-space: nowrap;
    }

    .a-card{
        position:relative;
        border: 1px solid {{ $line }};
        border-radius: 22px;
        box-shadow: 0 10px 26px rgba(0,0,0,.045);
        overflow: hidden;
        background: #fff;
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
    }

    .a-card::before{
        content:"";
        position:absolute;
        left:0; top:0; bottom:0;
        width:4px;
        background: linear-gradient(180deg, {{ $theme }}, {{ $brand2 }});
        opacity:.92;
    }

    .a-card:hover{
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(11,46,94,.10);
        border-color: rgba(11,46,94,.18);
    }

    .a-body{
        padding: 16px 16px 16px 18px;
    }

    @media (min-width: 768px){
        .a-body{ padding: 18px 20px 18px 22px; }
    }

    .avatar{
        width: 46px;
        height: 46px;
        border-radius: 15px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: linear-gradient(135deg, {{ $theme }}, {{ $brand2 }});
        color:#fff;
        font-weight: 900;
        letter-spacing: .03em;
        flex: 0 0 auto;
        box-shadow: 0 10px 22px rgba(11,46,94,.18);
        user-select:none;
    }

    .meta{
        min-width: 0;
        width: 100%;
    }

    .meta-top{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .office{
        font-weight: 900;
        color: {{ $theme }};
        line-height: 1.15;
        font-size: .96rem;
    }

    .time{
        color: {{ $muted }};
        font-size: .82rem;
        white-space: nowrap;
    }

    .badge-new{
        background: rgba(34,197,94,.12);
        color: #166534;
        border: 1px solid rgba(34,197,94,.22);
        font-weight: 900;
        border-radius: 999px;
        padding: .22rem .55rem;
        font-size: .72rem;
        letter-spacing: .2px;
    }

    .a-title{
        margin-top: 10px;
        font-weight: 900;
        color: #111827;
        font-size: 1.05rem;
        line-height: 1.25;
    }

    .a-desc{
        margin-top: 8px;
        color: {{ $muted }};
        font-size: .95rem;
        line-height: 1.55;
        white-space: pre-line;
        overflow-wrap:anywhere;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .chip-row{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top: 12px;
    }

    .chip-lite{
        display:inline-flex;
        align-items:center;
        padding:.34rem .68rem;
        border-radius:999px;
        border:1px solid {{ $line }};
        background:#f8fafc;
        color:#334155;
        font-weight:700;
        font-size:.74rem;
    }

    .actions{
        display:flex;
        justify-content:flex-end;
        margin-top: 14px;
    }

    .btn-open{
        background: {{ $theme }};
        border: none;
        color: #fff;
        font-weight: 800;
        border-radius: 12px;
        padding: .56rem 1rem;
        text-decoration:none;
        box-shadow: 0 8px 16px rgba(11,46,94,.12);
        transition: transform .14s ease, box-shadow .14s ease, opacity .14s ease;
    }

    .btn-open:hover{
        color:#fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(11,46,94,.18);
        opacity:.96;
    }

    @media (max-width: 575.98px){
        .actions .btn-open{ width: 100%; text-align:center; }
        .time{ font-size: .78rem; }
    }
</style>

<div class="container py-3 py-md-4 page-wrap">
    <div class="page-shell">

        <div class="page-head">
            <div>
                <h2 class="page-title">Announcements</h2>
                <div class="subtext">Your updates feed and related questions.</div>
            </div>

            <div class="head-actions">
                <a href="{{ route('questions.create') }}" class="btn-ask">
                    Ask a Question
                </a>
            </div>
        </div>

        @forelse($grouped as $label => $list)

            <div class="timeline-label">
                <div class="line"></div>
                <div class="tag">{{ $label }}</div>
                <div class="line"></div>
            </div>

            @foreach($list as $announcement)
                @php
                    $dt = !empty($announcement->posted_at)
                        ? ($announcement->posted_at instanceof Carbon ? $announcement->posted_at : Carbon::parse($announcement->posted_at))
                        : null;

                    $timeLabel = $dt
                        ? ($dt->isToday() || $dt->isYesterday()
                            ? $dt->format('h:i A')
                            : $dt->format('M d') . ' at ' . $dt->format('h:i A'))
                        : 'N/A';

                    $isViewed = in_array((int)$announcement->id, $viewedIds, true);
                    $showNew = (!$isViewed && $dt && $dt->gt(now()->subDays(3)));
                @endphp

                <div class="a-card mb-3">
                    <div class="a-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="avatar">SO</div>

                            <div class="meta">
                                <div class="meta-top">
                                    <div>
                                        <div class="office">Scholarship Office</div>
                                        <div class="time">{{ $timeLabel }}</div>
                                    </div>

                                    @if($showNew)
                                        <span class="badge-new">NEW</span>
                                    @endif
                                </div>

                                <div class="a-title">
                                    {{ $announcement->title ?? 'Announcement' }}
                                </div>

                                <div class="a-desc">
                                    {{ $announcement->description }}
                                </div>

                                <div class="chip-row">
                                    @if($announcement->scholarship)
                                        <span class="chip-lite">
                                            {{ $announcement->scholarship->scholarship_name }}
                                        </span>
                                    @endif

                                    @if(in_array($announcement->audience, ['specific_students', 'specific_scholars']))
                                        <span class="chip-lite">
                                            Personal
                                        </span>
                                    @endif
                                </div>

                                <div class="actions">
                                    <a href="{{ route('student.announcements.show', $announcement->id) }}"
                                       class="btn-open">
                                        Open
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @empty
            <div class="text-center py-5">
                <div class="mb-2" style="font-size: 2rem;">📢</div>
                <h5 class="fw-semibold mb-1" style="color:{{ $theme }};">No announcements yet</h5>
                <p class="text-muted mb-0">Please check again later.</p>
            </div>
        @endforelse

        @if(method_exists($announcements, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>
</div>
@endsection