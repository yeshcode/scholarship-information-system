

<?php $__env->startSection('content'); ?>
<div class="container-fluid container-xl py-4">

    
    <div class="bisu-hero p-4 p-md-5 rounded-4 shadow-sm mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="text-white-50 small mb-1">Scholarship Details</div>
                <h2 class="text-white fw-bold mb-1 wrap-anywhere">
                    <?php echo e($scholarship->scholarship_name); ?>

                </h2>
                <div class="text-white-50">
                    Full details, requirements, and information.
                </div>
            </div>

            <div class="d-flex gap-2">
                <?php if(!empty($scholarship->status)): ?>
                    <?php
                        $statusRaw = strtolower(trim((string)($scholarship->status ?? '')));
                        $badgeClass = match(true) {
                            str_contains($statusRaw, 'open') => 'bg-success-subtle text-success border border-success-subtle',
                            str_contains($statusRaw, 'ongoing') => 'bg-success-subtle text-success border border-success-subtle',
                            str_contains($statusRaw, 'available') => 'bg-success-subtle text-success border border-success-subtle',
                            str_contains($statusRaw, 'closed') => 'bg-danger-subtle text-danger border border-danger-subtle',
                            str_contains($statusRaw, 'pending') => 'bg-warning-subtle text-warning border border-warning-subtle',
                            default => 'bg-light text-dark border',
                        };
                    ?>
                    <span class="badge rounded-pill px-3 py-2 <?php echo e($badgeClass); ?> align-self-start">
                        <?php echo e($scholarship->status); ?>

                    </span>
                <?php endif; ?>

                <a href="<?php echo e(route('student.scholarships.index')); ?>" class="btn btn-light rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Benefactor</div>
                            <div class="fw-semibold wrap-anywhere">
                                <?php echo e($scholarship->benefactor ?? 'N/A'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Requirements</div>
                            <div class="fw-semibold">
                                <?php echo e(!empty($scholarship->requirements) ? 'Available' : 'Not specified'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-bubble bisu-soft">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Description</div>
                            <div class="fw-semibold">
                                <?php echo e(!empty($scholarship->description) ? 'Available' : 'Not specified'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-white border-0 rounded-top-4 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text text-primary"></i>
                    <div class="fw-bold text-bisu">Description</div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <?php if(!empty($scholarship->description)): ?>
                        <div class="text-muted" style="white-space: pre-line;">
                            <?php echo e($scholarship->description); ?>

                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-file-text"></i></div>
                            <div class="fw-semibold">No description provided</div>
                            <div class="text-muted small">The coordinator may add details soon.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-white border-0 rounded-top-4 d-flex align-items-center gap-2">
                    <i class="bi bi-list-check text-success"></i>
                    <div class="fw-bold text-bisu">Requirements</div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <?php if(!empty($scholarship->requirements)): ?>
                        <div class="text-muted" style="white-space: pre-line;">
                            <?php echo e($scholarship->requirements); ?>

                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-clipboard-check"></i></div>
                            <div class="fw-semibold">No requirements listed</div>
                            <div class="text-muted small">Please check again later.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    :root{
        --bisu-blue:#003366;
        --bisu-blue-2:#0b4a85;
        --bisu-soft:#eef6ff;
    }

    .text-bisu{ color: var(--bisu-blue) !important; }

    .bisu-hero{
        background: linear-gradient(135deg, var(--bisu-blue) 0%, var(--bisu-blue-2) 55%, #0e5aa7 100%);
    }

    .icon-bubble{
        width: 44px;
        height: 44px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex: 0 0 auto;
    }

    .bisu-soft{
        background: var(--bisu-soft);
        color: var(--bisu-blue);
        border: 1px solid rgba(0,51,102,.10);
    }

    .wrap-anywhere{
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .empty-state{
        min-height: 220px;
        border: 1px dashed rgba(0,0,0,.15);
        border-radius: 18px;
        display:flex;
        flex-direction: column;
        align-items:center;
        justify-content:center;
        text-align:center;
        padding: 1.25rem;
        background: rgba(255,255,255,.55);
    }

    .empty-icon{
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: var(--bisu-soft);
        color: var(--bisu-blue);
        border: 1px solid rgba(0,51,102,.12);
        font-size: 1.4rem;
        margin-bottom: .6rem;
    }

    /* ===== Mobile responsiveness patch (keep same design) ===== */
@media (max-width: 575.98px){

    /* container spacing */
    .container-fluid.container-xl{
        padding-left: 12px !important;
        padding-right: 12px !important;
    }

    /* hero spacing + readable title */
    .bisu-hero{
        padding: 16px !important; /* keeps same style, just smaller padding */
    }
    .bisu-hero h2{
        font-size: 1.25rem !important;
        line-height: 1.2;
    }

    /* make hero buttons stack nicely */
    .bisu-hero .d-flex.gap-2{
        flex-direction: column;
        width: 100%;
    }
    .bisu-hero .d-flex.gap-2 .btn,
    .bisu-hero .d-flex.gap-2 .badge{
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    /* badges should not overflow */
    .badge{
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* reduce card header spacing slightly */
    .card-header{
        padding: .85rem 1rem !important;
    }

    /* prevent long details from overflowing */
    .wrap-anywhere{
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    /* empty state: not too tall on phones */
    .empty-state{
        min-height: 160px;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/student/scholarships/show.blade.php ENDPATH**/ ?>