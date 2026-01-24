
<?php $fullWidth = true; ?>  


<?php $__env->startSection('content'); ?>
<div class="p-6">  
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Add Course Button (Upper Right, Enhanced Design) -->
    <div class="flex justify-end mb-6">
        <a href="<?php echo e(route('admin.courses.create')); ?>" class="inline-flex items-center bg-black text-black hover:bg-gray-800 font-bold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <span class="mr-2">+</span> Add Course
        </a>
    </div>

    <!-- Search Bar (Filter by Course Name, Description, or College) -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search by Course Name, Description, or College..." class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Table Card (Full-width, internal scrolling, compressed rows) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto max-h-[calc(100vh-250px)] overflow-y-auto">  
            <table class="table-auto w-full border-collapse text-center min-w-full" id="coursesTable">  
                <thead class="bg-blue-200 text-black sticky top-0">  
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Course Name</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Course Description</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">College</th>
                        <th class="border border-gray-300 px-3 py-2 font-bold text-sm uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition duration-150 even:bg-gray-25">
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($course->course_name); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($course->course_description ?? 'N/A'); ?></td>
                            <td class="border border-gray-300 px-3 py-2 text-gray-800"><?php echo e($course->college->college_name ?? 'N/A'); ?></td>
                            <td class="border border-gray-300 px-3 py-2 space-x-2">
                                <a href="<?php echo e(route('admin.courses.edit', $course->id)); ?>" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">✏️</span> Edit
                                </a>
                                <a href="<?php echo e(route('admin.courses.delete', $course->id)); ?>" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-black font-medium py-1 px-3 rounded shadow transition duration-200 text-sm">
                                    <span class="mr-1">🗑️</span> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(empty($courses)): ?>
                        <tr id="noResultsRow">
                            <td colspan="4" class="px-3 py-4 text-gray-500 text-center">No courses found. <a href="<?php echo e(route('admin.courses.create')); ?>" class="text-blue-500 underline hover:text-blue-700">Add one now</a>.</td>
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
        const rows = document.querySelectorAll('#coursesTable tbody tr');
        let hasResults = false;

        rows.forEach(row => {
            if (row.id === 'noResultsRow') return;  // Skip the no-results row
            const cells = row.querySelectorAll('td');
            const courseName = cells[0].textContent.toLowerCase();
            const description = cells[1].textContent.toLowerCase();
            const college = cells[2].textContent.toLowerCase();

            if (courseName.includes(query) || description.includes(query) || college.includes(query)) {
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/super-admin/courses.blade.php ENDPATH**/ ?>