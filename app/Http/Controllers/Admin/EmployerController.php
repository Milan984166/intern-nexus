<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\JobCategory;
use App\Skill;
use App\EmployerInfo;
use App\Job;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = User::where('role','>',2)->get();
        return view('admin.employers.employers',array('employers' => $employers, 'id' => 0));
    }

    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:225|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required'],
        ]);

        $request['status'] = isset($request['status']) ? $request['status'] : 0;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/employers";
            $thumbPath = "public/employers/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

        }

        $employer = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => $request['role'],
            'status' => $request['status'],
            'phone' => $request['phone'],
            'current_address' => $request['current_address'],
            'permanent_address' => $request['permanent_address'],
            'gender' => $request['gender'],
            'dob' => $request['dob'],
            'religion' => $request['religion'],
            'maritial_status' => $request['maritial_status'],
            'nationality' => $request['nationality'],
            'image' => @$filename
        ]);

        return redirect()->to('admin/employers')->with('status', 'Employer added Successfully!');

    }

    public function edit($id)
    {
        $id = base64_decode($id);
       	$employer = User::findOrFail($id);
       	return view('admin.employers.employers',array('employer' => $employer, 'id' => $id));
    }

    public function update(Request $request)
    {
        $employer = User::findOrFail($request->id);
        $request['status'] = isset($request['status']) ? $request['status'] : '0';
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => [
                    'required',
                    Rule::unique('users')->ignore($user->id)
                    ],
        ]);
        $employer->name = $request['name'];
        $employer->role = $request['role'];
        $employer->status = $request['status'];
        $employer->phone = $request['phone'];
        $employer->dob = $request['dob'];
        $employer->current_address = $request['current_address'];
        $employer->permanent_address = $request['permanent_address'];
        $employer->religion = $request['religion'];
        $employer->nationality = $request['nationality'];
        $employer->gender = $request['gender'];
        $employer->maritial_status = $request['maritial_status'];

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

            $oldemployer = $employer->image;

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/employers";
            $thumbPath = "public/employers/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

            Storage::delete($folderPath .'/'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/large_'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/small_'. $oldemployer);

            $employer->image = $filename;

        }

        $employer->save();
        return redirect('admin/employers')->with('status', 'Employer Updated Successfully!');

    }

    public function delete($id)
    {

        $employer = User::where('id', $id)->firstOrFail();

        if ($employer) {
            $employerHasJobs = $employer->jobs()->exists();
            // dd($employerHasJobs);
            if ($employerHasJobs) {
                return redirect()->back()->with('error', 'Employer Has Jobs!');
            }
            // $employer->delete();
            return redirect()->back()->with('status', 'Employer Deleted Successfully!');
            
        }
        return redirect()->back()->with('status', 'Something Went Wrong!');
    }

    public function profile($id)
    {
    	$employer = User::where('id',base64_decode($id))->firstOrFail();
    	$info = $employer->employer_info;
        return view('admin.employers.employers-profile',array('employer' => $employer, 'info' => $info, 'id' => $id));
    }

    /*-----------------------------------BASIC INFORMATION PART-------------------------------------------*/

    public function basic_info($id)
    {
    	$employer = User::where('id',base64_decode($id))->firstOrFail();
    	$info = $employer->employer_info;

        return view('admin.employers.employers-basic-info',array('employer' => $employer, 'info' => $info, 'id' => $id));
    }

    public function edit_basic_info($id)
    {
    	$employer = User::where('id',base64_decode($id))->firstOrFail();
    	$info = $employer->employer_info;
        return view('admin.employers.employers-edit-basic-info',array('employer' => $employer, 'info' => $info, 'id' => $id));
    }

    public function update_basic_info($id, Request $request)
    {

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

    	$employer = User::findOrFail(base64_decode($id));

        $slug = EmployerInfo::createSlug($request['organization_name'], base64_decode($id));

        $employerInfoArray = ["organization_name" => $request->organization_name, 
                              "slug" => $slug, 
                              "email" => $employer->email,
                              "address" => $request->address, 
                              "phone" => $request->phone, 
                              "category_id" => $request->category_id, 
                              "ownership_type" => $request->ownership_type, 
                              "organization_size" => $request->organization_size, 
                              "website" => $request->website, 
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

            $oldemployer = $employer->image;

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

            Storage::delete($folderPath .'/'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/large_'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/small_'. $oldemployer);

            $employerInfoArray['image'] = $filename;


        }

    	$employer_info = EmployerInfo::updateOrCreate(
                                        ['user_id' => base64_decode($id)],
                                        $employerInfoArray
                            );

        return redirect()->route('admin.employers.basic-info', ['id' => $id])->with("status", "Employers Basic Information Updated Successfully!");
    }

    /*-----------------------------------POSTED JOBS PART-------------------------------------------*/

    public function posted_jobs($id)
    {
        $employer = User::where('id',base64_decode($id))->firstOrFail();
        $jobs = $employer->jobs;
        // dd($jobs[0]->job_title);
        return view('admin.jobs.jobs',array('employer' => $employer, 'jobs' => $jobs, 'id' => $id));
    }

    public function post_jobs($id)
    {
        $employer = User::where('id',base64_decode($id))->firstOrFail();
        return view('admin.jobs.post-jobs',array('employer' => $employer, 'id' => $id, 'job_id' => 0));
    }

    public function add_job($id, Request $request)
    {
        // dd($_POST);
        $yesterday = \Carbon\Carbon::yesterday();

        $two_weeks_from_now = $yesterday->addWeeks(2);

        $validatedData = $request->validate([
            'job_title' => 'required|max:255',
            'job_level' => 'required|numeric|min:1|not_in:0',
            'job_category_id' => 'required|numeric|min:1',
            'no_of_vacancy' => 'required|numeric|min:1',
            'employment_type' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:'.$two_weeks_from_now,
            'location_id' => 'required|numeric|min:1',
            'min_salary' => 'sometimes|required',
            'max_salary' => 'sometimes|gte:min_salary',
        ]);

        // dd($_POST);

        $employer = User::findOrFail(base64_decode($id));

        $slug = Job::createSlug($request['job_title']);

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            ]);

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

        }

        $jobCreateArray = array('user_id' => base64_decode($id),
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

        return redirect()->route('admin.employers.posted-jobs', ['id' => $id])->with("status", "New Job Created Successfully!");

        dd($request);
    }


    public function show_job_categories_skills($id, Request $request){
        $job_category_id = $request->job_category_id;
        $job_id = base64_decode($request->job_id);
        $dbSkills = array();

        if($job_category_id){

            $jobCategorySkills = Skill::where('job_category_id', $job_category_id)->get();

            $responseText ='<select class="form-control multiselect multiselect-custom skills_multiselect"  name="skills[]" multiple="multiple">';

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



    public function edit_posted_jobs($id,$job_id)
    {
        // dd(base64_decode($job_id));
        $employer = User::where('id',base64_decode($id))->firstOrFail();
        // return view('admin.jobs.post-jobs',array('employer' => $employer, 'id' => $id, 'job_id' => 0));

        $job = Job::where('id',base64_decode($job_id))->firstOrFail();
        if ($job->user_id != $employer->id) {
            return redirect()->back()->with('error','Sorry, no Match Found!!');
        }
        
        return view('admin.jobs.post-jobs',array('employer' => $employer, 'job' => $job, 'id' => $id, 'job_id' => $job->id));
    }

    public function update_posted_jobs($id, Request $request)
    {
        $yesterday = \Carbon\Carbon::yesterday();
        $two_weeks_from_now = $yesterday->addWeeks(2);
        $job_id = base64_decode($request->job_id);
        
        $validatedData = $request->validate([
            'job_title' => 'required|max:255',
            'job_level' => 'required|numeric|min:1|not_in:0',
            'job_category_id' => 'required|numeric|min:1',
            'no_of_vacancy' => 'required|numeric|min:1',
            'employment_type' => 'required|numeric|min:1',
            // 'deadline' => 'required|date|after:'.$two_weeks_from_now,
            'location_id' => 'required|numeric|min:1',
            'min_salary' => 'sometimes|required',
            'max_salary' => 'sometimes|gte:min_salary',
        ]);

        $employer = User::findOrFail(base64_decode($id));
        $job = Job::where([['id',$job_id],['user_id',base64_decode($id)]])->firstOrFail();

        if (date('Y-m-d',strtotime($request->deadline)) != date('Y-m-d',strtotime($job->deadline))) {
            $request->validate([
                'deadline' => 'required|date|after:'.$two_weeks_from_now
            ]);
        }
        
        $slug = Job::createSlug($request['job_title'],$job_id);

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

        $jobUpdated = Job::where('id',$job_id)->update($jobUpdateArray);

        return redirect()->route('admin.employers.posted-jobs', ['id' => $id])->with("status", "Job Updated Successfully!");

        
    }

    public function view_posted_jobs($id, $job_id)
    {
        // dd(base64_decode($job_id));
        $employer = User::where('id',base64_decode($id))->firstOrFail();
        // return view('admin.jobs.post-jobs',array('employer' => $employer, 'id' => $id, 'job_id' => 0));

        $job = Job::where('id',base64_decode($job_id))->firstOrFail();
        if ($job->user_id != $employer->id) {
            return redirect()->back()->with('error','Sorry, no Match Found!!');
        }
        
        return view('admin.jobs.post-jobs',array('employer' => $employer, 'job' => $job, 'id' => $id, 'job_id' => $job->id));
    }
}
