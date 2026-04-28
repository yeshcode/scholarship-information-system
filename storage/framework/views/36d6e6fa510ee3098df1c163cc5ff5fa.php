

<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --brand:#0b2e5e;
        --brand2:#123f85;
        --muted:#6b7280;
        --card:#ffffff;
        --line:#e5e7eb;
        --bg:#f4f7fb;
    }

    .dash-wrap{
        max-width: 1200px;
        margin: 0 auto;
    }

    .dash-title{
        font-weight: 900;
        letter-spacing: .2px;
        color: var(--brand);
        font-size: 1.5rem;
        margin: 0;
    }

    .dash-sub{
        color: var(--muted);
        font-size: .92rem;
    }

    .metric-card{
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
        background: var(--card);
        box-shadow: 0 10px 25px rgba(11,46,94,.06);
        transition: transform .12s ease, box-shadow .12s ease;
        height: 100%;
    }

    .metric-card:hover{
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(11,46,94,.10);
    }

    .metric-top{
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
    }

    .metric-body{
        padding: 14px 16px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 10px;
    }

    .metric-num{
        font-size: 1.7rem;
        font-weight: 900;
        color: #111827;
        line-height: 1;
    }

    .metric-note{
        color: var(--muted);
        font-size: .85rem;
        margin-top: 4px;
    }

    .grad-1{ background: linear-gradient(135deg, #0b2e5e, #2563eb); }
    .grad-2{ background: linear-gradient(135deg, #0f766e, #22c55e); }
    .grad-3{ background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .grad-4{ background: linear-gradient(135deg, #b45309, #f59e0b); }

    .icon-badge{
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .panel{
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--card);
        box-shadow: 0 10px 25px rgba(11,46,94,.06);
        overflow: hidden;
        height: 100%;
    }

    .panel-h{
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: #fff;
    }

    .panel-title{
        font-weight: 900;
        color: var(--brand);
        margin: 0;
        font-size: 1rem;
    }

    .panel-sub{
        color: var(--muted);
        font-size: .85rem;
        margin: 0;
    }

    .chip{
        font-size: .78rem;
        padding: .2rem .55rem;
        border-radius: 999px;
        border: 1px solid rgba(11,46,94,.18);
        background: rgba(11,46,94,.06);
        color: var(--brand);
        font-weight: 800;
        white-space: nowrap;
    }

    .chart-box{
        padding: 16px;
    }

    .chart-wrap{
        position: relative;
        height: 340px;
    }


    @media (max-width: 768px){
        .chart-wrap{
            height: 280px;
        }
    }
</style>

<div class="dash-wrap">

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h1 class="dash-title">Scholarship Coordinator Dashboard</h1>
            <div class="dash-sub">
                Welcome back, <?php echo e(auth()->user()->firstname); ?>.
                <?php if($activeSemester): ?>
                    <span class="chip ms-1">Active Semester: <?php echo e($activeSemester->term); ?> <?php echo e($activeSemester->academic_year); ?></span>
                <?php else: ?>
                    <span class="chip ms-1">All Semesters</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-bisu-primary" href="<?php echo e(route('coordinator.manage-scholars')); ?>">Manage Scholars</a>
            <a class="btn btn-bisu-secondary" href="<?php echo e(route('coordinator.manage-stipends')); ?>">Manage Stipends</a>
            <a class="btn btn-bisu-secondary" href="<?php echo e(route('coordinator.manage-announcements')); ?>">Announcements</a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-1">
                    <div class="fw-bold">Total Scholars</div>
                    <div class="icon-badge">🎓</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num"><?php echo e(number_format($totalScholars)); ?></div>
                        <div class="metric-note">Active scholars in the system</div>
                    </div>
                    <div class="chip">Current</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-2">
                    <div class="fw-bold">Scholarships</div>
                    <div class="icon-badge">🏅</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num"><?php echo e(number_format($totalScholarships)); ?></div>
                        <div class="metric-note">Scholarship programs available</div>
                    </div>
                    <div class="chip">Programs</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-3">
                    <div class="fw-bold">Top Course</div>
                    <div class="icon-badge">📘</div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num"><?php echo e(number_format($topCourseTotal)); ?></div>
                        <div class="metric-note"><?php echo e($topCourseName); ?></div>
                    </div>
                    <div class="chip">Highest</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="metric-card">
                <div class="metric-top grad-4">
                    <div class="fw-bold">Growth Trend</div>
                    <div class="icon-badge">
                        <?php if($growthTrend > 0): ?>
                            📈
                        <?php elseif($growthTrend < 0): ?>
                            📉
                        <?php else: ?>
                            ➖
                        <?php endif; ?>
                    </div>
                </div>
                <div class="metric-body">
                    <div>
                        <div class="metric-num">
                            <?php if($growthTrend > 0): ?>
                                +<?php echo e(number_format($growthTrend)); ?>

                            <?php else: ?>
                                <?php echo e(number_format($growthTrend)); ?>

                            <?php endif; ?>
                        </div>
                        <div class="metric-note">Compared to previous semester</div>
                    </div>
                    <div class="chip"><?php echo e($growthTrendLabel); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-h">
                    <div>
                        <p class="panel-title mb-0">Scholars Per Year</p>
                        <p class="panel-sub mb-0">Trend of scholar records added each year</p>
                    </div>
                    <span class="chip">Line Chart</span>
                </div>
                <div class="chart-box">
                    <div class="chart-wrap">
                        <canvas id="yearChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel">
                <div class="panel-h">
                    <div>
                        <p class="panel-title mb-0">Scholars by Course</p>
                        <p class="panel-sub mb-0">Top 10 courses with the most scholars</p>
                    </div>
                    <span class="chip">Bar Chart</span>
                </div>
                <div class="chart-box">
                    <div class="chart-wrap">
                        <canvas id="courseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<footer class="border-top py-3 mt-4 bg-white">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
        <div class="text-muted small">
            © <?php echo e(date('Y')); ?> BISU Candijay Campus • Scholarship Management Information System
        </div>
        <div class="text-muted small">
            Scholarship Panel
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ======================================================
    // Line Chart: Scholars Per Year
    // ======================================================
    const yearLabels = <?php echo json_encode($yearLabels, 15, 512) ?>;
    const yearData   = <?php echo json_encode($yearData, 15, 512) ?>;

    const yearCtx = document.getElementById('yearChart');
    if (yearCtx) {
        new Chart(yearCtx, {
            type: 'line',
            data: {
                labels: yearLabels,
                datasets: [{
                    label: 'Number of Scholars',
                    data: yearData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 3
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // ======================================================
    // Bar Chart: Scholars By Course
    // ======================================================
    const courseLabels = <?php echo json_encode($courseLabels, 15, 512) ?>;
    const courseData   = <?php echo json_encode($courseData, 15, 512) ?>;

    const courseCtx = document.getElementById('courseChart');
    if (courseCtx) {
        new Chart(courseCtx, {
            type: 'bar',
            data: {
                labels: courseLabels,
                datasets: [{
                    label: 'Total Scholars',
                    data: courseData,
                    backgroundColor: [
                        '#2563eb',
                        '#22c55e',
                        '#f59e0b',
                        '#a855f7',
                        '#ef4444',
                        '#14b8a6',
                        '#3b82f6',
                        '#f97316',
                        '#6366f1',
                        '#84cc16'
                    ],
                    borderRadius: 8,
                    maxBarThickness: 42
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 20
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/dashboard.blade.php ENDPATH**/ ?>