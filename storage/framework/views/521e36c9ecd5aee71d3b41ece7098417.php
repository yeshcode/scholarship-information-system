  

<?php $__env->startSection('content'); ?>



<!-- Page-Specific Content (Yielded from individual views) -->
<?php echo $__env->yieldContent('page-content'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-open-confirm').forEach(btn => {
        btn.addEventListener('click', function () {
            const showId = this.getAttribute('data-show-id');
            const confirmId = this.getAttribute('data-confirm-id');

            const showEl = document.getElementById(showId);
            const confirmEl = document.getElementById(confirmId);

            if (!showEl || !confirmEl) return;

            const showModal = bootstrap.Modal.getOrCreateInstance(showEl);
            const confirmModal = bootstrap.Modal.getOrCreateInstance(confirmEl);

            // When the SHOW modal is fully hidden, open confirm modal
            const onHidden = () => {
                confirmModal.show();
                showEl.removeEventListener('hidden.bs.modal', onHidden);
            };

            showEl.addEventListener('hidden.bs.modal', onHidden);
            showModal.hide();
        });
    });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/layouts/coordinator.blade.php ENDPATH**/ ?>