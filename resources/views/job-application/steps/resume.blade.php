<div class="row">
    <div class="col-12">
        <h4 class="mb-3">Resume Upload</h4>
        <p class="text-muted mb-4">Please upload your resume or CV</p>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <label for="resume" class="form-label">Resume/CV File *</label>
        <div class="border rounded p-4 text-center" style="border-style: dashed !important;">
            <div class="mb-3">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
            </div>
            <input type="file" 
                   class="form-control @error('resume') is-invalid @enderror" 
                   id="resume" 
                   name="resume" 
                   accept=".pdf,.doc,.docx" 
                   required>
            @error('resume')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-text mt-2">
            <strong>File Requirements:</strong><br>
            • Accepted formats: PDF, DOC, DOCX<br>
            • Maximum file size: 2MB<br>
            • Please ensure your resume is up-to-date and includes your contact information
        </div>
    </div>
</div>

@if(session('job_application.resume.original_name'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <strong>Previously uploaded:</strong> {{ session('job_application.resume.original_name') }}
            <br><small>You can upload a new file to replace the previous one.</small>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Resume Tips</h6>
                <ul class="mb-0 text-muted">
                    <li>Include your full contact information</li>
                    <li>Highlight relevant work experience and skills</li>
                    <li>Keep it concise and well-formatted</li>
                    <li>Use a professional file name (e.g., "John_Doe_Resume.pdf")</li>
                    <li>Ensure the file is not password protected</li>
                </ul>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('resume');
    const uploadArea = fileInput.closest('.border');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            uploadArea.innerHTML = `
                <div class="mb-3">
                    <i class="fas fa-file-alt fa-3x text-success"></i>
                </div>
                <h6 class="text-success">File Selected</h6>
                <p class="mb-0"><strong>${fileName}</strong></p>
                <p class="text-muted mb-0">${fileSize} MB</p>
                <input type="file" class="form-control mt-3" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                <small class="text-muted">Click above to change file</small>
            `;
        }
    });
});
</script>