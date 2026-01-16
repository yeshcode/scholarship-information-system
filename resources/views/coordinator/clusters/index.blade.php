@extends('layouts.app')

@section('content')
<h1>Question Clusters</h1>

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Cluster ID</th>
            <th>Label / Topic</th>
            <th>Example Question</th>
            <th>Total Questions</th>
            <th>Answered?</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clusters as $cluster)
            <tr>
                <td>{{ $cluster->id }}</td>
                <td>{{ $cluster->label ?? '—' }}</td>
                <td>{{ $cluster->representative_question }}</td>
                <td>{{ $cluster->questions_count }}</td>
                <td>{{ $cluster->cluster_answer ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('clusters.show', $cluster->id) }}">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No question clusters yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
