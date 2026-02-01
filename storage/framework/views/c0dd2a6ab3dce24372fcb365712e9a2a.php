

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

    
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold mb-1" style="color:#0b2e5e;">Super Admin Dashboard</h1>
            <div class="text-muted">
                Overview of users, student records, and enrollments
                <?php if(!empty($activeSemesterName)): ?>
                    <span class="mx-2">•</span>
                    <span class="fw-semibold"><?php echo e($activeSemesterName); ?></span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>" class="btn btn-outline-primary btn-sm">
                Manage Users
            </a>
            <a href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>" class="btn btn-outline-primary btn-sm">
                Enrollment Records
            </a>
            <a href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>" class="btn btn-outline-primary btn-sm">
                Semesters
            </a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Users</div>
                    <div class="fs-3 fw-bold" id="kpiTotalUsers"><?php echo e($kpiTotalUsers ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Active Users</div>
                    <div class="fs-3 fw-bold" id="kpiActiveUsers"><?php echo e($kpiActiveUsers ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Students</div>
                    <div class="fs-3 fw-bold" id="kpiTotalStudents"><?php echo e($kpiTotalStudents ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Enrolled (This Semester)</div>
                    <div class="fs-3 fw-bold" id="kpiEnrolledThisSemester"><?php echo e($kpiEnrolledThisSemester ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Inactive Accounts</div>
                    <div class="fs-3 fw-bold" id="kpiInactiveUsers"><?php echo e($kpiInactiveUsers ?? 0); ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Incomplete Student Profiles</div>
                    <div class="fs-3 fw-bold" id="kpiIncompleteStudents"><?php echo e($kpiIncompleteStudents ?? 0); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                    <div class="fw-bold" style="color:#0b2e5e;">Enrollments by College (Current Semester)</div>
                    <div class="text-muted small">Auto-refresh enabled</div>
                </div>
                <div class="card-body">
                    <div style="height:340px;">
                        <canvas id="enrollByCollegeBar"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <div class="fw-bold" style="color:#0b2e5e;">Users by Role</div>
                </div>
                <div class="card-body">
                    <div style="height:340px;">
                        <canvas id="usersByRoleDonut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<div class="row g-4 mt-1">

    
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                <div class="fw-bold" style="color:#0b2e5e;">Enrollment Status Overview (Current Semester)</div>
                <span class="badge rounded-pill bg-light text-secondary border">Auto-refresh</span>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 bg-white h-100">
                            <div class="text-muted small">Enrolled</div>
                            <div class="fs-3 fw-bold" id="statEnrolled"><?php echo e($statEnrolled ?? 0); ?></div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 bg-white h-100">
                            <div class="text-muted small">Dropped</div>
                            <div class="fs-3 fw-bold" id="statDropped"><?php echo e($statDropped ?? 0); ?></div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 bg-white h-100">
                            <div class="text-muted small">Graduated</div>
                            <div class="fs-3 fw-bold" id="statGraduated"><?php echo e($statGraduated ?? 0); ?></div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="border rounded-3 p-3 bg-white h-100">
                            <div class="text-muted small">Not Enrolled</div>
                            <div class="fs-3 fw-bold" id="statNotEnrolled"><?php echo e($statNotEnrolled ?? 0); ?></div>
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <div class="text-muted small mb-2">Distribution</div>
                    <div style="height:280px;">
                        <canvas id="enrollmentStatusDonut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <div class="fw-bold" style="color:#0b2e5e;">Quick Admin Panel</div>
                <div class="text-muted small">Shortcuts to manage core data</div>
            </div>

            <div class="card-body">
                <div class="d-grid gap-2">

                    <a class="btn btn-outline-primary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'manage-users'])); ?>">
                        <span>Manage Users</span>
                        <span class="badge bg-light text-secondary border" id="countUsers"><?php echo e($countUsers ?? 0); ?></span>
                    </a>

                    <a class="btn btn-outline-primary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'enrollments'])); ?>">
                        <span>Enrollment Records</span>
                        <span class="badge bg-light text-secondary border" id="countEnrollments"><?php echo e($countEnrollments ?? 0); ?></span>
                    </a>

                    <a class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'colleges'])); ?>">
                        <span>Colleges</span>
                        <span class="badge bg-light text-secondary border" id="countColleges"><?php echo e($countColleges ?? 0); ?></span>
                    </a>

                    <a class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'courses'])); ?>">
                        <span>Courses</span>
                        <span class="badge bg-light text-secondary border" id="countCourses"><?php echo e($countCourses ?? 0); ?></span>
                    </a>

                    <a class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'year-levels'])); ?>">
                        <span>Year Levels</span>
                        <span class="badge bg-light text-secondary border" id="countYearLevels"><?php echo e($countYearLevels ?? 0); ?></span>
                    </a>

                    <a class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center"
                       href="<?php echo e(route('admin.dashboard', ['page' => 'semesters'])); ?>">
                        <span>Semesters</span>
                        <span class="badge bg-light text-secondary border" id="countSemesters"><?php echo e($countSemesters ?? 0); ?></span>
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
            © <?php echo e(date('Y')); ?> BISU Candijay Campus • Scholarship Management Information System
        </div>
        <div class="text-muted small">
            Super Admin Panel
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // =====================
    // INITIAL DATA FROM CONTROLLER (safe fallbacks)
    // =====================

    // Enrollments by College
    let enrollCollegeLabels = <?php echo json_encode($enrollCollegeLabels ?? [], 15, 512) ?>;
    let enrollCollegeCounts = <?php echo json_encode($enrollCollegeCounts ?? [], 15, 512) ?>;

    // Users by Role
    let roleLabels = <?php echo json_encode($roleLabels ?? [], 15, 512) ?>;
    let roleCounts = <?php echo json_encode($roleCounts ?? [], 15, 512) ?>;

    // Chart instances
    let enrollByCollegeBar = null;
    let usersByRoleDonut = null;
    let enrollmentStatusDonut = null;


    function buildCharts() {
        // Enrollments by College - Bar
        const collegeCtx = document.getElementById('enrollByCollegeBar');
        if (collegeCtx) {
            enrollByCollegeBar = new Chart(collegeCtx, {
                type: 'bar',
                data: {
                    labels: enrollCollegeLabels,
                    datasets: [{
                        label: 'Enrolled Students',
                        data: enrollCollegeCounts,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Users by Role - Donut
        const roleCtx = document.getElementById('usersByRoleDonut');
        if (roleCtx) {
            usersByRoleDonut = new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: roleLabels,
                    datasets: [{
                        data: roleCounts,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
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
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }


    async function refreshDashboard() {
        try {
            const response = await fetch('<?php echo e(route("admin.dashboard-data")); ?>', {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();

            // ===== KPI Updates =====
            const setText = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = (val ?? 0);
            };

            setText('kpiTotalUsers', data.kpiTotalUsers);
            setText('kpiActiveUsers', data.kpiActiveUsers);
            setText('kpiTotalStudents', data.kpiTotalStudents);
            setText('kpiEnrolledThisSemester', data.kpiEnrolledThisSemester);
            setText('kpiInactiveUsers', data.kpiInactiveUsers);
            setText('kpiIncompleteStudents', data.kpiIncompleteStudents);

            // ===== Alerts Updates =====
            setText('alertMissingCourse', data.alertMissingCourse);
            setText('alertMissingYearLevel', data.alertMissingYearLevel);
            setText('alertMissingCollege', data.alertMissingCollege);
            setText('alertMissingEmail', data.alertMissingEmail);

            // ===== Enrollment Status numbers =====
            setText('statEnrolled', data.statEnrolled);
            setText('statDropped', data.statDropped);
            setText('statGraduated', data.statGraduated);
            setText('statNotEnrolled', data.statNotEnrolled);

            // ===== Quick Admin Panel counts =====
            setText('countUsers', data.countUsers);
            setText('countEnrollments', data.countEnrollments);
            setText('countColleges', data.countColleges);
            setText('countCourses', data.countCourses);
            setText('countYearLevels', data.countYearLevels);
            setText('countSemesters', data.countSemesters);

            // ===== Update Enrollment Status donut =====
            if (enrollmentStatusDonut) {
                enrollmentStatusDonut.data.datasets[0].data = [
                    data.statEnrolled ?? 0,
                    data.statDropped ?? 0,
                    data.statGraduated ?? 0,
                    data.statNotEnrolled ?? 0,
                ];
                enrollmentStatusDonut.update();
            }


            // ===== Recent Activity Updates =====
            const body = document.getElementById('recentActivityBody');
            if (body && Array.isArray(data.recentActivity)) {
                body.innerHTML = data.recentActivity.length
                    ? data.recentActivity.map(item => `
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-secondary">${item.type ?? 'Activity'}</span>
                            </td>
                            <td class="text-muted">${item.detail ?? '-'}</td>
                            <td class="text-end pe-3 text-muted">${item.date ?? '-'}</td>
                        </tr>
                    `).join('')
                    : `
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No recent activity yet.
                            </td>
                        </tr>
                    `;
            }

            // ===== Update Chart: Enrollments by College =====
            if (enrollByCollegeBar && Array.isArray(data.enrollCollegeLabels) && Array.isArray(data.enrollCollegeCounts)) {
                enrollCollegeLabels = data.enrollCollegeLabels;
                enrollCollegeCounts = data.enrollCollegeCounts;

                enrollByCollegeBar.data.labels = enrollCollegeLabels;
                enrollByCollegeBar.data.datasets[0].data = enrollCollegeCounts;
                enrollByCollegeBar.update();
            }

            // ===== Update Chart: Users by Role =====
            if (usersByRoleDonut && Array.isArray(data.roleLabels) && Array.isArray(data.roleCounts)) {
                roleLabels = data.roleLabels;
                roleCounts = data.roleCounts;

                usersByRoleDonut.data.labels = roleLabels;
                usersByRoleDonut.data.datasets[0].data = roleCounts;
                usersByRoleDonut.update();
            }

        } catch (error) {
            console.error('Dashboard refresh error:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        buildCharts();

        // ✅ run once immediately
        refreshDashboard();

        // ✅ refresh every 10 seconds
        setInterval(refreshDashboard, 10000);
    });
</script>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.super-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/dashboard.blade.php ENDPATH**/ ?>