<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Personal Information</h4>
        <p class="text-muted mb-4">Please provide your basic contact information</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Full Name *</label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', session('job_application.personal_info.name')) }}" 
               required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email Address *</label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               id="email" 
               name="email" 
               value="{{ old('email', session('job_application.personal_info.email')) }}" 
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Phone Number *</label>
        <input type="tel" 
               class="form-control @error('phone') is-invalid @enderror" 
               id="phone" 
               name="phone" 
               value="{{ old('phone', session('job_application.personal_info.phone')) }}" 
               required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password (Optional)</label>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               id="password" 
               name="password" 
               placeholder="Leave blank if not needed">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Create a password to track your application status later</div>
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