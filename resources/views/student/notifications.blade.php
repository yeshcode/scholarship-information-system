@extends('layouts.app')

@section('content')
<h2>Notifications</h2>
@foreach($notifications as $notification)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $notification->title }}</h5>
        <p>{{ $notification->message }}</p>
        <small>Sent on: {{ $notification->sent_at ? $notification->sent_at->format('Y-m-d H:i') : 'N/A' }}</small>
    </div>
</div>
@endforeach
{{ $notifications->links() }}
@endsection