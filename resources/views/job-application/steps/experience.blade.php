<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Work Experience</h4>
        <p class="text-muted mb-4">Tell us about your professional experience</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="company" class="form-label">Company Name *</label>
        <input type="text" 
               class="form-control @error('company') is-invalid @enderror" 
               id="company" 
               name="company" 
               value="{{ old('company', session('job_application.experience.company')) }}" 
               required>
        @error('company')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="role" class="form-label">Job Title/Role *</label>
        <input type="text" 
               class="form-control @error('role') is-invalid @enderror" 
               id="role" 
               name="role" 
               value="{{ old('role', session('job_application.experience.role')) }}" 
               required>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="years" class="form-label">Years of Experience *</label>
        <select class="form-select @error('years') is-invalid @enderror" 
                id="years" 
                name="years" 
                required>
            <option value="">Select experience level</option>
            <option value="0-1" {{ old('years', session('job_application.experience.years')) == '0-1' ? 'selected' : '' }}>0-1 years</option>
            <option value="1-3" {{ old('years', session('job_application.experience.years')) == '1-3' ? 'selected' : '' }}>1-3 years</option>
            <option value="3-5" {{ old('years', session('job_application.experience.years')) == '3-5' ? 'selected' : '' }}>3-5 years</option>
            <option value="5-10" {{ old('years', session('job_application.experience.years')) == '5-10' ? 'selected' : '' }}>5-10 years</option>
            <option value="10+" {{ old('years', session('job_application.experience.years')) == '10+' ? 'selected' : '' }}>10+ years</option>
        </select>
        @error('years')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Job Description *</label>
        <textarea class="form-control @error('description') is-invalid @enderror" 
                  id="description" 
                  name="description" 
                  rows="4" 
                  placeholder="Describe your key responsibilities and achievements..." 
                  required>{{ old('description', session('job_application.experience.description')) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Minimum 50 characters required</div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif