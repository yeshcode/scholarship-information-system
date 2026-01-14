
<?php $fullWidth = true; ?>  


<?php $__env->startSection('content'); ?>
<div class="p-6">  
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <!-- Buttons (Upper Right, Enhanced Design) -->
    <div class="flex justify-end mb-6 space-x-4">
        <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="inline-flex items-center bg-black text-black hover:bg-gray-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add Enrollment
        </a>
        <a href="<?php echo e(route('admin.enrollments.enroll-students')); ?>" class="inline-flex items-center bg-purple-600 text-black hover:bg-purple-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">📚</span> Enroll Students
        </a>
    </div>

    <!-- Semester Filter (New: Added above search) -->
    <div class="mb-6">
        <form method="GET" action="<?php echo e(route('admin.enrollments')); ?>" class="flex items-center space-x-4">  
            <label for="semesterSelect" class="font-bold text-gray-700">Filter by Semester:</label>
            <select name="semester_id" id="semesterSelect" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                <option value="">All Semesters</option>
                <?php $__currentLoopData = $semesters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($semester->id); ?>" <?php echo e(($selectedSemesterId ?? '') == $semester->id ? 'selected' : ''); ?>>
                        <?php echo e($semester->term); ?> <?php echo e($semester->academic_year); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>

    <!-- Search Bar (Filter by User, Semester, Section, or Status) -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search by Last Name, First Name, Middle Name, Semester, Section, Course, or Status..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table Card (Full-width, internal scrolling, compressed rows) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-250px)] overflow-y-auto">  
            <table class="table-auto w-full border-collapse text-center min-w-full" id="enrollmentsTable">  
                <thead class="bg-blue-200 text-black sticky top-0">  
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Last Name</th>  
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">First Name</th>  
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Middle Name</th>  
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Semester</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Section</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Course</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Status</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $enrollments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->user->lastname ?? 'N/A'); ?></td>  
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->user->firstname ?? 'N/A'); ?></td>  
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->user->middlename ?? 'N/A'); ?></td>  
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->semester->term ?? 'N/A'); ?> <?php echo e($enrollment->semester->academic_year ?? ''); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->section->section_name ?? 'N/A'); ?> (<?php echo e($enrollment->section->course->course_name ?? ''); ?>)</td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->course->course_name ?? 'N/A'); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($enrollment->status); ?></td>
                            <td class="border border-gray-300 px-3 py-2 space-x-2">
                                <a href="<?php echo e(route('admin.enrollments.edit', $enrollment->id)); ?>" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">✏️</span> Edit
                                </a>
                                <a href="<?php echo e(route('admin.enrollments.delete', $enrollment->id)); ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">🗑️</span> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(empty($enrollments)): ?>
                        <tr id="noResultsRow">
                            <td colspan="8" class="px-3 py-4 text-gray-500 text-center">No enrollments found. <a href="<?php echo e(route('admin.enrollments.create')); ?>" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>  
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for Search Filtering -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#enrollmentsTable tbody tr');
        let hasResults = false;

        rows.forEach(row => {
            if (row.id === 'noResultsRow') return;  // Skip the no-results row
            const cells = row.querySelectorAll('td');
            const lastName = cells[0].textContent.toLowerCase();  // Last Name
            const firstName = cells[1].textContent.toLowerCase();  // First Name
            const middleName = cells[2].textContent.toLowerCase();  // Middle Name
            const semester = cells[3].textContent.toLowerCase();
            const section = cells[4].textContent.toLowerCase();
            const course = cells[5].textContent.toLowerCase();
            const status = cells[6].textContent.toLowerCase();

            if (lastName.includes(query) || firstName.includes(query) || middleName.includes(query) || semester.includes(query) || section.includes(query) || course.includes(query) || status.includes(query)) {
                row.style.display = '';
                hasResults = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no-results message
        const noResultsRow = document.getElementById('noResultsRow');
        noResultsRow.style.display = hasResults ? 'none' : '';
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/enrollments.blade.php ENDPATH**/ ?>