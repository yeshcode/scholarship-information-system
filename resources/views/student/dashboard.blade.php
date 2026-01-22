@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Welcome, {{ auth()->user()->firstname }} 👋
            </h1>
            <p class="text-slate-600 text-sm">
                View announcements, scholarships, and track your questions in one place.
            </p>
        </div>

        {{-- Student status pill (optional) --}}
        <div class="inline-flex items-center px-3 py-1 rounded-full border border-slate-300 bg-white text-slate-700 text-sm">
            Student Dashboard
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('student.announcements') }}"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Announcements</div>
            <div class="text-slate-600 text-sm">See latest updates and deadlines.</div>
        </a>

        <a href="{{ route('student.scholarships') }}"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Scholarships</div>
            <div class="text-slate-600 text-sm">Browse available scholarship opportunities.</div>
        </a>

        <a href="{{ route('questions.create') }}"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Ask a Question</div>
            <div class="text-slate-600 text-sm">Submit your concern to the coordinator.</div>
        </a>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Announcements --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Recent Announcements</h2>
                    <a class="text-sm text-blue-700 hover:underline"
                       href="{{ route('student.announcements') }}">View all</a>
                </div>
            </div>
            <div class="p-5">
                @forelse($recentAnnouncements ?? [] as $a)
                    <div class="py-3 border-b last:border-b-0 border-slate-100">
                        <div class="font-semibold text-slate-800">{{ $a->title }}</div>
                        <div class="text-sm text-slate-600 line-clamp-2">{{ $a->content }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            Posted: {{ optional($a->created_at)->format('M d, Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-slate-600 text-sm">No announcements yet.</p>
                @endforelse
            </div>
        </div>

        {{-- My Questions --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">My Questions</h2>
                    <a class="text-sm text-blue-700 hover:underline"
                       href="{{ route('questions.my') }}">View all</a>
                </div>
            </div>
            <div class="p-5">
                @forelse($myRecentQuestions ?? [] as $q)
                    <div class="py-3 border-b last:border-b-0 border-slate-100">
                        <div class="text-slate-800 font-semibold line-clamp-2">
                            {{ $q->question }}
                        </div>
                        <div class="text-xs mt-1 inline-flex items-center px-2 py-0.5 rounded-full border
                            {{ $q->status === 'Answered' ? 'border-green-300 text-green-700 bg-green-50' : 'border-amber-300 text-amber-700 bg-amber-50' }}">
                            {{ $q->status ?? 'Pending' }}
                        </div>
                    </div>
                @empty
                    <p class="text-slate-600 text-sm">You haven’t submitted a question yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
