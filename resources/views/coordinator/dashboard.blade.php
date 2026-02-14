@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --brand:#0b2e5e;
        --brand2:#123f85;
        --muted:#6b7280;
        --card:#ffffff;
        --line:#e5e7eb;
        --bg:#f4f7fb;
    }

    .dash-wrap{ max-width: 1200px; margin:0 auto; }
    .dash-title{
        font-weight:900; letter-spacing:.2px;
        color: var(--brand);
        font-size: 1.5rem;
        margin: 0;
    }
    .dash-sub{ color: var(--muted); font-size: .92rem; }

    .metric-card{
        border:1px solid var(--line);
        border-radius: 16px;
        overflow:hidden;
        background: var(--card);
        box-shadow: 0 10px 25px rgba(11,46,94,.06);
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .metric-card:hover{
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(11,46,94,.10);
    }
    .metric-top{
        padding: 14px 16px;
        display:flex; align-items:center; justify-content:space-between;
        color:#fff;
    }
    .metric-body{
        padding: 14px 16px;
        display:flex; align-items:flex-end; justify-content:space-between; gap:10px;
    }
    .metric-num{ font-size: 1.6rem; font-weight: 900; color: #111827; line-height:1; }
    .metric-label{ font-weight: 800; color:#111827; }
    .metric-note{ color: var(--muted); font-size:.85rem; }

    .grad-1{ background: linear-gradient(135deg, #0b2e5e, #2563eb); }
    .grad-2{ background: linear-gradient(135deg, #0f766e, #22c55e); }
    .grad-3{ background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .grad-4{ background: linear-gradient(135deg, #b45309, #f59e0b); }

    .icon-badge{
        width:42px; height:42px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        display:flex; align-items:center; justify-content:center;
        font-size: 20px;
    }

    .panel{
        border:1px solid var(--line);
        border-radius: 16px;
        background: var(--card);
        box-shadow: 0 10px 25px rgba(11,46,94,.06);
        overflow:hidden;
    }
    .panel-h{
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        background: #fff;
    }
    .panel-title{ font-weight: 900; color: var(--brand); margin:0; font-size: 1rem; }
    .panel-sub{ color: var(--muted); font-size:.85rem; margin:0; }

    .quick-link{
        border:1px solid var(--line);
        background:#fff;
        border-radius: 12px;
        padding: 10px 12px;
        text-decoration:none;
        display:flex; align-items:center; justify-content:space-between;
        font-weight: 800;
        color: #0b2e5e;
        transition: .12s ease;
    }
    .quick-link:hover{ background:#f1f5f9; }
    .chip{
        font-size:.78rem;
        padding:.2rem .55rem;
        border-radius: 999px;
        border:1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: var(--brand);
        font-weight: 800;
        white-space:nowrap;
    }

    .chart-box{ padding: 12px 16px 16px; }
    .chart-canvas{
        width:100% !important;
        height: 320px !important;
    }
    @media (max-width: 768px){
        .chart-canvas{ height: 280px !important; }
    }
</style>

<div class="dash-wrap">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="dash-title">Scholarship Coordinator Dashboard</h1>
            <div class="dash-sub">
                Welcome back, {{ auth()->user()->firstname }}.
                @if($activeSemester)
                    <span class="chip ms-1">Active Semester: {{ $activeSemester->term }} {{ $activeSemester->academic_year }}</span>
                @else
                    <span class="chip ms-1">All Semesters</span>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-bisu-primary" href="{{ route('coordinator.manage-scholars') }}">Manage Scholars</a>
            <a class="btn btn-bisu-secondary" href="{{ route('coordinator.manage-stipends') }}">Manage Stipends</a>
            <a class="btn btn-bisu-secondary" href="{{ route('coordinator.manage-announcements') }}">Announcements</a>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-1">
                    <div class="fw-bold">Total Scholars</div>
                    <div class="icon-badge">🎓</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num">{{ number_format($totalScholars) }}</div>
                        <div class="metric-note">All scholars in the system</div>
                    </div>
                    <div class="chip">+{{ number_format($recentScholars) }} this week</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-2">
                    <div class="fw-bold">Total Students</div>
                    <div class="icon-badge">👥</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num">{{ number_format($totalStudents) }}</div>
                        <div class="metric-note">Student accounts</div>
                    </div>
                    <div class="chip">Users</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-3">
                    <div class="fw-bold">Scholarships</div>
                    <div class="icon-badge">🏅</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num">{{ number_format($totalScholarships) }}</div>
                        <div class="metric-note">Available scholarship programs</div>
                    </div>
                    <div class="chip">Programs</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-4">
                    <div class="fw-bold">Batches</div>
                    <div class="icon-badge">📦</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num">{{ number_format($totalBatches) }}</div>
                        <div class="metric-note">Scholarship batch records</div>
                    </div>
                    <div class="chip">TDP/TES</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts + Quick links --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-h">
                    <div>
                        <p class="panel-title mb-0">Scholars by Scholarship</p>
                        <p class="panel-sub mb-0">Distribution of scholars per scholarship</p>
                    </div>
                    <span class="chip">Pie Chart</span>
                </div>
                <div class="chart-box">
                    <canvas id="pieScholarship" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-h">
                    <div>
                        <p class="panel-title mb-0">Scholars by Course</p>
                        <p class="panel-sub mb-0">Top courses (by number of scholars)</p>
                    </div>
                    <span class="chip">Line Chart</span>
                </div>
                <div class="chart-box">
                    <canvas id="lineCourse" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="panel">
                <div class="panel-h">
                    <div>
                        <p class="panel-title mb-0">Quick Actions</p>
                        <p class="panel-sub mb-0">Shortcuts to common coordinator tasks</p>
                    </div>
                    <span class="chip">Links</span>
                </div>

                <div class="p-3">
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <a class="quick-link" href="{{ route('coordinator.enrollment-records') }}">
                                <span>Students Record</span><span>→</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <a class="quick-link" href="{{ route('coordinator.manage-stipend-releases') }}">
                                <span>Stipend Release Schedule</span><span>→</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <a class="quick-link" href="{{ route('clusters.index') }}">
                                <span>Student Inquiries</span><span>→</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-h">
            <div>
                <p class="panel-title mb-0">Scholars Summary Table</p>
                <p class="panel-sub mb-0">Number of scholars in every scholarship</p>
            </div>
            <span class="chip">Table</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Scholarship</th>
                        <th class="text-center">Total Scholars</th>
                        <th style="width: 35%;">Share</th>
                        <th class="text-end pe-3">Percent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tableRows as $r)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $r->scholarship_name }}</td>
                            <td class="text-center fw-bold">{{ number_format($r->total) }}</td>
                            <td>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $r->percent }}%"
                                         aria-valuenow="{{ $r->percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end pe-3 fw-bold">{{ $r->percent }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Chart.js (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ✅ PIE CHART: scholars by scholarship
    const pieLabels = @json($pieLabels);
    const pieData   = @json($pieData);

    const pieCtx = document.getElementById('pieScholarship');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true }
                }
            }
        });
    }

    // ✅ LINE CHART: scholars by course
    const lineLabels = @json($lineLabels);
    const lineData   = @json($lineData);

    const lineCtx = document.getElementById('lineCourse');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Total Scholars',
                    data: lineData,
                    tension: 0.3,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: true, position: 'bottom' }
                }
            }
        });
    }

});
</script>
@endsection
