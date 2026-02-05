@extends('layouts.coordinator')

@section('page-content')
<h3 class="fw-bold mb-3">Edit Scholarship</h3>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('coordinator.scholarships.update', $scholarship->id) }}" method="POST" class="card border-0 shadow-sm">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="scholarship_name" value="{{ $scholarship->scholarship_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ $scholarship->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Requirements</label>
            <textarea name="requirements" class="form-control" rows="5" required>{{ $scholarship->requirements }}</textarea>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Benefactor</label>
                <input type="text" name="benefactor" value="{{ $scholarship->benefactor }}" class="form-control" required>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="open" {{ $scholarship->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ $scholarship->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

             <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Application Date</label>
                <input type="date" name="application_date"
                    value="{{ $scholarship->application_date ? \Carbon\Carbon::parse($scholarship->application_date)->format('Y-m-d') : '' }}"
                    class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Deadline</label>
                <input type="date" name="deadline"
                    value="{{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('Y-m-d') : '' }}"
                    class="form-control">
            </div>
        </div>
    </div>

    <div class="card-footer bg-white d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('coordinator.manage-scholarships') }}" class="btn btn-light">Cancel</a>
    </div>
</form>
@endsection
