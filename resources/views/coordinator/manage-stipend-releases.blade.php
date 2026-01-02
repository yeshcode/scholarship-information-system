@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
<h2 class="text-2xl font-bold mb-4">Manage Stipend Releases</h2>
<a href="{{ route('coordinator.stipend-releases.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">Add Release</a>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Batch</th>
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Amount</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date Release</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($releases as $release)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $release->scholarshipBatch->batch_number ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $release->title }}</td>
                <td class="px-4 py-2">{{ $release->amount }}</td>
                <td class="px-4 py-2">{{ $release->status }}</td>
                <td class="px-4 py-2">{{ $release->date_release }}</td>
                <td class="px-4 py-2 text-right">
                    <a href="{{ route('coordinator.stipend-releases.edit', $release->id) }}" class="text-blue-500 mr-2">Edit</a>
                    <a href="{{ route('coordinator.stipend-releases.confirm-delete', $release->id) }}" class="text-red-500">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $releases->links() }}
@endsection