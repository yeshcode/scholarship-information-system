@extends('layouts.coordinator')

@section('page-content')
<h1>Scholarship Coordinator Dashboard</h1>
<p>Welcome! Manage your scholarship system here.</p>
<a href="{{ route('coordinator.manage-scholars') }}">Manage Scholars</a> |
<a href="{{ route('coordinator.enrolled-users') }}">View All Enrolled Users</a> |
<a href="{{ route('coordinator.manage-scholarships') }}">Manage Scholarships</a> |  <!-- Added this -->
<a href="{{ route('coordinator.scholarship-batches') }}">Manage Scholarship Batches</a> |
<a href="{{ route('coordinator.manage-stipends') }}">Manage Stipend</a> |
<a href="{{ route('coordinator.manage-stipend-releases') }}">Manage Stipend Release</a> |
<a href="{{ route('coordinator.manage-announcements') }}">Manage Announcements</a>
@endsection