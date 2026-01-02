@extends('layouts.coordinator')

@section('page-content')
<h2>Manage Announcements</h2>
<a href="{{ route('coordinator.announcements.create') }}">Create Announcement</a>
@foreach($announcements as $announcement)
    <p>{{ $announcement->title }} - {{ $announcement->posted_at }} - By {{ $announcement->creator->firstname ?? 'N/A' }}</p>
@endforeach
@endsection