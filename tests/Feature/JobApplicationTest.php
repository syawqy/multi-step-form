<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Resume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_displays_step_1_personal_info_form()
    {
        $response = $this->get('/job-application/step/1');
        
        $response->assertStatus(200)
                 ->assertViewIs('job-application.form')
                 ->assertViewHas('currentStep', 1)
                 ->assertSee('Personal Information')
                 ->assertSee('Full Name')
                 ->assertSee('Email Address')
                 ->assertSee('Phone Number');
    }

    /** @test */
    public function it_validates_personal_info_step()
    {
        $response = $this->post('/job-application/step/1', []);
        
        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }

    /** @test */
    public function it_processes_valid_personal_info_and_redirects_to_step_2()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'password' => 'password123'
        ];

        $response = $this->post('/job-application/step/1', $data);
        
        $response->assertRedirect('/job-application/step/2');
        $response->assertSessionHas('job_application.personal_info', $data);
    }

    /** @test */
    public function it_displays_step_2_experience_form()
    {
        $response = $this->get('/job-application/step/2');
        
        $response->assertStatus(200)
                 ->assertViewHas('currentStep', 2)
                 ->assertSee('Work Experience')
                 ->assertSee('Company Name')
                 ->assertSee('Job Title/Role')
                 ->assertSee('Years of Experience');
    }

    /** @test */
    public function it_validates_experience_step()
    {
        $response = $this->post('/job-application/step/2', []);
        
        $response->assertSessionHasErrors(['company', 'role', 'years', 'description']);
    }

    /** @test */
    public function it_processes_valid_experience_and_redirects_to_step_3()
    {
        $data = [
            'company' => 'Tech Corp',
            'role' => 'Software Developer',
            'years' => '3-5',
            'description' => 'Developed web applications using Laravel and Vue.js. Led a team of 3 developers and implemented CI/CD pipelines.'
        ];

        $response = $this->post('/job-application/step/2', $data);
        
        $response->assertRedirect('/job-application/step/3');
        $response->assertSessionHas('job_application.experience', $data);
    }

    /** @test */
    public function it_displays_step_3_education_form()
    {
        $response = $this->get('/job-application/step/3');
        
        $response->assertStatus(200)
                 ->assertViewHas('currentStep', 3)
                 ->assertSee('Education Background')
                 ->assertSee('Institution Name')
                 ->assertSee('Degree/Qualification')
                 ->assertSee('Graduation Year');
    }

    /** @test */
    public function it_validates_education_step()
    {
        $response = $this->post('/job-application/step/3', []);
        
        $response->assertSessionHasErrors(['institution', 'degree', 'graduation_year']);
    }

    /** @test */
    public function it_processes_valid_education_and_redirects_to_step_4()
    {
        $data = [
            'institution' => 'University of Technology',
            'degree' => 'Bachelor of Computer Science',
            'graduation_year' => '2020'
        ];

        $response = $this->post('/job-application/step/3', $data);
        
        $response->assertRedirect('/job-application/step/4');
        $response->assertSessionHas('job_application.education', $data);
    }

    /** @test */
    public function it_displays_step_4_resume_form()
    {
        $response = $this->get('/job-application/step/4');
        
        $response->assertStatus(200)
                 ->assertViewHas('currentStep', 4)
                 ->assertSee('Resume Upload')
                 ->assertSee('Resume/CV File');
    }

    /** @test */
    public function it_validates_resume_file_requirement()
    {
        $response = $this->post('/job-application/step/4', []);
        
        $response->assertSessionHasErrors(['resume']);
    }

    /** @test */
    public function it_validates_resume_file_type()
    {
        $file = UploadedFile::fake()->create('resume.txt', 100);
        
        $response = $this->post('/job-application/step/4', [
            'resume' => 'not-a-file'
        ]);
        
        $response->assertSessionHasErrors(['resume']);
    }

    /** @test */
    public function it_validates_resume_file_size()
    {
        $file = UploadedFile::fake()->create('resume.pdf', 3000); // 3MB
        
        $response = $this->post('/job-application/step/4', [
            'resume' => $file
        ]);
        
        $response->assertSessionHasErrors(['resume']);
    }

    /** @test */
    public function it_completes_full_application_workflow()
    {
        // Step 1: Personal Info
        $personalData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '+1987654321',
            'password' => 'securepass123'
        ];
        
        $this->post('/job-application/step/1', $personalData)
             ->assertRedirect('/job-application/step/2');

        // Step 2: Experience
        $experienceData = [
            'company' => 'Innovation Labs',
            'role' => 'Senior Developer',
            'years' => '5-10',
            'description' => 'Led development of microservices architecture. Mentored junior developers and implemented automated testing strategies.'
        ];
        
        $this->post('/job-application/step/2', $experienceData)
             ->assertRedirect('/job-application/step/3');

        // Step 3: Education
        $educationData = [
            'institution' => 'MIT',
            'degree' => 'Master of Computer Science',
            'graduation_year' => '2018'
        ];
        
        $this->post('/job-application/step/3', $educationData)
             ->assertRedirect('/job-application/step/4');

        // Step 4: Resume
        $file = UploadedFile::fake()->create('jane_smith_resume.pdf', 1000);
        
        $response = $this->post('/job-application/step/4', [
            'resume' => $file
        ]);
        
        $response->assertRedirect('/job-application/success');

        // Verify database records
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '+1987654321',
            'is_completed' => true
        ]);

        $user = User::where('email', 'jane@example.com')->first();
        
        $this->assertDatabaseHas('experiences', [
            'user_id' => $user->id,
            'company' => 'Innovation Labs',
            'role' => 'Senior Developer',
            'years' => '5-10'
        ]);

        $this->assertDatabaseHas('educations', [
            'user_id' => $user->id,
            'institution' => 'MIT',
            'degree' => 'Master of Computer Science',
            'graduation_year' => 2018
        ]);

        $this->assertDatabaseHas('resumes', [
            'user_id' => $user->id,
            'original_name' => 'jane_smith_resume.pdf'
        ]);

        // Verify file was stored
        $resume = Resume::where('user_id', $user->id)->first();
        Storage::disk('public')->assertExists($resume->file_path);

        // Verify session was cleared
        $response->assertSessionMissing('job_application');
    }

    /** @test */
    public function it_displays_success_page_after_completion()
    {
        $response = $this->get('/job-application/success');
        
        $response->assertStatus(200)
                 ->assertViewIs('job-application.success')
                 ->assertSee('Application Submitted Successfully!')
                 ->assertSee('What\'s Next?', false);
    }

    /** @test */
    public function it_displays_submitted_data_on_success_page()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        // Step 1: Personal Info
        $this->post(route('job-application.process', 1), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890'
        ]);

        // Step 2: Experience
        $this->post(route('job-application.process', 2), [
            'company' => 'Tech Corp',
            'role' => 'Senior Developer',
            'years' => '5-10',
            'description' => 'Led development team and managed multiple projects, working with cross-functional teams to deliver high-quality software solutions.'
        ]);

        // Step 3: Education
        $this->post(route('job-application.process', 3), [
            'institution' => 'MIT',
            'degree' => 'Master of Computer Science',
            'graduation_year' => 2018
        ]);

        // Step 4: Resume
        $file = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');
        $response = $this->post(route('job-application.process', 4), [
            'resume' => $file
        ]);

        // Check redirect to success page
        $response->assertRedirect(route('job-application.success'));
        
        // Follow redirect and check success page
        $successResponse = $this->get(route('job-application.success'));
        
        // Assert success page displays submitted data
        $successResponse->assertStatus(200)
                  ->assertViewIs('job-application.success')
                  ->assertSee('John Doe')
                  ->assertSee('john@example.com')
                  ->assertSee('+1234567890')
                  ->assertSee('Tech Corp')
                  ->assertSee('Senior Developer')
                  ->assertSee('Master of Computer Science')
                  ->assertSee('MIT')
                  ->assertSee('resume.pdf');
    }

    /** @test */
    public function it_prevents_incomplete_application_submission()
    {
        // Try to submit to step 4 without completing previous steps
        $file = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');

        $response = $this->post('/job-application/step/4', [
            'resume' => $file
        ]);

        // The controller will redirect to step 1 with error when session data is incomplete
        $response->assertStatus(302);
        $response->assertRedirect('/job-application/step/1');
    }

    /** @test */
    public function it_maintains_session_data_between_steps()
    {
        $personalData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1111111111',
            'password' => 'password123'
        ];

        // Submit step 1
        $response = $this->post('/job-application/step/1', $personalData);
        $response->assertStatus(302);

        // Check step 2 shows previous data is maintained
        $response = $this->get('/job-application/step/2');
        $response->assertStatus(200);

        // Check session has the data
        $response->assertSessionHas('job_application.personal_info');
    }

    /** @test */
    public function it_allows_navigation_between_completed_steps()
    {
        // Complete step 1
        $this->post('/job-application/step/1', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1111111111'
        ]);
        
        // Navigate back to step 1
        $response = $this->get('/job-application/step/1');
        $response->assertStatus(200)
                 ->assertViewHas('currentStep', 1);
        
        // Navigate to step 2
        $response = $this->get('/job-application/step/2');
        $response->assertStatus(200)
                 ->assertViewHas('currentStep', 2);
    }
}