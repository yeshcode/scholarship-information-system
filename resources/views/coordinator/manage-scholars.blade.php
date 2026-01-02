@extends('layouts.coordinator')

@section('page-content')
<h2>Manage Scholars</h2>
<a href="{{ route('coordinator.scholars.create') }}">Add Scholar Manually</a>
<button>OCR Verification</button>  <!-- Placeholder for later -->
@foreach($scholars as $scholar)
    <p>{{ $scholar->user->firstname }} - {{ $scholar->scholarshipBatch->batch_number }} - {{ $scholar->status }}</p>
@endforeach
@endsection