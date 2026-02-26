@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h4 class="mb-1" style="color:#0b2e5e;">Reports</h4>
            <div class="text-muted">Generate official scholarship reports per semester (A4 format).</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-2" style="color:#0b2e5e;">List of Scholars and Grantees</h6>
                    <p class="text-muted mb-3">
                        Auto-generates the scholars list for the selected/active semester.
                    </p>

                    <a href="{{ route('coordinator.reports.list-of-scholars') }}"
                       class="btn btn-primary btn-sm">
                        View / Print
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-2" style="color:#0b2e5e;">Summary of Scholarships</h6>
                    <p class="text-muted mb-3">
                        Shows scholarship totals for 1st and 2nd semester within the active academic year.
                    </p>

                    <a href="{{ route('coordinator.reports.summary-of-scholarships') }}"
                       class="btn btn-primary btn-sm">
                        View / Print
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-muted mt-3" style="font-size:.9rem;">
        Tip: Use your browser’s Print → “Save as PDF” for an official downloadable copy.
    </div>

</div>
@endsection