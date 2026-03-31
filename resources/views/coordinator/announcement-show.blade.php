@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --brand:#0b2e5e;
        --brand-600:#123f85;
        --brand-soft:#eef4ff;
        --brand-line:#cfe0ff;
        --ink:#1e293b;
        --muted:#6b7280;
        --line:#e5e7eb;
        --bg:#f4f8ff;
        --soft:#f8fafc;
    }

    .page-wrap{
        max-width: 1280px;
        margin: 0 auto;
        padding: 16px;
    }

    .page-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:16px;
    }

    .title{
        color:var(--brand);
        font-weight:800;
        margin:0;
    }

    .muted{
        color:var(--muted);
    }

    .btnx{
        border:1px solid transparent;
        border-radius:14px;
        padding:10px 14px;
        font-weight:700;
        font-size:.9rem;
        display:inline-flex;
        align-items:center;
        gap:8px;
        cursor:pointer;
        text-decoration:none;
        line-height:1;
        white-space:nowrap;
    }

    .btnx-primary{
        background:linear-gradient(180deg, var(--brand), var(--brand-600));
        color:#fff;
    }

    .btnx-secondary{
        background:#fff;
        border-color:var(--line);
        color:var(--ink);
    }

    .main-grid{
        display:grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(380px, .8fr);
        gap:18px;
        align-items:start;
    }

    @media (max-width: 991.98px){
        .main-grid{
            grid-template-columns:1fr;
        }
    }

    .panel{
        background:#fff;
        border:1px solid var(--line);
        border-radius:24px;
        box-shadow:0 .75rem 1.8rem rgba(15,23,42,.06);
        overflow:hidden;
    }

    .panel-body{
        padding:20px;
    }

    .meta-line{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin:10px 0 14px;
    }

    .badgex{
        display:inline-flex;
        align-items:center;
        gap:6px;
        font-size:.76rem;
        font-weight:700;
        padding:6px 12px;
        border-radius:999px;
        border:1px solid var(--line);
        background:#f8fafc;
        color:#334155;
    }

    .announce-title{
        font-size:1.38rem;
        font-weight:900;
        color:var(--brand);
        line-height:1.2;
        margin-bottom:10px;
    }

    .announce-text{
        color:#475569;
        line-height:1.68;
        font-size:.96rem;
        white-space:pre-line;
    }

    .announce-image{
        margin-top:16px;
        width:100%;
        max-height:420px;
        object-fit:cover;
        border-radius:18px;
        border:1px solid var(--line);
    }

    .sticky-comments{
        position:sticky;
        top:88px;
    }

    @media (max-width: 991.98px){
        .sticky-comments{
            position:static;
        }
    }

    .comment-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        margin-bottom:14px;
    }

    .section-title{
        margin:0;
        color:var(--brand);
        font-weight:900;
        font-size:1.04rem;
    }

    .comment-box{
        border:1px solid #e7edf6;
        border-radius:18px;
        padding:14px;
        background:#fff;
        margin-bottom:12px;
        transition: box-shadow .14s ease, transform .14s ease, border-color .14s ease;
    }

    .comment-box:hover{
        transform: translateY(-1px);
        box-shadow:0 10px 22px rgba(15,23,42,.05);
        border-color:#d6e4ff;
    }

    .comment-user{
        font-weight:800;
        color:var(--brand);
        font-size:.92rem;
    }

    .comment-date{
        color:var(--muted);
        font-size:.77rem;
        margin-top:3px;
    }

    .comment-text{
        margin-top:10px;
        color:#475569;
        line-height:1.58;
        font-size:.91rem;
        white-space:pre-line;
    }

    .reply-box{
        border:1px solid var(--brand-line);
        border-radius:16px;
        padding:12px;
        background:var(--brand-soft);
        margin-top:12px;
        margin-left:14px;
    }

    .reply-form{
        margin-top:12px;
        border-top:1px dashed #e5ebf5;
        padding-top:12px;
    }

    .text-area{
        width:100%;
        border:1px solid var(--line);
        border-radius:14px;
        padding:11px 13px;
        min-height:84px;
        resize:vertical;
        outline:none;
        background:#fff;
        font-size:.92rem;
    }

    .text-area:focus{
        border-color:#bcd6ff;
        box-shadow:0 0 0 4px rgba(18,63,133,.08);
    }

    .empty-box{
        text-align:center;
        padding:24px 14px;
        border:1px dashed #dbe4f0;
        border-radius:18px;
        color:var(--muted);
        background:#fbfdff;
    }
