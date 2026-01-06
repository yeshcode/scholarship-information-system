@extends('layouts.coordinator')

@section('page-content')
<h2>Manage Announcements</h2>
<a href="{{ route('coordinator.announcements.create') }}" class="btn btn-primary">Create Announcement</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Audience</th>
            <th>Posted At</th>
            <th>Created By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($announcements as $announcement)
        <tr>
            <td>{{ $announcement->title }}</td>
            <td>{{ Str::limit($announcement->description, 50) }}</td>
            <td>{{ $announcement->audience === 'all_students' ? 'All Students' : 'Specific Scholars' }}</td>
            <td>{{ $announcement->posted_at ? $announcement->posted_at->format('Y-m-d H:i') : 'N/A' }}</td>
            <td>{{ $announcement->creator->firstname ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $announcements->links() }}
@endsection