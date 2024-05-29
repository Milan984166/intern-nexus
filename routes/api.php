<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {

    Route::get('/jobs/{filter_type}','ApiController@jobs')->name('jobs.api');

    Route::get('/job-details/{id}','ApiController@job_details')->name('job_details.api');

    Route::get('/jobseekers','ApiController@jobseekers')->name('jobseekers.api');
    Route::get('/jobseeker/{id}','ApiController@jobseeker_details')->name('jobseeker_details.api');
    Route::get('/jobseeker/{id}/d/{detail_type}','ApiController@jobseeker_single_detail')->name('jobseeker_single_detail.api');

    Route::get('/employers','ApiController@employers')->name('employers.api');
    Route::get('/employer/{id}','ApiController@employer_details')->name('employer_details.api');
    Route::get('/employer/{id}/company-info','ApiController@employer_company_detail')->name('employer_company_detail.api');

    Route::get('/job-categories','ApiController@job_categories')->name('job_categories.api');
    Route::get('/job-category-details/{id}','ApiController@job_category_details')->name('job_category_details.api');

    Route::get('/skills','ApiController@skills')->name('skills.api');
    Route::get('/skill-details/{id}','ApiController@skill_details')->name('skill_details.api');

    Route::get('/locations','ApiController@locations')->name('locations.api');
    Route::get('/location-details/{id}','ApiController@location_details')->name('location_details.api');
    Route::get('/location-jobs/{id}','ApiController@location_jobs')->name('location_jobs.api');

    Route::get('/settings','ApiController@settings')->name('settings.api');


    // ===============================================================================

    Route::put('/employer/company-info/update','EmployerController@update_employer_company_detail')->name('update_employer_company_detail');



    Route::post('/post-jobs','EmployerController@post_jobs')->name('post_jobs');
    Route::get('/edit-posted-jobs/{id}','EmployerController@edit_posted_jobs')->name('edit_posted_jobs');
    Route::put('/update-posted-jobs','EmployerController@update_posted_jobs')->name('update_posted_jobs');

    // =================================================================================
    // JOB SEEKERS
    /* Basic Information Routes */
    Route::prefix('jobseeker')->group(function () {


        Route::get('/edit-basic-info/{id}','JobSeekerController@edit_basic_info')->name('edit-basic-info');
        Route::put('/update-basic-info','JobSeekerController@update_basic_info')->name('update-basic-info');

        /* Job Preferences Routes */
        Route::get('/edit-job-preference/{id}','JobSeekerController@edit_job_preference')->name('edit-job-preference');

        Route::post('/add-job-preference','JobSeekerController@add_job_preference')->name('add-job-preference');

        Route::put('/update-job-preference','JobSeekerController@update_job_preference')->name('update-job-preference');

        Route::get('/job-preference/add-job-preferences/{cIndex}','JobSeekerController@add_job_preferences')->name('add-job_preferences');

        Route::get('job-preferences/delete/{category_skill_id}', 'JobSeekerController@delete_category_skill');


        /* Education Routes */
        Route::get('/edit-education/{id}','JobSeekerController@edit_education')->name('edit-education');
        Route::post('/add-education','JobSeekerController@add_education')->name('add-education');
        Route::put('/update-education','JobSeekerController@update_education')->name('update-education');
        Route::get('educations/delete/{education_id}', 'JobSeekerController@delete_education');


        /* Training Routes */
        Route::get('/edit-training/{id}','JobSeekerController@edit_training')->name('edit-training');
        Route::post('/add-training','JobSeekerController@add_training')->name('add-training');
        Route::put('/update-training','JobSeekerController@update_training')->name('update-training');
        Route::get('trainings/delete/{training_id}', 'JobSeekerController@delete_training');

        /* Work Experience Routes */
        Route::get('/edit-work-experience/{id}','JobSeekerController@edit_work_experience')->name('edit-work-experience');
        Route::post('/add-work-experience','JobSeekerController@add_work_experience')->name('add-work-experience');
        Route::put('/update-work-experience','JobSeekerController@update_work_experience')->name('update-work-experience');
        Route::get('work-experiences/delete/{work_experience_id}', 'JobSeekerController@delete_work_experience');

        /* Job Apply Route */
        Route::post('/apply-job','JobSeekerController@apply_job')->name('apply_job.api');
        Route::post('/add-to-watchlist','JobSeekerController@add_to_watchlist')->name('watchlist.api');
    });

});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1','middleware' => ['cors', 'json.response']], function () {

    // Route::get('/user','ApiAuthController@user')->name('user.api');
    // public routes
    Route::post('/login', 'ApiAuthController@login')->name('login.api');
    Route::post('/register','ApiAuthController@register')->name('register.api');
    Route::post('/logout', 'ApiAuthController@logout')->name('logout.api');
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1','middleware' => ['cors', 'json.response','auth:api']], function(){

    Route::get('/user','ApiAuthController@user')->name('user.api');
    // Route::post('/post-jobs','EmployerController@post_jobs')->name('post_jobs');

});

// Route::middleware(['auth:api'])->get('/user', function (Request $request) {
//     return $request->user();

// });
