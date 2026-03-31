@extends('layouts.app')

@section('content')
<style>
    :root{
        --brand:#003366;
        --brand-2:#0b3d8f;
        --bg:#f4f7fb;
        --line:#e5e7eb;
        --muted:#6b7280;
        --soft:#f8fafc;
        --soft-blue:#eef4ff;
    }

    body{ background: var(--bg); }

    .page-wrap{
        max-width: 1280px;
        margin: 0 auto;
    }

    .page-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:16px;
    }

    .page-title{
        color:var(--brand);
        font-weight:900;
        margin:0;
    }

    .head-actions{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }

    .btn-brand{
        background: linear-gradient(135deg, var(--brand), var(--brand-2));
        color:#fff;
        border:none;
        border-radius:14px;
        padding:.62rem 1rem;
        font-weight:800;
        text-decoration:none;
        box-shadow: 0 10px 20px rgba(11,46,94,.14);
    }
    .btn-brand:hover{ color:#fff; }

    .btn-soft{
        background:#fff;
        color:var(--brand);
        border:1px solid var(--line);
        border-radius:14px;
        padding:.62rem 1rem;
        font-weight:800;
        text-decoration:none;
    }
    .btn-soft:hover{ color:var(--brand); background:#f8fafc; }

    .main-grid{
        display:grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(360px, .8fr);
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
        box-shadow:0 14px 34px rgba(15,23,42,.05);
        overflow:hidden;
    }

    .panel-body{
        padding:20px;
    }

    .announce-title{
        font-size:1.4rem;
        font-weight:900;
        color:var(--brand);
        line-height:1.22;
        margin-bottom:12px;
    }

    .meta-row{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-bottom:14px;
    }

    .pill{
        display:inline-flex;
        align-items:center;
        padding:.38rem .72rem;
        border-radius:999px;
        border:1px solid var(--line);
        background:#f8fafc;
        color:#334155;
        font-size:.76rem;
        font-weight:800;
    }

    .announce-text{
        color:#475569;
        white-space:pre-line;
        line-height:1.72;
        font-size:.97rem;
    }

    .announce-image{
        margin-top:16px;
        border-radius:18px;
        border:1px solid var(--line);
        width:100%;
        max-height:420px;
        object-fit:cover;
    }

    .comment-head{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        margin-bottom:14px;
        flex-wrap:wrap;
    }

    .section-title{
        margin:0;
        color:var(--brand);
        font-weight:900;
        font-size:1.04rem;
    }

    .comment-form{
        border:1px solid var(--line);
        border-radius:18px;
        background:var(--soft);
        padding:14px;
        margin-bottom:16px;
    }

    .comment-textarea{
        width:100%;
        border:1px solid var(--line);
        border-radius:16px;
        padding:12px 14px;
        min-height:92px;
        resize:vertical;
        outline:none;
        background:#fff;
    }

    .comment-textarea:focus{
        border-color:#bcd6ff;
        box-shadow:0 0 0 4px rgba(11,61,143,.08);
    }

    .comment-card{
        border:1px solid #e8edf5;
        border-radius:18px;
        background:#fff;
        padding:14px;
        margin-bottom:12px;
        transition: box-shadow .14s ease, border-color .14s ease, transform .14s ease;
    }

    .comment-card:hover{
        border-color:#dbe7ff;
        box-shadow:0 10px 20px rgba(15,23,42,.05);
        transform: translateY(-1px);
    }

    .comment-user{
        font-weight:800;
        color:var(--brand);
        font-size:.92rem;
        line-height:1.1;
    }

    .comment-date{
        color:var(--muted);
        font-size:.77rem;
        margin-top:3px;
    }

    .comment-text{
        margin-top:10px;
        color:#475569;
        line-height:1.6;
        font-size:.92rem;
        white-space:pre-line;
    }

    .reply-list{
        margin-top:12px;
        padding-left:12px;
        border-left:2px solid #e5edff;
    }

    .reply-card{
        border:1px solid #dbe7ff;
        border-radius:16px;
        background:var(--soft-blue);
        padding:12px;
        margin-bottom:10px;
    }

    .reply-top{
        display:flex;
        flex-wrap:wrap;
        align-items:center;
        gap:8px;
    }

    .reply-badge{
        display:inline-flex;
        align-items:center;
        padding:.22rem .55rem;
        border-radius:999px;
        background:#fff;
        border:1px solid #d6e4ff;
        color:var(--brand);
        font-size:.72rem;
        font-weight:800;
    }

    .empty-box{
        text-align:center;
        border:1px dashed #dbe4f0;
        border-radius:18px;
        padding:24px 14px;
        color:var(--muted);
        background:#fbfdff;
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
</style>

<div class="container py-3 py-md-4">
    <div class="page-wrap">

        <div class="page-head">
            <div>
                <h2 class="page-title">Announcement</h2>
                <small class="text-muted">
                    {{ $announcement->posted_at ? $announcement->posted_at->format('M d, Y • h:i A') : '' }}
                </small>
            </div>

            <div class="head-actions">
                <a href="{{ route('questions.create') }}" class="btn-brand">
                    Ask a Question
                </a>
                <a href="{{ route('student.announcements') }}" class="btn-soft">
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0 rounded-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger shadow-sm border-0 rounded-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="main-grid">
            {{-- LEFT: Announcement --}}
            <div class="panel">
                <div class="panel-body">
                    <div class="announce-title">
                        {{ $announcement->title }}
                    </div>

                    <div class="meta-row">
                        @if($announcement->scholarship)
                            <span class="pill">
                                {{ $announcement->scholarship->scholarship_name }}
                            </span>
                        @endif

                        @if(in_array($announcement->audience, ['specific_students', 'specific_scholars']))
                            <span class="pill">
                                Personal
                            </span>
                        @endif
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

            {{-- RIGHT: Comments --}}
            <div class="sticky-comments">
                <div class="panel">
                    <div class="panel-body">
                        <div class="comment-head">
                            <h5 class="section-title">Comments</h5>
                            <span class="pill">{{ $announcement->comments->count() }} total</span>
                        </div>

                        <form action="{{ route('student.announcements.comments.store', $announcement->id) }}" method="POST" class="comment-form">
                            @csrf
                            <div class="mb-2">
                                <textarea
                                    name="comment"
                                    class="comment-textarea"
                                    placeholder="Write your comment here..."
                                    required>{{ old('comment') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary rounded-4 px-4 fw-bold">
                                    Post Comment
                                </button>
                            </div>
                        </form>

                        @forelse($announcement->comments as $comment)
                            <div class="comment-card">
                                <div>
                                    <div class="comment-user">
                                        {{ $comment->user->firstname ?? 'User' }} {{ $comment->user->lastname ?? '' }}
                                    </div>
                                    <div class="comment-date">
                                        {{ $comment->created_at ? $comment->created_at->format('M d, Y • h:i A') : '' }}
                                    </div>
                                </div>

                                <div class="comment-text">
                                    {{ $comment->comment }}
                                </div>

                                @if($comment->replies->count())
                                    <div class="reply-list">
                                        @foreach($comment->replies as $reply)
                                            <div class="reply-card">
                                                <div class="reply-top">
                                                    <div class="comment-user" style="font-size:.88rem;">
                                                        {{ $reply->user->firstname ?? 'Coordinator' }} {{ $reply->user->lastname ?? '' }}
                                                    </div>
                                                    <span class="reply-badge">Coordinator Reply</span>
                                                </div>

                                                <div class="comment-date">
                                                    {{ $reply->created_at ? $reply->created_at->format('M d, Y • h:i A') : '' }}
                                                </div>

                                                <div class="comment-text" style="font-size:.9rem;">
                                                    {{ $reply->comment }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="empty-box">
                                <div class="mb-2" style="font-size:1.7rem;">💬</div>
                                <div class="fw-bold mb-1" style="color:var(--brand);">No comments yet</div>
                                <div>Be the first to comment on this announcement.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection