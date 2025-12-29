@extends('layouts.app')

@section('page-content')
<h1 class="text-2xl font-bold mb-4">Manage Users</h1>
<p class="mb-6">Manage users here.</p>
<table class="table-auto w-full border">
    <thead><tr class="bg-gray-200"><th class="px-4 py-2">ID</th><th class="px-4 py-2">Name</th><th class="px-4 py-2">Email</th></tr></thead>
    <tbody>
        @foreach($users ?? [] as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection