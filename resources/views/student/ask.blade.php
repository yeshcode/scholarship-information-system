@extends('layouts.app')

@section('content')
<h1>Ask a Question</h1>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<form action="{{ route('questions.store') }}" method="POST">
    @csrf
    <div>
        <label for="question_text">Your question</label>
        <textarea name="question_text" id="question_text" rows="4" required>{{ old('question_text') }}</textarea>
        @error('question_text')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">Submit</button>
</form>
@endsection
