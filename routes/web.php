<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationController;

Route::get('/', function () {
    return redirect()->route('job-application.step', 1);
});

// Job Application Routes
Route::get('/job-application/step/{step?}', [JobApplicationController::class, 'showStep'])
    ->name('job-application.step')
    ->where('step', '[1-4]');

Route::post('/job-application/step/{step}', [JobApplicationController::class, 'processStep'])
    ->name('job-application.process')
    ->where('step', '[1-4]');

Route::get('/job-application/success', [JobApplicationController::class, 'success'])
    ->name('job-application.success');
