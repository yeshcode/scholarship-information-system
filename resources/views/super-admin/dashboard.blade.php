@extends('layouts.super-admin')  

@section('page-content')
<p>Debug: Page parameter is "{{ request('page') }}"</p>
@if(request('page') === 'sections')
    @include('super-admin.sections')
@elseif(request('page') === 'year-levels')
    @include('super-admin.year-levels')
@elseif(request('page') === 'colleges')
    @include('super-admin.colleges')
@elseif(request('page') === 'courses')
    @include('super-admin.courses')
@elseif(request('page') === 'semesters')
    @include('super-admin.semesters')
@elseif(request('page') === 'enrollments')
    @include('super-admin.enrollments')
@elseif(request('page') === 'manage-users')
    @include('super-admin.users')
@elseif(request('page') === 'user-type')
    @include('super-admin.user-type')
@else
    <!-- Default Dashboard Content -->
    <h1 class="text-2xl font-bold mb-4">Welcome to Super Admin Dashboard</h1>
    <p class="mb-6">This is your dashboard. Use the navigation above to access different sections.</p>
@endif
@endsection