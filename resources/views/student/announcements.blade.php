@extends('layouts.app')

@section('content')
<h2>Announcements</h2>
@foreach($announcements as $announcement)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $announcement->title }}</h5>
        <p>{{ $announcement->description }}</p>
        <small>Posted on: {{ $announcement->posted_at ? $announcement->posted_at->format('Y-m-d H:i') : 'N/A' }}</small>
    </div>
</div>
@endforeach
{{ $announcements->links() }}
@endsection