</style>

<div class="page-wrap">
    <div class="page-head">
        <div>
            <h2 class="title">Announcement Details</h2>
            <small class="muted">
                {{ $announcement->posted_at ? $announcement->posted_at->format('M d, Y • h:i A') : '' }}
            </small>
        </div>

        <a href="{{ route('coordinator.manage-announcements') }}" class="btnx btnx-secondary">
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="panel mb-3" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
            <div class="panel-body py-3">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="panel mb-3" style="border-color:#fecaca;background:#fef2f2;color:#991b1b;">
            <div class="panel-body py-3">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="panel mb-3" style="border-color:#fecaca;background:#fef2f2;color:#991b1b;">
            <div class="panel-body py-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="main-grid">
        {{-- LEFT --}}
        <div class="panel">
            <div class="panel-body">
                <div class="announce-title">{{ $announcement->title }}</div>

                <div class="meta-line">
                    @if($announcement->scholarship)
                        <span class="badgex">{{ $announcement->scholarship->scholarship_name }}</span>
                    @endif

                    <span class="badgex">
                        {{ match($announcement->audience){
                            'all_students' => 'All Students',
                            'specific_students' => 'Selected Students',
                            'scholarship_scholars' => 'All Scholars in Scholarship',
                            'specific_scholars' => 'Selected Scholars',
                            'all_scholars' => 'All Scholars',
                            default => 'Audience'
                        } }}
                    </span>
                </div>

                <div class="announce-text">
                    {{ $announcement->description }}
                </div>

                @if(!empty($announcement->image_path))
                    <img src="{{ asset('storage/' . $announcement->image_path) }}"
                         alt="Announcement image"
                         class="announce-image">
                @endif
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="sticky-comments">
            <div class="panel">
                <div class="panel-body">
                    <div class="comment-head">
                        <h4 class="section-title">Student Comments</h4>
                        <span class="badgex">{{ $announcement->comments->count() }} total</span>
                    </div>

                    @forelse($announcement->comments as $comment)
                        <div class="comment-box">
                            <div>
                                <div class="comment-user">
                                    {{ $comment->user->firstname ?? 'Student' }} {{ $comment->user->lastname ?? '' }}
                                </div>
                                <div class="comment-date">
                                    {{ $comment->created_at ? $comment->created_at->format('M d, Y • h:i A') : '' }}
                                </div>
                            </div>

                            <div class="comment-text">
                                {{ $comment->comment }}
                            </div>

                            @if($comment->replies->count())
                                @foreach($comment->replies as $reply)
                                    <div class="reply-box">
                                        <div class="comment-user" style="font-size:.88rem;">
                                            {{ $reply->user->firstname ?? 'Coordinator' }} {{ $reply->user->lastname ?? '' }}
                                        </div>
                                        <div class="comment-date">
                                            {{ $reply->created_at ? $reply->created_at->format('M d, Y • h:i A') : '' }}
                                        </div>

                                        <div class="comment-text" style="font-size:.89rem;">
                                            {{ $reply->comment }}
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <form action="{{ route('coordinator.announcements.reply.store', [$announcement->id, $comment->id]) }}"
                                  method="POST"
                                  class="reply-form">
                                @csrf

                                <div class="mb-2">
                                    <textarea
                                        name="comment"
                                        class="text-area"
                                        placeholder="Write your reply to this student..."
                                        required></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btnx btnx-primary">
                                        Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="empty-box">
                            <div class="mb-2" style="font-size:1.8rem;">💬</div>
                            <h6 class="fw-semibold mb-1" style="color:#003366;">No comments yet</h6>
                            <p class="muted mb-0">Students have not commented on this announcement yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection