@extends('layouts.app')

@section('content')

<div class="container py-5 d-flex justify-content-center">

    <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 600px;">

        {{-- TITLE --}}
        <h2 class="fw-bold text-center mb-4" style="color: #003366;">
            System Settings
        </h2>

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Fix the following errors:</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- SYSTEM NAME --}}
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">System Name</label>
                <input 
                    type="text" 
                    name="system_name" 
                    value="{{ $settings->system_name }}"
                    class="form-control"
                    required
                >
            </div>

            {{-- LOGO UPLOAD --}}
            <div class="mb-3">
                <label class="form-label fw-bold text-dark">System Logo</label>
                <input 
                    type="file" 
                    name="logo_path" 
                    class="form-control"
                    accept="image/png, image/jpeg"
                >

                @if($settings->logo_path)
                    <div class="text-center mt-3">
                        <img 
                            src="{{ asset('storage/' . $settings->logo_path) }}"
                            alt="Logo"
                            class="img-fluid rounded border p-2"
                            style="max-height: 120px;">
                    </div>
                @endif

                <small class="text-muted">Recommended: PNG/JPG (128×128 or higher)</small>
            </div>

            {{-- SAVE BUTTON --}}
            <div class="text-center mt-4">
                <button class="btn btn-primary px-4 py-2 fw-bold">
                    Save Changes
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
