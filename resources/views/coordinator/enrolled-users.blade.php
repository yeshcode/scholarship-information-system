@extends('layouts.coordinator')

@section('page-content')
<h2>Enrolled Users</h2>
@foreach($enrolledUsers as $enrollment)
    <p>{{ $enrollment->user->firstname }} - {{ $enrollment->semester->term }}</p>
@endforeach
<h3>Add Enrolled User</h3>
<form action="{{ route('coordinator.enrolled-users.add') }}" method="POST">
    @csrf
    <!-- Add fields for user_id, semester_id, course_id, status -->
    <button type="submit">Add</button>
</form>
@endsection