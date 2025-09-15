@extends('layouts.app')

@section('title', 'Application Submitted Successfully')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-check-circle fa-5x text-success"></i>
    </div>
    
    <h2 class="text-success mb-3">Application Submitted Successfully!</h2>
    <p class="lead mb-4">Thank you for your interest in joining our team.</p>
    
    <div class="card bg-light mb-4">
        <div class="card-body">
            <h5 class="card-title">Application Summary</h5>
            @if(session('application_data'))
            <div class="row text-start">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ session('application_data.personal_info.name') }}</p>
                    <p><strong>Email:</strong> {{ session('application_data.personal_info.email') }}</p>
                    <p><strong>Phone:</strong> {{ session('application_data.personal_info.phone') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Company:</strong> {{ session('application_data.experience.company') }}</p>
                    <p><strong>Role:</strong> {{ session('application_data.experience.role') }}</p>
                    <p><strong>Education:</strong> {{ session('application_data.education.degree') }} from {{ session('application_data.education.institution') }}</p>
                </div>
            </div>
            @if(session('application_data.resume.original_name'))
            <p class="mb-0"><strong>Resume:</strong> {{ session('application_data.resume.original_name') }}</p>
            @endif
            @else
            <p class="text-muted">Application data not available.</p>
            @endif
        </div>
    </div>
    
    <div class="alert alert-info">
        <h6 class="alert-heading">What's Next?</h6>
        <ul class="mb-0 text-start">
            <li>Our HR team will review your application within 3-5 business days</li>
            <li>You will receive an email confirmation shortly</li>
            <li>If your profile matches our requirements, we'll contact you for the next steps</li>
            <li>Please check your email regularly for updates</li>
        </ul>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('job-application.step', 1) }}" class="btn btn-primary me-2">
            <i class="fas fa-plus"></i> Submit Another Application
        </a>
        <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Summary
        </a>
    </div>
    
    <div class="mt-4 text-muted">
        <small>
            Application submitted on {{ now()->format('F j, Y \a\t g:i A') }}
        </small>
    </div>
</div>

<style>
@media print {
    .btn, .no-print {
        display: none !important;
    }
    .form-container {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }
}
</style>
@endsection