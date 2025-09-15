<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Education Background</h4>
        <p class="text-muted mb-4">Please provide your educational qualifications</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="institution" class="form-label">Institution Name *</label>
        <input type="text" 
               class="form-control @error('institution') is-invalid @enderror" 
               id="institution" 
               name="institution" 
               value="{{ old('institution', session('job_application.education.institution')) }}" 
               placeholder="University/College/School name" 
               required>
        @error('institution')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="degree" class="form-label">Degree/Qualification *</label>
        <input type="text" 
               class="form-control @error('degree') is-invalid @enderror" 
               id="degree" 
               name="degree" 
               value="{{ old('degree', session('job_application.education.degree')) }}" 
               placeholder="e.g., Bachelor of Science, MBA, High School Diploma" 
               required>
        @error('degree')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="graduation_year" class="form-label">Graduation Year *</label>
        <select class="form-select @error('graduation_year') is-invalid @enderror" 
                id="graduation_year" 
                name="graduation_year" 
                required>
            <option value="">Select graduation year</option>
            @php
                $currentYear = date('Y');
                $startYear = $currentYear - 50;
            @endphp
            @for($year = $currentYear + 5; $year >= $startYear; $year--)
                <option value="{{ $year }}" 
                        {{ old('graduation_year', session('job_application.education.graduation_year')) == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endfor
        </select>
        @error('graduation_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Additional Information</h6>
                <p class="card-text text-muted mb-0">
                    <small>
                        • If you have multiple degrees, please enter your highest qualification<br>
                        • For ongoing studies, select your expected graduation year<br>
                        • Professional certifications can be mentioned in the degree field
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif