@extends('layouts.coordinator')

@section('page-content')
<h2>Create Announcement</h2>
<form action="{{ route('coordinator.announcements.store') }}" method="POST">
    @csrf
    <label>Title:</label><input type="text" name="title" required>
    <label>Description:</label><textarea name="description" required></textarea>
    <label>Posted At:</label><input type="datetime-local" name="posted_at" required>
    <button type="submit">Create</button>
</form>
@endsection