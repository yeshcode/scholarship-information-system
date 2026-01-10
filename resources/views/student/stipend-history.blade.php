@extends('layouts.app')

@section('content')
<h2>Stipend History</h2>
@if($stipends->isEmpty())
    <p>No stipend history available.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Release Title</th>
                <th>Amount Received</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stipends as $stipend)
            <tr>
                <td>{{ $stipend->stipendRelease->title ?? 'N/A' }}</td>
                <td>{{ $stipend->amount_received }}</td>
                <td>{{ $stipend->status }}</td>
                <td>{{ $stipend->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $stipends->links() }}
@endif
@endsection