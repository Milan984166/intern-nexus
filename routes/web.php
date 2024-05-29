<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/user/login','LoginRegisterController@login')->name('front_login');
Route::get('/register/job-seeker','HomeController@register_jobseeker')->name('register-jobseeker');
Route::get('/register/employer','HomeController@register_employer')->name('register-employer');
Route::post('/store/job-seeker','HomeController@store_jobseeker')->name('store-jobseeker');

Route::get('/companies','JobController@companies')->name('companies');
Route::get('/categories','JobController@categories')->name('categories');

Route::get('/jobs','JobController@jobs')->name('jobs');
Route::get('/company/jobs/{slug}','JobController@company_jobs')->name('company_jobs');
Route::get('/category/{slug}','JobController@category_jobs')->name('category_jobs');
Route::get('/location/{slug}','JobController@location_jobs')->name('location_jobs');
Route::get('/skill-assessment/{slug}','HomeController@skill_assessment')->name('skill-assessment');


Route::get('/job/{slug}','HomeController@job_details')->name('job-details');
Route::get('/jobseeker-profile/{id}','HomeController@jobseeker_details')->name('jobseeker-details');

Route::get('/job-search','HomeController@job_search')->name('job_search');

Route::post('/change-password','HomeController@change_password')->name('change-password');

Route::get('/about-us',function(){
    return view('about');
});

Route::get('/contact-us',function(){
    return view('contact-us');
});

Route::post('/contact-mail','HomeController@contact_mail')->name('contact-mail');



Route::name('job-seeker.')->prefix('jobseeker')->group(function () {
    Route::get('/profile','CustomerController@jobseeker_profile')->name('profile');

    Route::post('/update-basic-info','CustomerController@update_basic_info')->name('update-basic-info');

    Route::post('/update-education','CustomerController@update_education')->name('update-education');
    Route::get('/education/add-educations/{cIndex}','CustomerController@add_educations')->name('add_educations');
    Route::get('educations/delete/{education_id}', 'CustomerController@delete_education');

    Route::post('/update-experience','CustomerController@update_experience')->name('update-experience');
    Route::get('/experience/add-experiences/{cIndex}','CustomerController@add_experiences')->name('add_experiences');
    Route::get('experiences/delete/{experience_id}', 'CustomerController@delete_experience');

    Route::post('/update-training','CustomerController@update_training')->name('update-training');
    Route::get('/training/add-trainings/{cIndex}','CustomerController@add_trainings')->name('add_trainings');
    Route::get('trainings/delete/{training_id}', 'CustomerController@delete_training');

    /* Job Preferences Routes */
    // Route::get('/job-preference','JobSeekerController@job_preference')->name('job-preference');
    // Route::get('/edit-job-preference','JobSeekerController@edit_job_preference')->name('edit-job-preference');
    Route::post('/update-job-preference','CustomerController@update_job_preference')->name('update-job-preference');

    Route::get('/job-preference/add-job-preferences/{cIndex}','CustomerController@add_job_preferences')->name('add-job_preferences');

    Route::get('job-preferences/delete/{category_skill_id}', 'CustomerController@delete_category_skill');

    Route::post('/job-preference/show-job-categories-skills','CustomerController@show_job_categories_skills')->name('show-job-categories-skills');
    // ====================================================================================

    Route::get('apply/job/{slug}','CustomerController@apply_job')->name('apply_job');
    Route::post('/confirm-apply-job','CustomerController@confirm_apply_job')->name('confirm_apply_job');

    Route::get('/add-to-watchlist/{slug}','CustomerController@add_to_watchlist')->name('watchlist');

    Route::get('watchlist-jobs/delete/{id}', 'CustomerController@remove_from_watchlist');

    // ========================================================================================
    Route::get('/subscribe-premium', 'CustomerController@subscribe_premium')->name('subscribe-premium');
    Route::get('paypal-checkout', 'PaymentController@payWithpaypal')->name('paywithpaypal');
    Route::get('payment/status', 'PaymentController@getPaymentStatus')->name('payment.status');

    Route::get('/create-resume', 'CustomerController@create_resume')->name('create-resume');
    Route::get('/resume', 'CustomerController@resume_result')->name('resume-result');
    // Route::get('applications','CustomerController@applications')->name('applications');
});

