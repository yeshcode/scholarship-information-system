@extends('layouts.coordinator')

@section('page-content')
<h2>Create Announcement</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('coordinator.announcements.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label>Posted At</label>
        <input type="datetime-local" name="posted_at" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Audience</label>
        <select name="audience" class="form-control" id="audience-select" required>
            <option value="all_students">All Students</option>
            <option value="specific_scholars">Specific Scholars</option>
        </select>
    </div>
    <div class="form-group" id="scholar-selection" style="display: none;">
        <label>Select Scholars</label>
        @foreach($scholars as $scholar)
        <div class="form-check">
            <input type="checkbox" name="selected_scholars[]" value="{{ $scholar->id }}" class="form-check-input">
            <label class="form-check-label">{{ $scholar->user->firstname }} {{ $scholar->user->lastname }} ({{ $scholar->user->bisu_email }})</label>
        </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-success">Post & Notify</button>
</form>

<script>
document.getElementById('audience-select').addEventListener('change', function() {
    const selection = document.getElementById('scholar-selection');
    selection.style.display = this.value === 'specific_scholars' ? 'block' : 'none';
});
</script>
@endsection