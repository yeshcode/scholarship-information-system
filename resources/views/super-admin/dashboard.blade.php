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

    <style>
        :root{
            --brand:#0b2e5e;
            --brand2:#123f85;
            --muted:#6b7280;
            --bg:#f4f7fb;
            --line:#e5e7eb;
            --shadow: 0 14px 34px rgba(15, 23, 42, .08);
        }

        body { background: var(--bg); }

        /* Headings */
        .dash-title{
            font-weight: 900;
            color: var(--brand);
            letter-spacing: .2px;
            font-size: 1.75rem;
            margin: 0;
        }
        .dash-sub{ color: var(--muted); font-size: .95rem; }

        /* Card shells */
        .card-shell{
            border:1px solid var(--line);
            border-radius: 18px;
            background:#fff;
            box-shadow: var(--shadow);
            overflow:hidden;
        }
        .card-shell .card-header{
            background:#fff;
            border-bottom:1px solid var(--line);
            padding: .95rem 1.1rem;
        }
        .card-shell .card-body{ padding: 1.1rem; }

        /* KPI Cards */
        .kpi-card{
            border:1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 26px rgba(15,23,42,.06);
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .kpi-card:hover{
            transform: translateY(-2px);
            box-shadow: 0 16px 34px rgba(15,23,42,.10);
        }
        .kpi-label{
            color: var(--muted);
            font-size: .86rem;
            font-weight: 700;
        }
        .kpi-value{
            font-size: 1.85rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.1;
        }
        .kpi-icon{
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: rgba(11,46,94,.08);
            border: 1px solid rgba(11,46,94,.12);
            color: var(--brand);
            font-weight: 900;
            font-size: 1.05rem;
            flex: 0 0 auto;
        }

        /* Buttons */
        .btn-soft{
            background:#f8fafc;
            border:1px solid var(--line);
            font-weight: 700;
        }
        .btn-soft:hover{ background:#eef2ff; }

        .mini-badge{
            background:#f8fafc;
            border:1px solid var(--line);
            color:#334155;
            font-weight:800;
        }

        /* Chart area */
        .chart-wrap{ height: 340px; }
        @media (max-width: 576px){
            .dash-title{ font-size: 1.45rem; }
            .chart-wrap{ height: 260px; }
        }

        /* Status cards */
        .status-card{
            border:1px solid var(--line);
            border-radius: 16px;
            padding: .9rem;
            background: #fbfdff;
        }
        .status-card .label{ color: var(--muted); font-size: .82rem; font-weight: 700; }
        .status-card .val{ font-size: 1.55rem; font-weight: 900; color:#0f172a; line-height:1.1; }

        /* Quick panel items */
        .quick-item{
            border:1px solid var(--line);
            border-radius: 14px;
            padding: .75rem .85rem;
            background: #fff;
            transition: .12s ease;
            text-decoration: none;
            color: inherit;
        }
        .quick-item:hover{
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .quick-title{
            font-weight: 800;
            color: #0f172a;
        }
        .quick-sub{
            color: var(--muted);
            font-size: .85rem;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="dash-title">Admin Dashboard</h1>
                @if(!empty($activeSemesterName))
                    <span class="badge rounded-pill mini-badge">{{ $activeSemesterName }}</span>
                @endif
            </div>
            <div class="dash-sub mt-1">
                Overview of students and enrollment status (auto-refresh every 10s)
            </div>
        </div>

        {{-- Desktop Quick Actions --}}
        <div class="d-none d-md-flex gap-2">
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="btn btn-soft btn-sm">
                Manage Students
            </a>
            <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="btn btn-soft btn-sm">
                Enrollment Records
            </a>
            <a href="{{ route('admin.dashboard', ['page' => 'semesters']) }}" class="btn btn-soft btn-sm">
                Semesters
            </a>
        </div>

        {{-- Mobile Quick Actions (Offcanvas) --}}
        <div class="d-md-none">
            <button class="btn btn-soft btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#quickActions">
                Quick Actions
            </button>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="quickActions">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">Quick Actions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-grid gap-2">
            <a href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}" class="btn btn-outline-primary">
                Manage Students
            </a>
            <a href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}" class="btn btn-outline-primary">
                Enrollment Records
            </a>
            <a href="{{ route('admin.dashboard', ['page' => 'semesters']) }}" class="btn btn-outline-secondary">
                Semesters
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="kpi-label">Total Students</div>
                        <div class="kpi-value" id="kpiTotalStudents">{{ $kpiTotalStudents ?? 0 }}</div>
                        <div class="text-muted small mt-1">All registered students</div>
                    </div>
                    <div class="kpi-icon">👥</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="kpi-label">Enrolled (This Semester)</div>
                        <div class="kpi-value" id="kpiEnrolledThisSemester">{{ $kpiEnrolledThisSemester ?? 0 }}</div>
                        <div class="text-muted small mt-1">Within selected semester</div>
                    </div>
                    <div class="kpi-icon">✅</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="kpi-label">Not Enrolled</div>
                        <div class="kpi-value" id="statNotEnrolled">{{ $statNotEnrolled ?? 0 }}</div>
                        <div class="text-muted small mt-1">No enrollment record yet</div>
                    </div>
                    <div class="kpi-icon">⏳</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="kpi-label">Incomplete Profiles</div>
                        <div class="kpi-value" id="kpiIncompleteStudents">{{ $kpiIncompleteStudents ?? 0 }}</div>
                        <div class="text-muted small mt-1">Needs profile completion</div>
                    </div>
                    <div class="kpi-icon">⚠️</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main row --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-shell h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="color:var(--brand);">Enrollments by College</div>
                        <div class="text-muted small">Shows enrolled students per college</div>
                    </div>
                    <span class="badge rounded-pill mini-badge">Auto-refresh</span>
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="enrollByCollegeBar"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-shell h-100">
                <div class="card-header">
                    <div class="fw-bold" style="color:var(--brand);">Students by Year Level</div>
                    <div class="text-muted small">Profile distribution</div>
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="studentsByYearLevelDonut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Second row --}}
    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            <div class="card-shell h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="color:var(--brand);">Enrollment Status Overview</div>
                        <div class="text-muted small">Quick numbers + distribution</div>
                    </div>
                    <span class="badge rounded-pill mini-badge">Auto-refresh</span>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="status-card h-100">
                                <div class="label">Enrolled</div>
                                <div class="val" id="statEnrolled">{{ $statEnrolled ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="status-card h-100">
                                <div class="label">Dropped</div>
                                <div class="val" id="statDropped">{{ $statDropped ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="status-card h-100">
                                <div class="label">Graduated</div>
                                <div class="val" id="statGraduated">{{ $statGraduated ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="status-card h-100">
                                <div class="label">Not Enrolled</div>
                                <div class="val" id="statNotEnrolled2">{{ $statNotEnrolled ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="text-muted small mb-2 fw-semibold">Distribution</div>
                        <div class="chart-wrap" style="height:280px;">
                            <canvas id="enrollmentStatusDonut"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Quick Admin Panel --}}
        <div class="col-lg-5">
            <div class="card-shell h-100">
                <div class="card-header">
                    <div class="fw-bold" style="color:var(--brand);">Quick Admin Panel</div>
                    <div class="text-muted small">Shortcuts to manage core data</div>
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'manage-users']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Manage Students</div>
                                    <div class="quick-sub">Add, edit, and update student profiles</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countUsers">{{ $countUsers ?? 0 }}</span>
                            </div>
                        </a>

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'enrollments']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Enrollment Records</div>
                                    <div class="quick-sub">View enrollment status per semester</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countEnrollments">{{ $countEnrollments ?? 0 }}</span>
                            </div>
                        </a>

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'colleges']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Colleges</div>
                                    <div class="quick-sub">Maintain college list</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countColleges">{{ $countColleges ?? 0 }}</span>
                            </div>
                        </a>

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'courses']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Courses</div>
                                    <div class="quick-sub">Manage program offerings</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countCourses">{{ $countCourses ?? 0 }}</span>
                            </div>
                        </a>

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'year-levels']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Year Levels</div>
                                    <div class="quick-sub">Edit year level options</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countYearLevels">{{ $countYearLevels ?? 0 }}</span>
                            </div>
                        </a>

                        <a class="quick-item" href="{{ route('admin.dashboard', ['page' => 'semesters']) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="quick-title">Semesters</div>
                                    <div class="quick-sub">Add and set active semester</div>
                                </div>
                                <span class="badge bg-light text-secondary border" id="countSemesters">{{ $countSemesters ?? 0 }}</span>
                            </div>
                        </a>

                    </div>

                    <div class="mt-3 text-muted small">
                        Tip: Use the Semester filter in the navbar to change dashboard scope.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<footer class="border-top py-3 mt-4 bg-white">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
        <div class="text-muted small">
            © {{ date('Y') }} BISU Candijay Campus • Scholarship Management Information System
        </div>
        <div class="text-muted small">
            Admin Panel
        </div>
    </div>
</footer>

{{-- ✅ IMPORTANT: use LOCAL Chart.js (recommended) --}}
{{-- Put chart.umd.min.js in public/chartjs/chart.umd.min.js --}}
<script src="{{ asset('chartjs/chart.umd.min.js') }}"></script>

<script>
    // =====================
    // INITIAL DATA
    // =====================
    let enrollCollegeLabels = @json($enrollCollegeLabels ?? []);
    let enrollCollegeCounts = @json($enrollCollegeCounts ?? []);

    let yearLevelLabels = @json($yearLevelLabels ?? []);
    let yearLevelCounts = @json($yearLevelCounts ?? []);

    let enrollByCollegeBar = null;
    let studentsByYearLevelDonut = null;
    let enrollmentStatusDonut = null;

    function niceLegend() {
        return {
            position: 'bottom',
            labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                boxWidth: 10,
                padding: 14,
                font: { size: 12, weight: '600' }
            }
        }
    }

    function buildCharts() {
        const collegeCtx = document.getElementById('enrollByCollegeBar');
        if (collegeCtx) {
            enrollByCollegeBar = new Chart(collegeCtx, {
                type: 'bar',
                data: {
                    labels: enrollCollegeLabels,
                    datasets: [{
                        label: 'Enrolled Students',
                        data: enrollCollegeCounts,
                        borderWidth: 1,
                        borderRadius: 10,
                        maxBarThickness: 48
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { ticks: { autoSkip: true, maxRotation: 0 } }
                    }
                }
            });
        }

        const ylCtx = document.getElementById('studentsByYearLevelDonut');
        if (ylCtx) {
            studentsByYearLevelDonut = new Chart(ylCtx, {
                type: 'doughnut',
                data: {
                    labels: yearLevelLabels,
                    datasets: [{
                        data: yearLevelCounts,
                        borderWidth: 2,
                        hoverOffset: 8,
                        radius: '92%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: niceLegend(),
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed ?? 0;
                                    const total = (ctx.dataset.data || []).reduce((a,b)=>a+(Number(b)||0),0);
                                    const pct = total ? ((val/total)*100).toFixed(1) : '0.0';
                                    return ` ${ctx.label}: ${val} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        const statusCtx = document.getElementById('enrollmentStatusDonut');
        if (statusCtx) {
            enrollmentStatusDonut = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Enrolled', 'Dropped', 'Graduated', 'Not Enrolled'],
                    datasets: [{
                        data: [
                            Number(document.getElementById('statEnrolled')?.textContent ?? 0),
                            Number(document.getElementById('statDropped')?.textContent ?? 0),
                            Number(document.getElementById('statGraduated')?.textContent ?? 0),
                            Number(document.getElementById('statNotEnrolled')?.textContent ?? 0),
                        ],
                        borderWidth: 2,
                        hoverOffset: 8,
                        radius: '92%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: niceLegend(),
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed ?? 0;
                                    const total = (ctx.dataset.data || []).reduce((a,b)=>a+(Number(b)||0),0);
                                    const pct = total ? ((val/total)*100).toFixed(1) : '0.0';
                                    return ` ${ctx.label}: ${val} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    async function refreshDashboard() {
        try {
            const response = await fetch('{{ route("admin.dashboard-data") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();

            const setText = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = (val ?? 0);
            };

            setText('kpiTotalStudents', data.kpiTotalStudents);
            setText('kpiEnrolledThisSemester', data.kpiEnrolledThisSemester);
            setText('kpiIncompleteStudents', data.kpiIncompleteStudents);

            setText('statEnrolled', data.statEnrolled);
            setText('statDropped', data.statDropped);
            setText('statGraduated', data.statGraduated);
            setText('statNotEnrolled', data.statNotEnrolled);
            setText('statNotEnrolled2', data.statNotEnrolled);

            setText('countUsers', data.countUsers);
            setText('countEnrollments', data.countEnrollments);
            setText('countColleges', data.countColleges);
            setText('countCourses', data.countCourses);
            setText('countYearLevels', data.countYearLevels);
            setText('countSemesters', data.countSemesters);

            if (enrollmentStatusDonut) {
                enrollmentStatusDonut.data.datasets[0].data = [
                    data.statEnrolled ?? 0,
                    data.statDropped ?? 0,
                    data.statGraduated ?? 0,
                    data.statNotEnrolled ?? 0,
                ];
                enrollmentStatusDonut.update();
            }

            if (enrollByCollegeBar && Array.isArray(data.enrollCollegeLabels) && Array.isArray(data.enrollCollegeCounts)) {
                enrollByCollegeBar.data.labels = data.enrollCollegeLabels;
                enrollByCollegeBar.data.datasets[0].data = data.enrollCollegeCounts;
                enrollByCollegeBar.update();
            }

            if (studentsByYearLevelDonut && Array.isArray(data.yearLevelLabels) && Array.isArray(data.yearLevelCounts)) {
                studentsByYearLevelDonut.data.labels = data.yearLevelLabels;
                studentsByYearLevelDonut.data.datasets[0].data = data.yearLevelCounts;
                studentsByYearLevelDonut.update();
            }

        } catch (error) {
            console.error('Dashboard refresh error:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        buildCharts();
        refreshDashboard();
        setInterval(refreshDashboard, 10000);
    });
</script>

@endif

@endsection     