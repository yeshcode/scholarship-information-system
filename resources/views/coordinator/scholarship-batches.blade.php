@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
<h2 class="text-2xl font-bold mb-4">Manage Scholarship Batches</h2>
<a href="{{ route('coordinator.scholarship-batches.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">Add Batch</a>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Scholarship</th>
            <th class="px-4 py-2">Semester</th>
            <th class="px-4 py-2">Batch Number</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batches as $batch)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $batch->scholarship->scholarship_name ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $batch->semester->term ?? 'N/A' }} {{ $batch->semester->academic_year ?? '' }}</td>
                <td class="px-4 py-2">{{ $batch->batch_number }}</td>
                <td class="px-4 py-2 text-right">
                    <a href="{{ route('coordinator.scholarship-batches.edit', $batch->id) }}" class="text-blue-500 mr-2">Edit</a>
                    <a href="{{ route('coordinator.scholarship-batches.confirm-delete', $batch->id) }}" class="text-red-500">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $batches->links() }}
@endsection