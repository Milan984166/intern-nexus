<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobStatusMail;
use App\User;
use App\EmployerInfo;
use App\JobCategory;
use App\Skill;
use App\Location;
use App\Education;
use App\Job;
use App\JobApply;
use App;

class EmployerController extends Controller
{
	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// }

    public function employer_profile()
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $employer = User::where('id', Auth::user()->id)->firstOrFail(); 
        $employer_info = $employer->employer_info;
        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->limit(8)->get();
        $jobs = $employer->jobs()->limit(5)->get();
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';

        return view('employer.employer-profile', compact('employer','employer_info','jobs','employer_type','job_categories'));
    }

    public function edit_information()
    {
    	if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $employer = User::where('id',Auth::user()->id)->firstOrFail();
        $employer_info = $employer->employer_info;
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        return view('employer.employer-update-info',array('employer' => $employer, 'employer_info' => $employer_info, 'employer_type' => $employer_type));
    }

    public function update_information(Request $request)
    {
    	if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $validatedData = $request->validate([
            'organization_name' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255',
            'category_id' => 'required|max:255',
            'about' => 'required',
            'cp_name' => 'required|max:255',
            'cp_designation' => 'required|max:255',
            'cp_contact' => 'required|max:255',
        ]);

    	$employer = User::where('id', Auth::user()->id)->firstOrFail();
    	$employer_info = $employer->employer_info;

    	$slug = EmployerInfo::createSlug($request['organization_name'], Auth::user()->id);

    	$employerInfoArray = ["organization_name" => $request->organization_name,
                                "slug" => $slug,
                                "email" => $employer->email,
                                "address" => $request->address,
                                "phone" => $request->phone,
                                "category_id" => $request->category_id,
                                "ownership_type" => $request->ownership_type,
                                "organization_size" => $request->organization_size,
                                "website" => $request->website,
                                "facebook" => $request->facebook,
                                "twitter" => $request->twitter,
                                "linkedin" => $request->linkedin,
                                "about" => $request->about,
                                "cp_name" => $request->cp_name,
                                "cp_contact" => $request->cp_contact,
                                "cp_designation" => $request->cp_designation,
                                "cp_email" => $request->cp_email
                            ];

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            
            $folderPath = "public/employers/company-info";
            $thumbPath = "public/employers/company-info/thumbs";

            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

            if ($employer_info) {
            	$oldemployer = $employer_info->image;
            	Storage::delete($folderPath .'/'. $oldemployer);
            	Storage::delete($folderPath .'/thumbs/large_'. $oldemployer);
            	Storage::delete($folderPath .'/thumbs/small_'. $oldemployer);	
            }

            $employerInfoArray['image'] = $filename;
        }

    	$employer_info = EmployerInfo::updateOrCreate(
                                        ['user_id' => Auth::user()->id],
                                        $employerInfoArray
                            );
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        return redirect()->route($employer_type.'.profile')->with("status", "Employers Basic Information Updated Successfully!");

    }

    public function applications($job_slug)
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            if (!in_array(Auth::user()->role, ['3','4'])) {
                return redirect()->back()->with('error', 'You are not Employer!!');
            }
        }

        $employer = User::where('id', Auth::user()->id)->firstOrFail();

        $job = Job::where('slug', $job_slug)->firstOrFail();
        $employer_info = $employer->employer_info;

        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        $applicants = $job->applicants()->orderBy('created_at')->get();
        // dd($applicants);

        return view('jobs.applicants',compact('employer', 'employer_info', 'job', 'employer_type','applicants'));
    }

    public function change_application_status($job_id, $status)
    {
        // dd(base64_decode($job_id));
        if (!Auth::check()) {

            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{

            if (!in_array(Auth::user()->role, ['3','4'])) {

                return redirect()->back()->with('error', 'You are not Employer!!');

            }
        }

        $job_id = base64_decode($job_id);
        $status = base64_decode($status);
        // dd($status);

        $job_application = JobApply::where('id', $job_id)->firstOrFail();
        // dd($job_application->job_details->job_title);

        if ($job_application->employer_id != Auth::user()->id) {

            return redirect()->back()->with('error','UnAuthorized Access!');

        }else{

            $job_application->status = $status;
            $jobApplicationUpdated = $job_application->save();

            if ($jobApplicationUpdated) {
                
                $jobStatusMessage = array(
                    
                    'jobseeker_name' => $job_application->job_seeker->name,
                    'jobseeker_email' => $job_application->job_seeker->email,
                    'employer_name' => Auth::user()->employer_info->organization_name,
                    'employer_email' => Auth::user()->employer_info->email,
                    'job_title' => $job_application->job_details->job_title,
                    'job_url' => route('job-details',['slug' => $job_application->job_details->slug]),
                    'intern_nexus_email' => \App\Setting::where('id', 1)->first()->siteemail,
                    'status' => $status == 1 ? 'Apporved' : 'Declined'
                );


                Mail::to($jobStatusMessage['jobseeker_email'])->send(new JobStatusMail($jobStatusMessage));

                return redirect()->back()->with('status','Job Application Status changed Successfully!');
            }else{

                return redirect()->back()->with('error','Something Went Wrong!');
            }
        }

        return redirect()->back()->with('error','Something Went Wrong!');

    }

    public function posted_jobs()
    {
    	if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $employer = User::where('id', Auth::user()->id)->firstOrFail();
        $employer_info = $employer->employer_info;

        $jobs = $employer->jobs()->orderBy('created_at','desc')->get();
        
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->limit(8)->get();
        return view('jobs.posted-jobs',compact('employer', 'employer_info', 'jobs', 'employer_type','job_categories'));
    }

    public function post_job()
    {
    	if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $employer = User::where('id',Auth::user()->id)->firstOrFail();
        $employer_info = $employer->employer_info;
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        return view('jobs.post-job',array('employer' => $employer, 'employer_info' => $employer_info, 'job_id' => 0, 'employer_type' => $employer_type));
    }

    public function add_job(Request $request)
    {

    	// dd($request);
        // $yesterday = \Carbon\Carbon::yesterday();

        // $two_weeks_from_now = $yesterday->addWeeks(2);

        $validatedData = $request->validate([
            'job_title' => 'required|max:255',
            'job_level' => 'required|numeric|min:1|not_in:0',
            'job_category_id' => 'required|numeric|min:1',
            'no_of_vacancy' => 'required|numeric|min:1',
            'employment_type' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'location_id' => 'required|numeric|min:1',
            'min_salary' => 'sometimes|required',
            'max_salary' => 'sometimes|gte:min_salary',
        ]);

        // dd($_POST);

        $slug = Job::createSlug($request['job_title']);

        $jobCreateArray = array('user_id' => Auth::user()->id,
                                 "job_title" => $request->job_title, 
                                  "slug" => $slug, 
                                  "job_category_id" => $request->job_category_id,
                                  "skill_ids" => isset($request->skills) ? json_encode($request->skills) : '[]', 
                                  "no_of_vacancy" => $request->no_of_vacancy, 
                                  "job_level" => $request->job_level, 
                                  "employment_type" => $request->employment_type, 
                                  "deadline" => $request->deadline, 
                                  "location_id" => $request->location_id, 
                                  "salary_type" => $request->salary_type, 
                                  "min_salary" => @$request->min_salary, 
                                  "max_salary" => @$request->max_salary, 
                                  "image" => '', 
                                  "education_level" => $request->education_level,
                                  "experience_type" => $request->experience_type, 
                                  "experience_year" => $request->experience_year, 
                                  "contact_email" => $request->contact_email, 
                                  "contact_phone" => $request->contact_phone, 
                                  "messenger_link" => $request->messenger_link, 
                                  "viber_number" => $request->viber_number, 
                                  "whatsapp_number" => $request->whatsapp_number, 
                                  "job_description" => $request->job_description, 
                                  "benefits" => $request->benefits, 
                                  "display" => isset($request->display) ? $request->display : 0, 
                                  "featured" => isset($request->featured) ? $request->featured : 0
                              );

        $jobCreated = Job::create($jobCreateArray);
        $employer_type = Auth::user()->role == 4 ? 'company' : 'soletrader';
        return redirect()->route($employer_type.'.posted-jobs')->with("status", "New Job Created Successfully!");

        // dd($request);
    }

    public function edit_posted_jobs($slug)
    {
    	if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (!in_array(Auth::user()->role, ['3','4'])) {
        		return redirect()->back()->with('error', 'You are not Employer!!');
        	}
        }

        $employer = User::where('id',Auth::user()->id)->firstOrFail();
        $employer_info = $employer->employer_info;
        $job = Job::where('slug', base64_decode($slug))->firstOrFail();
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';
        return view('jobs.post-job',array('employer' => $employer, 'employer_info' => $employer_info, 'job' => $job, 'job_id' => $job->id, 'employer_type' => $employer_type));
    }

    public function update_posted_jobs(Request $request)
    {

    	// dd($request);
        // $yesterday = \Carbon\Carbon::yesterday();
        // $two_weeks_from_now = $yesterday->addWeeks(2);
        $job_id = base64_decode($request->job_id);

        $validatedData = $request->validate([
            'job_title' => 'required|max:255',
            'job_level' => 'required|numeric|min:1|not_in:0',
            'job_category_id' => 'required|numeric|min:1',
            'no_of_vacancy' => 'required|numeric|min:1',
            'employment_type' => 'required|numeric|min:1',
            // 'deadline' => 'required|date|after:today',
            'location_id' => 'required|numeric|min:1',
            'min_salary' => 'sometimes|required',
            'max_salary' => 'sometimes|gte:min_salary',
        ]);

        // dd($_POST);
        $job = Job::where([['id', $job_id],['user_id',Auth::user()->id]])->firstOrFail();

        if (date('Y-m-d',strtotime($request->deadline)) != date('Y-m-d',strtotime($job->deadline))) {
            $request->validate([
                'deadline' => 'required|date|after:today'
            ]);
        }
        
        $slug = Job::createSlug($request['job_title'], $job_id);

        $jobUpdateArray = array( "job_title" => $request->job_title, 
                                  "slug" => $slug, 
                                  "job_category_id" => $request->job_category_id,
                                  "skill_ids" => isset($request->skills) ? json_encode($request->skills) : '[]', 
                                  "no_of_vacancy" => $request->no_of_vacancy, 
                                  "job_level" => $request->job_level, 
                                  "employment_type" => $request->employment_type, 
                                  "deadline" => $request->deadline, 
                                  "location_id" => $request->location_id, 
                                  "salary_type" => $request->salary_type, 
                                  "min_salary" => @$request->min_salary, 
                                  "max_salary" => @$request->max_salary, 
                                  "education_level" => $request->education_level,
                                  "experience_type" => $request->experience_type, 
                                  "experience_year" => $request->experience_year, 
                                  "contact_email" => $request->contact_email, 
                                  "contact_phone" => $request->contact_phone, 
                                  "messenger_link" => $request->messenger_link, 
                                  "viber_number" => $request->viber_number, 
                                  "whatsapp_number" => $request->whatsapp_number, 
                                  "job_description" => $request->job_description, 
                                  "benefits" => $request->benefits, 
                                  "display" => isset($request->display) ? $request->display : 0, 
                                  "featured" => isset($request->featured) ? $request->featured : 0
                              );

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $oldjob = $job->image;

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/jobs";
            $thumbPath = "public/jobs/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

            $jobUpdateArray['image'] = $filename;
            Storage::delete($folderPath .'/'. $oldjob);
            Storage::delete($folderPath .'/thumbs/large_'. $oldjob);
            Storage::delete($folderPath .'/thumbs/small_'. $oldjob);

        }

        $jobUpdated = Job::where('id', $job_id)->update($jobUpdateArray);
        $employer_type = Auth::user()->role == 4 ? 'company' : 'soletrader';
        return redirect()->route($employer_type.'.posted-jobs')->with("status", "Job Updated Successfully!");

        // dd($request);
    }

    public function show_job_categories_skills(Request $request){
        $job_category_id = $request->job_category_id;
        $job_id = base64_decode($request->job_id);
        $dbSkills = array();

        if($job_category_id){

            $jobCategorySkills = Skill::where('job_category_id', $job_category_id)->get();

            $responseText ='<select class="ui fluid dropdown multi-select" name="skills[]"  multiple="multiple">
            	<option value="">Select Job Skills</option>';

            if ($job_id != 0) {
                $dbSkills = Job::where('id' , $job_id)->first()->skill_ids;
                $dbSkills = json_decode($dbSkills);

            }

            foreach ($jobCategorySkills as $key => $skill) {
                $selected = in_array($skill->id, $dbSkills) ? "selected" : "";
                $responseText .= '<option '.$selected. ' value="'.$skill->id .'">'.$skill->title.'</option>';
            }

            $responseText .= '</select>';

        }else{
            $responseText = '<select class="form-control" required><option selected disabled>Select Job Category First</option></select>';
        }

        return $responseText;
    }

    public function subscribe_premium()
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            if (!in_array(Auth::user()->role, ['3','4'])) {
                return redirect()->back()->with('error', 'You are not Employer!!');
            }
        }

        $employer = User::where('id', Auth::user()->id)->firstOrFail(); 
        $employer_info = $employer->employer_info;
        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->limit(8)->get();
        $jobs = $employer->jobs()->limit(5)->get();
        $employer_type = $employer->role == 4 ? 'company' : 'soletrader';

        return view('employer.subscribe-premium', compact('employer','employer_info','jobs','employer_type','job_categories'));
    }
}
