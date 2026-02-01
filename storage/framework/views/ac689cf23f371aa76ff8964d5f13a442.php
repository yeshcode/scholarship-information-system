

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6">

    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Reports</h1>
        <p class="text-slate-600 text-sm">
            View and generate summary reports related to scholarships, scholars, and stipends.
        </p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-slate-800 mb-2">
                Scholars Report
            </h2>
            <p class="text-sm text-slate-600 mb-4">
                Overview of total scholars, active scholars, and scholarship distribution.
            </p>

            <button
                class="px-4 py-2 rounded-md bg-[#003366] text-white text-sm font-semibold hover:bg-[#002244] transition">
                View Report
            </button>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-slate-800 mb-2">
                Stipends Report
            </h2>
            <p class="text-sm text-slate-600 mb-4">
                Summary of stipend releases, amounts, and release schedules.
            </p>

            <button
                class="px-4 py-2 rounded-md bg-[#003366] text-white text-sm font-semibold hover:bg-[#002244] transition">
                View Report
            </button>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-slate-800 mb-2">
                Scholarships Report
            </h2>
            <p class="text-sm text-slate-600 mb-4">
                List of available scholarships and their current status.
            </p>

            <button
                class="px-4 py-2 rounded-md bg-[#003366] text-white text-sm font-semibold hover:bg-[#002244] transition">
                View Report
            </button>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-slate-800 mb-2">
                Student Queries Report
            </h2>
            <p class="text-sm text-slate-600 mb-4">
                Summary of student inquiries, grouped questions, and response status.
            </p>

            <button
                class="px-4 py-2 rounded-md bg-[#003366] text-white text-sm font-semibold hover:bg-[#002244] transition">
                View Report
            </button>
        </div>

    </div>

    
    <div class="mt-8 text-sm text-slate-500">
        Reports are generated for monitoring, documentation, and decision-making purposes.
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/reports.blade.php ENDPATH**/ ?>