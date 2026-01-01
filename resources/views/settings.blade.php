@extends('layouts.app')

@section('content')
<div class="container">
    <h1>System Settings</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="system_name">System Name</label>
            <input type="text" name="system_name" id="system_name" class="form-control" value="{{ $settings->system_name }}" required>
        </div>
        <div class="form-group">
            <label for="logo_path">Logo</label>
            <input type="file" name="logo_path" id="logo_path" class="form-control">
            @if($settings->logo_path)
                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Current Logo" width="100">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>
@endsection