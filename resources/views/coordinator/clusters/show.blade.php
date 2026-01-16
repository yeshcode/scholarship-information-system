@extends('layouts.app')

@section('content')
<h1>Cluster #{{ $cluster->id }}</h1>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<p><strong>Label/Topic:</strong> {{ $cluster->label ?? '—' }}</p>
<p><strong>Representative Question:</strong> {{ $cluster->representative_question }}</p>

<h2>Answer for this Cluster</h2>

<form action="{{ route('clusters.answer', $cluster->id) }}" method="POST">
    @csrf
    <textarea name="cluster_answer" rows="4" required>{{ old('cluster_answer', $cluster->cluster_answer) }}</textarea>
    @error('cluster_answer')
        <div>{{ $message }}</div>
    @enderror
    <button type="submit">Save Answer for All</button>
</form>

<h2>Questions in this Cluster</h2>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Student</th>
            <th>Question</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cluster->questions as $q)
            <tr>
                <td>{{ $q->user->name ?? 'Student #'.$q->user_id }}</td>
                <td>{{ $q->question_text }}</td>
                <td>{{ $q->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No questions in this cluster.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
