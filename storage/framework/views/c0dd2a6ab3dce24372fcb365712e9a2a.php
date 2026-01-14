  

<?php $__env->startSection('page-content'); ?>

<?php
    $page = request('page');
?>

<?php if($page === 'sections'): ?>
    <?php echo $__env->make('super-admin.sections', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'year-levels'): ?>
    <?php echo $__env->make('super-admin.year-levels', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'colleges'): ?>
    <?php echo $__env->make('super-admin.colleges', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'courses'): ?>
    <?php echo $__env->make('super-admin.courses', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'semesters'): ?>
    <?php echo $__env->make('super-admin.semesters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'enrollments'): ?>
    <?php echo $__env->make('super-admin.enrollments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'manage-users'): ?>
    <?php echo $__env->make('super-admin.users', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php elseif($page === 'user-type'): ?>
    <?php echo $__env->make('super-admin.user-type', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php else: ?>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h1 class="fw-bold text-primary" style="font-size: 2rem;">Super Admin Dashboard</h1>
        <p class="text-muted mb-0">Modern analytics overview of students and enrollments</p>
    </div>

    

    
    <div class="row g-4">

        
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



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ===== DATA PASSED FROM CONTROLLER =====
    let coursePieLabels      = <?php echo json_encode($coursePieLabels ?? [], 15, 512) ?>;
    let coursePieCounts      = <?php echo json_encode($coursePieCounts ?? [], 15, 512) ?>;
    let studentYearLabels    = <?php echo json_encode($studentYearLabels ?? [], 15, 512) ?>;
    let studentYearCounts    = <?php echo json_encode($studentYearCounts ?? [], 15, 512) ?>;
    let semCourseLabels      = <?php echo json_encode($semCourseLabels ?? [], 15, 512) ?>;
    let semCourseCourseNames = <?php echo json_encode($semCourseCourseNames ?? [], 15, 512) ?>;
    let semCourseMatrix      = <?php echo json_encode($semCourseMatrix ?? [], 15, 512) ?>;

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
            const response = await fetch('<?php echo e(route("admin.dashboard-data")); ?>');
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
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/dashboard.blade.php ENDPATH**/ ?>