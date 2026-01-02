@extends('layouts.coordinator')

@section('page-content')
<h2>Add Scholar</h2>
<form action="{{ route('coordinator.scholars.store') }}" method="POST">
    @csrf
    <label>Student:</label>
    <select name="student_id" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
        @endforeach
    </select>
    <label>Batch:</label>
    <select name="batch_id" required>
        @foreach($batches as $batch)
            <option value="{{ $batch->id }}">{{ $batch->batch_number }}</option>
        @endforeach
    </select>
    <label>Date Added:</label><input type="date" name="date_added" required>
    <label>Status:</label>
    <select name="status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="graduated">Graduated</option>
    </select>
    <button type="submit">Add</button>
</form>
@endsection