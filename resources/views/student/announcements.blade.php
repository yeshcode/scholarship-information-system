@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $theme = '#003366';
    $brand2 = '#0b3d8f';
    $bg = '#f4f7fb';
    $line = '#e5e7eb';
    $muted = '#6b7280';

    // Group the paginator collection, not paginator object
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

    // viewedIds passed from controller
    $viewedIds = $viewedIds ?? [];

    // for "see more"
    $previewLimit = 220; // adjust if you want shorter/longer
@endphp

<style>
    body{ background: {{ $bg }}; }

    .page-wrap{
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
    }

    .page-head{
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom: 14px;
    }
    .page-title{
        font-weight: 900;
        color: {{ $theme }};
        letter-spacing: .2px;
        margin: 0;
    }
    .subtext{ color: {{ $muted }}; font-size: .92rem; }

    .timeline-label{
        display:flex;
        align-items:center;
        gap:12px;
        margin: 18px 0 12px;
    }
    .timeline-label .line{ flex: 1; height:1px; background: {{ $line }}; }
    .timeline-label .tag{
        color: {{ $muted }};
        font-weight: 800;
        font-size: .86rem;
        padding: .25rem .7rem;
        border-radius: 999px;
        border: 1px solid {{ $line }};
        background: rgba(255,255,255,.8);
        white-space: nowrap;
    }

    .a-card{
        border: 1px solid {{ $line }};
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(0,0,0,.05);
        overflow: hidden;
        background: #fff;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .a-card:hover{
        transform: translateY(-1px);
        box-shadow: 0 14px 34px rgba(0,0,0,.07);
    }

    .a-body{ padding: 14px; }
    @media (min-width: 768px){
        .a-body{ padding: 18px 20px; }
    }

    .avatar{
        width: 44px; height: 44px;
        border-radius: 14px;
        display:flex; align-items:center; justify-content:center;
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
        font-size: .95rem;
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
        font-size: 1.02rem;
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
    }

    .actions{
        display:flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .btn-brand{
        background: {{ $theme }};
        border: none;
        color: #fff;
        font-weight: 800;
        border-radius: 12px;
        padding: .5rem .9rem;
    }
    .btn-brand:hover{ opacity:.92; color:#fff; }

    .btn-soft{
        border: 1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: {{ $theme }};
        font-weight: 800;
        border-radius: 12px;
        padding: .5rem .9rem;
    }
    .btn-soft:hover{ background: rgba(11,46,94,.10); color: {{ $theme }}; }

    /* Mobile: make buttons comfortable */
    @media (max-width: 575.98px){
        .actions .btn{ width: 100%; }
        .time{ font-size: .78rem; }
    }

</style>

<div class="container py-3 py-md-4 page-wrap">
    <div class="mx-auto" style="max-width: 820px;">

        <div class="page-head">
            <div>
                <h2 class="page-title">Announcements</h2>
                <div class="subtext">Your updates feed.</div>
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

                    // "new" logic: show only if NOT viewed + posted within last 3 days (adjust if you want)
                    $showNew = (!$isViewed && $dt && $dt->gt(now()->subDays(3)));

                    $desc = (string)($announcement->description ?? '');
                    $isLong = Str::length($desc) > $previewLimit;

                    $collapseId = 'ann_desc_'.$announcement->id;
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

                                {{-- Description with See more --}}
                                <div class="a-desc">
                                    @if($isLong)
                                        {{-- Preview --}}
                                        <div id="preview_{{ $collapseId }}">
                                            {{ Str::limit($desc, $previewLimit) }}
                                        </div>

                                        {{-- Full text (Bootstrap collapse) --}}
                                        <div class="collapse" id="{{ $collapseId }}">
                                            {{ $desc }}
                                        </div>
                                    @else
                                        {{ $desc }}
                                    @endif
                                </div>

                                <div class="actions">
                                    {{-- Open (marks as viewed because announcementShow() writes AnnouncementView) --}}
                                    <a href="{{ route('student.announcements.show', $announcement->id) }}"
                                       class="btn btn-brand btn-sm">
                                        Open
                                    </a>

                                    @if($isLong)
                                        <button class="btn btn-soft btn-sm"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $collapseId }}"
                                                onclick="
                                                    const c = document.getElementById('{{ $collapseId }}');
                                                    const p = document.getElementById('preview_{{ $collapseId }}');
                                                    const btn = this;

                                                    setTimeout(() => {
                                                        const isShown = c.classList.contains('show');
                                                        if(isShown){
                                                            p.style.display = 'none';
                                                            btn.textContent = 'See less';
                                                        }else{
                                                            p.style.display = 'block';
                                                            btn.textContent = 'See more';
                                                        }
                                                    }, 50);
                                                ">
                                            See more
                                        </button>
                                    @endif
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

        {{-- Pagination --}}
        @if(method_exists($announcements, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>
</div>
@endsection