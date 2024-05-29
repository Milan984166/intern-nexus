<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
use Validator;
use App;
use App\Pages;
use App\EmployerInfo;
use App\Slider;
use App\JobCategory;
use App\Skill;
use App\NewsEvent;
use App\Location;
use App\Setting;
use App\User;
use App\Job;
use App\JobApply;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    public function post_jobs(Request $request)
    {
        $yesterday = \Carbon\Carbon::yesterday();

        $two_weeks_from_now = $yesterday->addWeeks(2);

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $userRole = User::where('id',$request->user_id)->firstOrFail()->role;

    	if (!in_array($userRole, [3, 4]) ) {
    		return response()->json(['status' => 404, 'message' => 'Not A Employer']);
    	}
    	
    	// return response()->json($request->skill_ids);
    	// dd();
		
        $slug = Job::createSlug($request['job_title']);
        
        $jobCreateArray = array('user_id' => $request->user_id,
                                 "job_title" => $request->job_title, 
                                  "slug" => $slug, 
                                  "job_category_id" => $request->job_category_id,
                                  "skill_ids" => json_encode($request->skill_ids), 
                                  "no_of_vacancy" => $request->no_of_vacancy, 
                                  "job_level" => $request->job_level, 
                                  "employment_type" => $request->employment_type, 
                                  "deadline" => $request->deadline, 
                                  "location_id" => $request->location_id, 
                                  "salary_type" => $request->salary_type, 
                                  "min_salary" => @$request->min_salary, 
                                  "max_salary" => @$request->max_salary, 
                                  "image" => @$imageName, 
                                  "education_level" => $request->education_level,
                                  "experience_type" => $request->experience_type, 
                                  "experience_year" => $request->experience_year, 
                                  "job_description" => $request->job_description, 
                                  "benefits" => $request->benefits, 
                                  "display" => isset($request->display) ? $request->display : 0, 
                                  "featured" => isset($request->featured) ? $request->featured : 0
                              );
        $jobCreated = Job::create($jobCreateArray);

    	if ($jobCreated) {
    		return response()->json(['status' => 200, "message" => "Job Posted Successfully" ]);
    	}else{
    		return response()->json(['status' => 500, "message" => "Something went wrong" ]);
    	}
        
    	// return \App\Job::create($request->all());
    }

    public function edit_posted_jobs($id)
    {
        $job = Job::with(['user','job_category', 'location'])->where('id',$id)->first();

        if (!$job) {
            return response()->json(["status" => 404 ,"message" => 'Job Not Found!']);
        }

        $data = array("job" => $job);
        return response()->json(["status" => 200 ,'message' => 'Job Details retrieved successfully!', "data" => $data]);
    }

    public function update_posted_jobs(Request $request)
    {
        $yesterday = \Carbon\Carbon::yesterday();

        $two_weeks_from_now = $yesterday->addWeeks(2);

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'user_id' => 'required',
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

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $user = User::where('id', $request->user_id)->first();
        
        if (!isset($user)) {
            return response()->json(['status' => 404, 'message' => 'Employer Not Found']);
        }

        $userRole = $user->role;

        if (!in_array($userRole, [3,4]) ) {
            return response()->json(['status' => 404, 'message' => 'Not A Employer']);
        }

        $job = Job::where([['id',$request->id],['user_id',$request->user_id]])->first();

        if (!isset($job)) {
            return response()->json(["status" => 404 ,"message" => 'Job Not Found!']);
        }

        
        // return response()->json($request->skill_ids);
        // dd();
        
        $slug = Job::createSlug($request['job_title']);
        
        $jobCreateArray = array("job_title" => $request->job_title, 
                                  "slug" => $slug, 
                                  "job_category_id" => $request->job_category_id,
                                  "skill_ids" => json_encode($request->skill_ids), 
                                  "no_of_vacancy" => $request->no_of_vacancy, 
                                  "job_level" => $request->job_level, 
                                  "employment_type" => $request->employment_type, 
                                  "deadline" => $request->deadline, 
                                  "location_id" => $request->location_id, 
                                  "salary_type" => $request->salary_type, 
                                  "min_salary" => @$request->min_salary, 
                                  "max_salary" => @$request->max_salary, 
                                  "image" => @$imageName, 
                                  "education_level" => $request->education_level,
                                  "experience_type" => $request->experience_type, 
                                  "experience_year" => $request->experience_year, 
                                  "job_description" => $request->job_description, 
                                  "benefits" => $request->benefits, 
                                  "display" => isset($request->display) ? $request->display : 0, 
                                  "featured" => isset($request->featured) ? $request->featured : 0
                              );
        // return response()->json($jobCreateArray);
        $jobUpdated = Job::where([['id',$request->id],['user_id',$request->user_id]])->update($jobCreateArray);
        // dd($jobUpdated);

        if ($jobUpdated) {
            return response()->json(['status' => 200, "message" => "Job Updated Successfully" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
        
        // return \App\Job::create($request->all());
    }

    public function update_employer_company_detail(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();
        
        if (!isset($user)) {
            return response()->json(['status' => 404, 'message' => 'Employer Not Found']);
        }

        $userRole = $user->role;

        if (!in_array($userRole, [3,4]) ) {
            return response()->json(['status' => 404, 'message' => 'Not A Employer']);
        }


        $validator = Validator::make($request->all(), [
            'organization_name' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:255',
            'category_id' => 'required|max:255',
            'about' => 'required',
            'cp_name' => 'required|max:255',
            'cp_designation' => 'required|max:255',
            'cp_contact' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        
        $employer = User::where('id', $request->user_id)->firstOrFail();
        $employer_info = EmployerInfo::where([['id',$request->id],['user_id',$request->user_id]])->first();

        $slug = EmployerInfo::createSlug($request['organization_name'], $request->user_id);

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

        if ($request->image) {

            $image = $request->image;  // your base64 encoded
            $image = str_replace('data:image/jpg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time().'_api.jpg';

            \File::put(storage_path(). '/app/public/employers/company-info/' . $imageName, base64_decode($image));

            Image::make($image)->crop(800, 800)->save(storage_path(). '/app/public/employers/company-info/thumbs/large_' . $imageName);
            Image::make($image)->crop(200, 200)->save(storage_path(). '/app/public/employers/company-info/thumbs/small_' . $imageName);

            $folderPath = "public/employers/company-info";
            $thumbPath = "public/employers/company-info/thumbs";

            $oldemployer = $employer_info->image;
            Storage::delete($folderPath .'/'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/large_'. $oldemployer);
            Storage::delete($folderPath .'/thumbs/small_'. $oldemployer);

            $employerInfoArray['image'] = $imageName;
        }

        // return response()->json($employerInfoArray);


        $employer_info = EmployerInfo::where([['id',$request->id],['user_id',$request->user_id]])->update($employerInfoArray);

        if ($employer_info) {
            return response()->json(['status' => 200, "message" => "Employer Info Updated Successfully" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
    }

    

}
