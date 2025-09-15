@extends('layouts.app')

@section('title', 'Job Application - Step ' . $currentStep)

@section('content')
<div class="text-center mb-4">
    <h2 class="mb-3">Job Application Form</h2>
    <p class="text-muted">Complete all steps to submit your application</p>
</div>

<!-- Progress Indicator -->
<div class="step-indicator mb-4">
    <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
        <div class="step-number">1</div>
        <div class="mt-2">Personal Info</div>
    </div>
    <div class="step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
        <div class="step-number">2</div>
        <div class="mt-2">Experience</div>
    </div>
    <div class="step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
        <div class="step-number">3</div>
        <div class="mt-2">Education</div>
    </div>
    <div class="step {{ $currentStep >= 4 ? 'active' : '' }}">
        <div class="step-number">4</div>
        <div class="mt-2">Resume</div>
    </div>
</div>

<!-- Progress Bar -->
<div class="progress mb-4" style="height: 8px;">
    <div class="progress-bar progress-bar-custom" role="progressbar" 
         style="width: {{ ($currentStep / 4) * 100 }}%" 
         aria-valuenow="{{ $currentStep }}" 
         aria-valuemin="0" 
         aria-valuemax="4">
    </div>
</div>

<!-- Form Content -->
<form method="POST" action="{{ route('job-application.process', $currentStep) }}" enctype="multipart/form-data">
    @csrf
    
    @if($currentStep == 1)
        @include('job-application.steps.personal-info')
    @elseif($currentStep == 2)
        @include('job-application.steps.experience')
    @elseif($currentStep == 3)
        @include('job-application.steps.education')
    @elseif($currentStep == 4)
        @include('job-application.steps.resume')
    @endif
    
    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-between mt-4">
        @if($currentStep > 1)
            <a href="{{ route('job-application.step', $currentStep - 1) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
        @else
            <div></div>
        @endif
        
        @if($currentStep < 4)
            <button type="submit" class="btn btn-primary">
                Next <i class="fas fa-arrow-right"></i>
            </button>
        @else
            <button type="submit" class="btn btn-success">
                Submit Application <i class="fas fa-check"></i>
            </button>
        @endif
    </div>
</form>
@endsection