@extends('layouts.app')

@section('content')
<h1>My Questions</h1>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Date</th>
            <th>Question</th>
            <th>Status</th>
            <th>Answer</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($questions as $q)
            <tr>
                <td>{{ $q->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $q->question_text }}</td>
                <td>{{ ucfirst($q->status) }}</td>
                <td>{{ $q->answer ?? 'No answer yet. Please wait for the office staff.' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">You have not asked any questions yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
