@php $fullWidth = true; @endphp
@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --danger:#dc3545;
    }
    .page-title-bisu{ font-weight:800; font-size:1.6rem; color:var(--bisu-blue); margin:0; }
    .subtext{ color:#6b7280; font-size:.9rem; }
    .btn-bisu{
        background:var(--bisu-blue)!important;
        border-color:var(--bisu-blue)!important;
        color:#fff!important;
        font-weight:700;
    }
    .btn-bisu:hover{ background:var(--bisu-blue-2)!important; border-color:var(--bisu-blue-2)!important; }
    .card-bisu{ border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="page-title-bisu">Cheque Claim Notifications</h2>
        <div class="subtext">Notifications triggered when scholars confirm they already claimed their cheque.</div>
        <div class="mt-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                Unread: <strong>{{ $unreadCount ?? 0 }}</strong>
            </span>
        </div>
    </div>

    <a href="{{ route('coordinator.manage-stipends') }}" class="btn btn-bisu btn-sm">
        Back to Manage Stipends
    </a>
</div>

<div class="card card-bisu shadow-sm">
    <div class="card-body">
        @if($notifications->isEmpty())
            <div class="text-center text-muted py-4">No claim notifications yet.</div>
        @else
            <div class="list-group">
                @foreach($notifications as $n)
                    <div class="list-group-item d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong>{{ $n->title }}</strong>
                                @if(!$n->is_read)
                                    <span class="badge bg-danger">NEW</span>
                                @endif
                            </div>
                            <div class="text-muted small">
                                {{ \Carbon\Carbon::parse($n->sent_at ?? $n->created_at)->format('M d, Y h:i A') }}
                            </div>
                            <div class="mt-2">
                                {{ $n->message }}
                            </div>
                        </div>

                        <div class="text-end">
                            @if(!$n->is_read)
                                <form method="POST" action="{{ route('coordinator.notifications.read', $n->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                                        Mark as read
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Read</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection