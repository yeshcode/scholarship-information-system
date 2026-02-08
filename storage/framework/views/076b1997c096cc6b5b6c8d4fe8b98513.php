

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6">

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Welcome, <?php echo e(auth()->user()->firstname); ?> 👋
            </h1>
            <p class="text-slate-600 text-sm">
                View announcements, scholarships, and track your questions in one place.
            </p>
        </div>

        
        <div class="inline-flex items-center px-3 py-1 rounded-full border border-slate-300 bg-white text-slate-700 text-sm">
            Student Dashboard
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <a href="<?php echo e(route('student.announcements')); ?>"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Announcements</div>
            <div class="text-slate-600 text-sm">See latest updates and deadlines.</div>
        </a>

        <a href="<?php echo e(route('student.scholarships')); ?>"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Scholarships</div>
            <div class="text-slate-600 text-sm">Browse available scholarship opportunities.</div>
        </a>

        <a href="<?php echo e(route('questions.create')); ?>"
           class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition">
            <div class="text-slate-800 font-bold mb-1">Ask a Question</div>
            <div class="text-slate-600 text-sm">Submit your concern to the coordinator.</div>
        </a>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Recent Announcements</h2>
                    <a class="text-sm text-blue-700 hover:underline"
                       href="<?php echo e(route('student.announcements')); ?>">View all</a>
                </div>
            </div>
            <div class="p-5">
                <?php $__empty_1 = true; $__currentLoopData = $announcements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="py-3 border-b last:border-b-0 border-slate-100">
                        <div class="font-semibold text-slate-800"><?php echo e($a->title); ?></div>
                        <div class="text-sm text-slate-600 line-clamp-2"><?php echo e($a->description); ?></div>
                        <div class="text-xs text-slate-500 mt-1">
                            Posted: <?php echo e(optional($a->created_at)->format('M d, Y')); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-600 text-sm">No announcements yet.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-5 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">My Questions</h2>
                    <a class="text-sm text-blue-700 hover:underline"
                       href="<?php echo e(route('questions.my')); ?>">View all</a>
                </div>
            </div>
            <div class="p-5">
                <?php $__empty_1 = true; $__currentLoopData = $myRecentQuestions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="py-3 border-b last:border-b-0 border-slate-100">
                        <div class="text-slate-800 font-semibold line-clamp-2">
                            <?php echo e($q->question); ?>

                        </div>
                        <div class="text-xs mt-1 inline-flex items-center px-2 py-0.5 rounded-full border
                            <?php echo e($q->status === 'Answered' ? 'border-green-300 text-green-700 bg-green-50' : 'border-amber-300 text-amber-700 bg-amber-50'); ?>">
                            <?php echo e($q->status ?? 'Pending'); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-600 text-sm">You haven’t submitted a question yet.</p>
                <?php endif; ?>
            </div>
        </div>

        
<div class="bg-white border border-slate-200 rounded-xl shadow-sm">
    <div class="p-5 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Notifications</h2>

            <a class="text-sm text-blue-700 hover:underline"
               href="<?php echo e(route('student.notifications')); ?>">
                View all
                <?php if(($unreadCount ?? 0) > 0): ?>
                    <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs bg-blue-700 text-white">
                        <?php echo e($unreadCount); ?>

                    </span>
                <?php endif; ?>
            </a>
        </div>
        <p class="text-slate-600 text-sm mt-1">Latest updates (stipend & announcements)</p>
    </div>

    <div class="p-5">
        <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isUnread = !$notification->is_read;
                $openUrl = route('student.notifications.open', $notification->id);
            ?>

            <a href="<?php echo e($openUrl); ?>" class="block rounded-lg border border-slate-200 p-3 mb-2 hover:bg-slate-50 transition
                <?php echo e($isUnread ? 'bg-blue-50 border-blue-200' : ''); ?>">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold"
                         style="background:#003366;">
                        🔔
                    </div>

                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="font-semibold text-slate-800">
                                <?php echo e($notification->title); ?>

                            </div>

                            <?php if($isUnread): ?>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-700 text-white">New</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-sm text-slate-600 mt-1" style="white-space: pre-line;">
                            <?php echo e(\Illuminate\Support\Str::limit($notification->message, 90)); ?>

                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            <?php echo e($notification->sent_at ? $notification->sent_at->format('M d, Y • h:i A') : 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-slate-600 text-sm">No notifications yet.</p>
        <?php endif; ?>
    </div>
</div>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/dashboard.blade.php ENDPATH**/ ?>