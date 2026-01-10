@extends('layouts.app')

@section('content')
<h2>Scholarships</h2>
@foreach($scholarships as $scholarship)
<div class="card mb-3">
    <div class="card-body">
        <h5>{{ $scholarship->scholarship_name }}</h5>
        <p>{{ $scholarship->description }}</p>
        <p><strong>Requirements:</strong> {{ $scholarship->requirements }}</p>
        <p><strong>Benefactor:</strong> {{ $scholarship->benefactor }}</p>
        <p><strong>Status:</strong> {{ $scholarship->status }}</p>
    </div>
</div>
@endforeach
{{ $scholarships->links() }}
@endsection