$employers = ['company','soletrader'];
foreach ($employers as $employer) {
    Route::name($employer.'.')->prefix($employer)->group(function () {
        Route::get('/profile', 'EmployerController@employer_profile')->name('profile');
        Route::get('/update-information', 'EmployerController@edit_information')->name('edit-information');
        Route::post('/update-information', 'EmployerController@update_information')->name('update-information');

        Route::get('/post-job', 'EmployerController@post_job')->name('post_job');
        Route::post('/add-jobs','EmployerController@add_job')->name('add-job');

        Route::get('/posted-jobs','EmployerController@posted_jobs')->name('posted-jobs');
        Route::get('/posted-jobs/edit/{slug}','EmployerController@edit_posted_jobs')->name('edit-posted-jobs');
        Route::get('/posted-jobs/view/{slug}','EmployerController@view_posted_jobs')->name('view-posted-jobs');
        Route::post('/update-job','EmployerController@update_posted_jobs')->name('update-job');
        Route::post('/post-job/show-job-categories-skills','EmployerController@show_job_categories_skills')->name('show-job-categories-skills');

        Route::get('/applications/{job_slug}', 'EmployerController@applications')->name('applications');
        Route::get('/jobs/applications/change-status/{job_id}/status/{status}','EmployerController@change_application_status')->name('change-application-status');
        Route::get('/subscribe-premium', 'EmployerController@subscribe_premium')->name('subscribe-premium');
        Route::get('paypal-checkout', 'PaymentController@payWithpaypal')->name('paywithpaypal');

        Route::get('payment/status', 'PaymentController@getPaymentStatus')->name('payment.status');
    });
}

Route::prefix('user')->group(function () {
    Route::get('/my-account','CustomerController@my_account')->name('my_account');
    Route::get('/update-information','CustomerController@update_information')->name('update_information');
    Route::get('/get_states/{cName}', 'CustomerController@get_states')->name('get-states');
    Route::post('updateuser', 'CustomerController@updateuser')->name('customer.updateuser');
    Route::post('/updatepassword', 'CustomerController@update_password')
                ->name('customer.update_password');
});

Route::get('artisan/{command}', function($command){
    $com = explode('-', $command);
    if (isset($com[0]) && isset($com[1])) {
        \Artisan::call($com[0].':'.$com[1]);
        return redirect()->back()->with('success_status', "Artisan Command executed Successfully!");
    }else{
        return redirect()->back()->with('error', "Invalid Artisan Command, Please check it Properly!");
    }
});

Auth::routes();
Auth::routes(['register' => false]);

