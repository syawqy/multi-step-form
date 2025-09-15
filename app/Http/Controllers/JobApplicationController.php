<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PersonalInfoRequest;
use App\Http\Requests\ExperienceRequest;
use App\Http\Requests\EducationRequest;
use App\Http\Requests\ResumeRequest;

class JobApplicationController extends Controller
{
    public function showStep($step = 1)
    {
        $step = (int) $step;
        if ($step < 1 || $step > 4) {
            return redirect()->route('job-application.step', 1);
        }

        $currentStep = $step;

        return view('job-application.form', compact('currentStep'));
    }

    public function processStep(Request $request, $step)
    {
        $step = (int) $step;
        
        switch ($step) {
            case 1:
                return $this->processPersonalInfo($request);
            case 2:
                return $this->processExperience($request);
            case 3:
                return $this->processEducation($request);
            case 4:
                return $this->processResume($request);
            default:
                return redirect()->route('job-application.step', 1);
        }
    }

    private function processPersonalInfo(Request $request)
    {
        $personalInfoRequest = PersonalInfoRequest::createFrom($request);
        $personalInfoRequest->setContainer(app())->setRedirector(app('redirect'));
        $personalInfoRequest->validateResolved();
        
        $data = session('job_application', []);
        $data['personal_info'] = $personalInfoRequest->validated();
        session(['job_application' => $data]);

        return redirect()->route('job-application.step', 2);
    }

    private function processExperience(Request $request)
    {
        $experienceRequest = ExperienceRequest::createFrom($request);
        $experienceRequest->setContainer(app())->setRedirector(app('redirect'));
        $experienceRequest->validateResolved();
        
        $data = session('job_application', []);
        $data['experience'] = $experienceRequest->validated();
        session(['job_application' => $data]);

        return redirect()->route('job-application.step', 3);
    }

    private function processEducation(Request $request)
    {
        $educationRequest = EducationRequest::createFrom($request);
        $educationRequest->setContainer(app())->setRedirector(app('redirect'));
        $educationRequest->validateResolved();
        
        $data = session('job_application', []);
        $data['education'] = $educationRequest->validated();
        session(['job_application' => $data]);

        return redirect()->route('job-application.step', 4);
    }

    private function processResume(Request $request)
    {
        $resumeRequest = ResumeRequest::createFrom($request);
        $resumeRequest->setContainer(app())->setRedirector(app('redirect'));
        $resumeRequest->validateResolved();
        
        $file = $resumeRequest->file('resume');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('resumes', $fileName, 'public');

        $data = session('job_application', []);
        $data['resume'] = [
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
        session(['job_application' => $data]);

        return $this->submitApplication();
    }

    private function submitApplication()
    {
        $data = session('job_application', []);

        if (!isset($data['personal_info'], $data['experience'], $data['education'], $data['resume'])) {
            return redirect()->route('job-application.step', 1)->with('error', 'Please complete all steps.');
        }

        // Create user
        $user = User::create([
            'name' => $data['personal_info']['name'],
            'email' => $data['personal_info']['email'],
            'phone' => $data['personal_info']['phone'],
            'is_completed' => true,
        ]);

        // Create experience
        Experience::create([
            'user_id' => $user->id,
            'company' => $data['experience']['company'],
            'role' => $data['experience']['role'],
            'years' => $data['experience']['years'],
            'description' => $data['experience']['description'],
        ]);

        // Create education
        Education::create([
            'user_id' => $user->id,
            'institution' => $data['education']['institution'],
            'degree' => $data['education']['degree'],
            'graduation_year' => $data['education']['graduation_year'],
        ]);

        // Create resume
        Resume::create([
            'user_id' => $user->id,
            'file_path' => $data['resume']['file_path'],
            'original_name' => $data['resume']['original_name'],
            'file_size' => $data['resume']['file_size'],
            'mime_type' => $data['resume']['mime_type'],
        ]);

        // Store data for success page before clearing session
        $successData = [
            'personal_info' => $data['personal_info'],
            'experience' => $data['experience'],
            'education' => $data['education'],
            'resume' => $data['resume']
        ];
        
        // Clear session
        session()->forget('job_application');

        return redirect()->route('job-application.success')
            ->with('success', 'Application submitted successfully!')
            ->with('application_data', $successData);
    }

    public function success()
    {
        return view('job-application.success');
    }
}
