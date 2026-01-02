@extends('layouts.coordinator')

@section('page-content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif
<h2 class="text-2xl font-bold mb-4">Manage Scholarships</h2>
<a href="{{ route('coordinator.scholarships.create') }}" class="bg-blue-500 text-black px-4 py-2 rounded mb-4 inline-block">Add Scholarship</a>
<table class="min-w-full bg-white border border-gray-200">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Benefactor</th>
            <th class="px-4 py-2 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scholarships as $scholarship)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $scholarship->scholarship_name }}</td>
                <td class="px-4 py-2">{{ Str::limit($scholarship->description, 50) }}</td>
                <td class="px-4 py-2">{{ $scholarship->status }}</td>
                <td class="px-4 py-2">{{ $scholarship->benefactor }}</td>
                <td class="px-4 py-2 text-right">
                    <a href="{{ route('coordinator.scholarships.edit', $scholarship->id) }}" class="text-blue-500 mr-2">Edit</a>
                    <a href="{{ route('coordinator.scholarships.confirm-delete', $scholarship->id) }}" class="text-red-500">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $scholarships->links() }}
@endsection