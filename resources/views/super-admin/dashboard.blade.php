@extends('layouts.super-admin')  

@section('page-content')

@php
    $page = request('page');
@endphp

@if($page === 'sections')
    @include('super-admin.sections')

@elseif($page === 'year-levels')
    @include('super-admin.year-levels')

@elseif($page === 'colleges')
    @include('super-admin.colleges')

@elseif($page === 'courses')
    @include('super-admin.courses')

@elseif($page === 'semesters')
    @include('super-admin.semesters')

@elseif($page === 'enrollments')
    @include('super-admin.enrollments')

@elseif($page === 'manage-users')
    @include('super-admin.users')

@elseif($page === 'user-type')
    @include('super-admin.user-type')

@else
{{-- --------------------- DEFAULT SUPER ADMIN DASHBOARD --------------------- --}}
<div class="container-fluid py-4">

    <div class="mb-4">
        <h1 class="fw-bold text-primary" style="font-size: 2rem;">Super Admin Dashboard</h1>
        <p class="text-muted mb-0">Modern analytics overview of students and enrollments</p>
    </div>

    

    {{-- ===================== ROW 1: PIE (LEFT) + BAR (RIGHT) ===================== --}}
    <div class="row g-4">

        {{-- PIE CHART --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold text-primary fs-5">
                    Students Enrolled by Course
                </div>
                <div class="card-body">
                    <canvas id="coursePieChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        {{-- BAR CHART --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold text-primary fs-5">
                    Course Enrollments per Semester
                </div>
                <div class="card-body">
                    <canvas id="semCourseBarChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== ROW 2: LINE CHART FULL WIDTH ===================== --}}
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-primary fs-5">
                    Students Added per Year
                </div>
                <div class="card-body">
                    <canvas id="studentsYearLineChart" style="height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>


{{-- ===================== CHARTS SCRIPT ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ===== DATA PASSED FROM CONTROLLER =====
    let coursePieLabels      = @json($coursePieLabels ?? []);
    let coursePieCounts      = @json($coursePieCounts ?? []);
    let studentYearLabels    = @json($studentYearLabels ?? []);
    let studentYearCounts    = @json($studentYearCounts ?? []);
    let semCourseLabels      = @json($semCourseLabels ?? []);
    let semCourseCourseNames = @json($semCourseCourseNames ?? []);
    let semCourseMatrix      = @json($semCourseMatrix ?? []);

    let coursePieChart = null;
    let studentsYearLineChart = null;
    let semCourseBarChart = null;

    // ===== CREATE CHARTS =====
    function createCharts() {
        const pieCtx = document.getElementById('coursePieChart');
        if (pieCtx) {
            coursePieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: coursePieLabels,
                    datasets: [{
                        data: coursePieCounts,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }

        const lineCtx = document.getElementById('studentsYearLineChart');
        if (lineCtx) {
            studentsYearLineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: studentYearLabels,
                    datasets: [{
                        label: 'Students Registered',
                        data: studentYearCounts,
                        tension: 0.3,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        const barCtx = document.getElementById('semCourseBarChart');
        if (barCtx) {
            const datasets = semCourseCourseNames.map((name, index) => ({
                label: name,
                data: semCourseMatrix[index] || [],
            }));

            semCourseBarChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: semCourseLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { beginAtZero: true, stacked: true }
                    }
                }
            });
        }
    }

    // ===== REFRESH CHARTS VIA AJAX =====
    async function refreshDashboardCharts() {
        try {
            const response = await fetch('{{ route("admin.dashboard-data") }}');
            const data = await response.json();

            coursePieLabels = data.coursePieLabels;
            coursePieCounts = data.coursePieCounts;
            studentYearLabels = data.studentYearLabels;
            studentYearCounts = data.studentYearCounts;
            semCourseLabels = data.semCourseLabels;
            semCourseCourseNames = data.semCourseCourseNames;
            semCourseMatrix = data.semCourseMatrix;

            // Update the charts
            if (coursePieChart) {
                coursePieChart.data.labels = coursePieLabels;
                coursePieChart.data.datasets[0].data = coursePieCounts;
                coursePieChart.update();
            }

            if (studentsYearLineChart) {
                studentsYearLineChart.data.labels = studentYearLabels;
                studentsYearLineChart.data.datasets[0].data = studentYearCounts;
                studentsYearLineChart.update();
            }

            if (semCourseBarChart) {
                const newDatasets = semCourseCourseNames.map((name, index) => ({
                    label: name,
                    data: semCourseMatrix[index] || [],
                }));

                semCourseBarChart.data.labels = semCourseLabels;
                semCourseBarChart.data.datasets = newDatasets;
                semCourseBarChart.update();
            }

        } catch (error) {
            console.error('Dashboard refresh error:', error);
        }
    }

    // INITIALIZE ON LOAD
    document.addEventListener('DOMContentLoaded', function () {
        createCharts();
        setInterval(refreshDashboardCharts, 10000);
    });
</script>
@endif

@endsection
