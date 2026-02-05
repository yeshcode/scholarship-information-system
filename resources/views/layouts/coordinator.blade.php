@extends('layouts.app')  {{-- Extends your default layouts.app --}}

@section('content')



<!-- Page-Specific Content (Yielded from individual views) -->
@yield('page-content')

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
@endsection