Route::get('/admin/login', function () {
    return view('admin/login');
})->middleware('guest')->name('admin.login');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin'],'namespace' => 'Admin'], function(){

    Route::get('/', 'DashboardController@index')->name('dashboard');

	//site setting
    Route::get('/setting', 'SettingController@index')->name('admin.setting');
   	Route::post('/setting/update', 'SettingController@update')->name('admin.setting.update');

   	//users
   	Route::prefix('users')->group(function () {
   		Route::get('/', 'UsersController@index')->name('admin.users');
	    Route::post('/create', 'UsersController@create')->name('admin.users.create');
	    Route::get('/edit/{id}', 'UsersController@edit')->name('admin.users.edit');
	    Route::post('/update', 'UsersController@update')->name('admin.users.update');
	    Route::get('/delete/{id}', 'UsersController@delete')->name('admin.users.delete');

        Route::post('/change-password','UsersController@change_password')->name('admin.users.change-password');
   	});

    //job seekers
    Route::prefix('job-seekers')->group(function () {
        Route::get('/', 'JobSeekerController@index')->name('admin.job-seekers');
        Route::post('/create', 'JobSeekerController@create')->name('admin.job-seekers.create');
        Route::get('/edit/{id}', 'JobSeekerController@edit')->name('admin.job-seekers.edit');
        Route::post('/update', 'JobSeekerController@update')->name('admin.job-seekers.update');
        Route::get('/delete/{id}', 'JobSeekerController@delete')->name('admin.job-seekers.delete');

        Route::name('admin.job-seekers.')->prefix('profile/{id}')->group(function(){
            // Main Profile
            Route::get('/','JobSeekerController@profile')->name('profile');

            /* Basic Information Routes */
            Route::get('/basic-info','JobSeekerController@basic_info')->name('basic-info');
            Route::get('/edit-basic-info','JobSeekerController@edit_basic_info')->name('edit-basic-info');
            Route::post('/update-basic-info','JobSeekerController@update_basic_info')->name('update-basic-info');

            /* Job Preferences Routes */
            Route::get('/job-preference','JobSeekerController@job_preference')->name('job-preference');
            Route::get('/edit-job-preference','JobSeekerController@edit_job_preference')->name('edit-job-preference');
            Route::post('/update-job-preference','JobSeekerController@update_job_preference')->name('update-job-preference');

            Route::get('/job-preference/add-job-preferences/{cIndex}','JobSeekerController@add_job_preferences')->name('add-job_preferences');

            Route::get('job-preferences/delete/{category_skill_id}', 'JobSeekerController@delete_category_skill');

            Route::post('/job-preference/show-job-categories-skills','JobSeekerController@show_job_categories_skills')->name('show-job-categories-skills');

            /* Education Routes */
            Route::get('/education','JobSeekerController@education')->name('education');
            Route::get('/edit-education','JobSeekerController@edit_education')->name('edit-education');
            Route::post('/update-education','JobSeekerController@update_education')->name('update-education');
            Route::get('/education/add-educations/{cIndex}','JobSeekerController@add_educations')->name('add_educations');
            Route::get('educations/delete/{education_id}', 'JobSeekerController@delete_education');


            /* Training Routes */
            Route::get('/training','JobSeekerController@training')->name('training');
            Route::get('/edit-training','JobSeekerController@edit_training')->name('edit-training');
            Route::post('/update-training','JobSeekerController@update_training')->name('update-training');
            Route::get('/training/add-trainings/{cIndex}','JobSeekerController@add_trainings')->name('add_trainings');
            Route::get('trainings/delete/{training_id}', 'JobSeekerController@delete_training');

            /* Work Experience Routes */
            Route::get('/work-experience','JobSeekerController@work_experience')->name('work-experience');
            Route::get('/edit-work-experience','JobSeekerController@edit_work_experience')->name('edit-work-experience');
            Route::post('/update-work-experience','JobSeekerController@update_work_experience')->name('update-work-experience');
            Route::get('/work-experience/add-work-experiences/{cIndex}','JobSeekerController@add_work_experiences')->name('add-work_experiences');

            Route::get('work-experiences/delete/{work_experience_id}', 'JobSeekerController@delete_work_experience');
        });
    });

    Route::prefix('employers')->group(function () {

        Route::get('/', 'EmployerController@index')->name('admin.employers');
        Route::post('/create', 'EmployerController@create')->name('admin.employers.create');
        Route::get('/edit/{id}', 'EmployerController@edit')->name('admin.employers.edit');
        Route::post('/update', 'EmployerController@update')->name('admin.employers.update');
        Route::get('/delete/{id}', 'EmployerController@delete')->name('admin.employers.delete');

        Route::name('admin.employers.')->prefix('profile/{id}')->group(function(){
            // Main Profile
            Route::get('/','EmployerController@profile')->name('profile');

            /* Basic Information Routes */
            Route::get('/basic-info','EmployerController@basic_info')->name('basic-info');
            Route::get('/edit-basic-info','EmployerController@edit_basic_info')->name('edit-basic-info');
            Route::post('/update-basic-info','EmployerController@update_basic_info')->name('update-basic-info');

            // Posted Jobs
            Route::get('/posted-jobs','EmployerController@posted_jobs')->name('posted-jobs');
            Route::get('/post-jobs','EmployerController@post_jobs')->name('post-jobs');
            Route::post('/add-jobs','EmployerController@add_job')->name('add-job');

            Route::get('/posted-jobs/edit/{job_id}','EmployerController@edit_posted_jobs')->name('edit-posted-jobs');
            Route::get('/posted-jobs/view/{job_id}','EmployerController@view_posted_jobs')->name('view-posted-jobs');
            Route::post('/update-jobs','EmployerController@update_posted_jobs')->name('update-job');
            Route::post('/post-jobs/show-job-categories-skills','EmployerController@show_job_categories_skills')->name('show-job-categories-skills');
        });
    });

    // Sliders
    Route::prefix('sliders')->group(function () {
        Route::get('/', 'SliderController@index')->name('admin.sliders');
        Route::post('/create', 'SliderController@createslide')->name('admin.sliders.create');
        Route::get('/edit/{id}', 'SliderController@editslide');
        Route::post('/update', 'SliderController@updateslide')->name('admin.sliders.update');
        Route::get('/delete/{id}', 'SliderController@delete')->name('admin.sliders.delete');
        Route::post('/set_order', 'SliderController@set_order')->name('order_sliders');
        Route::post('/imageupload', 'SliderController@imageupload')->name('imageupload');
    });

   	// Pages
   	Route::get('/&{slug}', 'PagesController@single');
    Route::prefix('pages')->group(function () {
        Route::get('/', 'PagesController@index')->name('admin.pages');
        Route::post('/create', 'PagesController@create')->name('admin.pages.create');
        Route::get('/edit/{id}', 'PagesController@edit');
        Route::post('/update', 'PagesController@update')->name('admin.pages.update');
        Route::get('/delete/{id}', 'PagesController@delete')->name('admin.pages.delete');
        Route::post('/set_order', 'PagesController@set_order')->name('order_pages');
        Route::post('/imageupload', 'PagesController@imageupload')->name('imageupload');
    });

    // Skill Assessments
    Route::get('/&{slug}', 'SkillAssessmentController@single');
    Route::prefix('skill-assessments')->group(function () {
        Route::get('/', 'SkillAssessmentController@index')->name('admin.skill-assessments');
        Route::post('/create', 'SkillAssessmentController@create')->name('admin.skill-assessments.create');
        Route::get('/edit/{id}', 'SkillAssessmentController@edit');
        Route::post('/update', 'SkillAssessmentController@update')->name('admin.skill-assessments.update');
        Route::get('/delete/{id}', 'SkillAssessmentController@delete')->name('admin.pages.delete');
        Route::post('/set_order', 'SkillAssessmentController@set_order')->name('order_skill_assessments');
        Route::post('/imageupload', 'SkillAssessmentController@imageupload')->name('imageupload');
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', 'CategoryController@index')->name('admin.categories');
        Route::post('/create', 'CategoryController@create')->name('admin.categories.create');
        Route::get('/edit/{id}', 'CategoryController@edit');
        Route::get('/sub-categories/{parent_id}', 'CategoryController@sub_categories');
        Route::post('/update', 'CategoryController@update')->name('admin.categories.update');
        Route::get('/delete/{id}', 'CategoryController@delete')->name('admin.categories.delete');
        Route::post('/set_order', 'CategoryController@set_order')->name('order_categories');
        Route::post('/imageupload', 'CategoryController@imageupload')->name('imageupload');
    });

    // Locations
    Route::prefix('locations')->group(function () {
        Route::get('/', 'LocationController@index')->name('admin.locations');
        Route::post('/create', 'LocationController@create')->name('admin.locations.create');

        Route::get('/edit/{id}', 'LocationController@edit');
        Route::post('/update', 'LocationController@update')->name('admin.locations.update');

        Route::get('/delete/{id}', 'LocationController@delete')->name('admin.locations.delete');
        Route::post('/set_order', 'LocationController@set_order')->name('order_locations');
    });

    // Job Category
    Route::prefix('job-categories')->group(function () {
        Route::get('/', 'JobCategoryController@index')->name('admin.job_categories');
        Route::post('/create', 'JobCategoryController@create')->name('admin.job_categories.create');

        Route::get('/edit/{id}', 'JobCategoryController@edit');
        Route::post('/update', 'JobCategoryController@update')->name('admin.job_categories.update');

        Route::get('/delete/{id}', 'JobCategoryController@delete')->name('admin.job_categories.delete');

        Route::get('/addSkills/{cIndex}','JobCategoryController@add_skills');
        Route::get('skills/delete/{id}', 'JobCategoryController@delete_skill');

        Route::post('/set_order', 'JobCategoryController@set_order')->name('order_job_categories');
    });

    // Job Category
    Route::prefix('job-industries')->group(function () {
        Route::get('/', 'JobIndustryController@index')->name('admin.job_industries');
        Route::post('/create', 'JobIndustryController@create')->name('admin.job_industries.create');

        Route::get('/edit/{id}', 'JobIndustryController@edit');
        Route::post('/update', 'JobIndustryController@update')->name('admin.job_industries.update');

        Route::get('/delete/{id}', 'JobIndustryController@delete')->name('admin.job_industries.delete');

        Route::post('/set_order', 'JobIndustryController@set_order')->name('order_job_industries');
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', 'ProductController@index')->name('admin.products');
        Route::post('/create', 'ProductController@createproduct')->name('admin.products.create');
        Route::get('/edit/{id}', 'ProductController@editproduct');
        Route::post('/update', 'ProductController@updateproduct')->name('admin.products.update');
        Route::get('/delete/{id}', 'ProductController@delete')->name('admin.products.delete');
        Route::post('/set_order', 'ProductController@set_order')->name('order_products');
        Route::get('/addBarrel/{cIndex}','ProductController@add_barrel');
        Route::get('delete-image/{albumName}/{photoName}','ProductController@delete_product_gallery_image');
    });


    //News & Events
    Route::name('news-and-events.')->prefix('news-and-events')->group(function () {
        Route::get('/', 'NewsEventController@index')->name('list');
        Route::get('/create', 'NewsEventController@show')->name('show');
        Route::post('/create', 'NewsEventController@store')->name('add');
        Route::get('/edit/{id}', 'NewsEventController@edit')->name('edit');
        Route::post('/edit/{id}', 'NewsEventController@update')->name('update');
        Route::get('/delete/{id}', 'NewsEventController@delete')->name('delete');

    });

	 //for editor image upload in my custom storage
    Route::post('/image/upload', 'PagesController@imageupload')->name('admin.image.upload');    

});