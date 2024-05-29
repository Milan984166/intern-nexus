<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
use Validator;
use App;
use App\Pages;
use App\EmployerInfo;
use App\JobCategory;
use App\Skill;
use App\User;
use App\Job;
use App\JobApply;
use App\JobWatchlist;
use App\Education;
use App\Experience;
use App\Training;
use App\JobPreference;
use App\CategorySkill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;

class JobSeekerController extends Controller
{
    public function edit_basic_info($id)
    {
    	$jobseeker = User::where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	return response()->json(["status" => 200 ,'message' => "Jobseeker Details", "data" => array("jobseeker" => $jobseeker)]);
    }

    public function update_basic_info(Request $request)
    {
    	// dd($_POST);

    	$validator = Validator::make($request->all(), [
    		'id' => 'required',
            'name' => 'required|max:255',
            'phone' => [
                    'required',
                    Rule::unique('users')->ignore($request->id)
                    ],
            'dob' => 'required',
            'current_address' => 'required',
            'permanent_address' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'maritial_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id',$request->id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

        $jobseekerArray = array('name' => $request->name,
						        'phone' => $request->phone,
						        'dob' => $request->dob,
						        'current_address' => $request->current_address,
						        'permanent_address' => $request->permanent_address,
						        'religion' => $request->religion,
						        'nationality' => $request->nationality,
						        'gender' => $request->gender,
						        'maritial_status' => $request->maritial_status);

        if ($request->image) {

            $image = $request->image;  // your base64 encoded
            $image = str_replace('data:image/jpg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time().'_api.jpg';

            $folderPath = "public/jobseekers";
            $thumbPath = "public/jobseekers/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }

            \File::put(storage_path(). '/app/public/jobseekers/' . $imageName, base64_decode($image));

            Image::make($image)->crop(800, 800)->save(storage_path(). '/app/public/jobseekers/thumbs/large_' . $imageName);
            Image::make($image)->crop(200, 200)->save(storage_path(). '/app/public/jobseekers/thumbs/small_' . $imageName);

            $oldjobseeker = $jobseeker->image;
            Storage::delete($folderPath .'/'. $oldjobseeker);
            Storage::delete($folderPath .'/thumbs/large_'. $oldjobseeker);
            Storage::delete($folderPath .'/thumbs/small_'. $oldjobseeker);

            $jobseekerArray['image'] = $imageName;
        }


        $basic_info = User::where('id', $request->id)->update($jobseekerArray);

        if ($basic_info) {
            return response()->json(['status' => 200, "message" => "Job Seekers Basic Information Updated" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
    }

    /*---------------------------------- Education -------------------------------------------*/
    public function edit_education($id)
    {
    	$jobseeker = User::with('education')->where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	return response()->json(["status" => 200, 'message' => "Jobseeker Education Details", "data" => array("user_id" =>$id ,"education" => $jobseeker->education)]);
    }

    public function add_education(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$educationArray = $request->education;
    	$educationUpdateArray = array();
    	foreach ($educationArray as $education) {

			array_push($educationUpdateArray, array("degree" => $education['degree'], 
		    									  "program" => $education['program'], 
		    									  "board" => $education['board'], 
		    									  "institute" => $education['institute'], 
		    									  "student_here" => $education['student_here'] == 1 ? $education['student_here'] : 0,
		    									  "year" => $education['year'],
		    									  "month" => $education['month'],
		    									  "marks_unit" => $education['student_here'] == 1 ? '' : $education['marks_unit'],
		    									  "marks" => $education['student_here'] == 1 ? '' : $education['marks'],
		    									  "created_by" => $jobseeker->name
		    									)
											);
		}
		
		$educationAdded =  $jobseeker->education()->createMany($educationUpdateArray);

        if ($educationAdded) {
            return response()->json(['status' => 200, "message" => "Job Seekers Education Added" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
    }

    public function update_education(Request $request)
    {
    	// return response()->json($request->education[0]['degree']);

    	// dd($_POST);

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$educationArray = $request->education;

    	foreach ($educationArray as $education) {
			$educationUpdateArray = array("degree" => $education['degree'], 
    									  "program" => $education['program'], 
    									  "board" => $education['board'], 
    									  "institute" => $education['institute'], 
    									  "student_here" => $education['student_here'] == 1 ? $education['student_here'] : 0,
    									  "year" => $education['year'],
    									  "month" => $education['month'],
    									  "marks_unit" => $education['student_here'] == 1 ? '' : $education['marks_unit'],
    									  "marks" => $education['student_here'] == 1 ? '' : $education['marks'],
    									  "updated_by" => $jobseeker->name
    									);
			Education::where([['id', $education['id']],['user_id',$request->user_id]])->update($educationUpdateArray);
		}

        
        return response()->json(['status' => 200, "message" => "Job Seekers Education Updated" ]);
    }

    /*---------------------------------- Work Experience -------------------------------------------*/

    public function edit_work_experience($id)
    {
    	$jobseeker = User::with('experience')->where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	return response()->json(["status" => 200, 'message' => "Jobseeker Experience Details", "data" => array("user_id" =>$id ,"experience" => $jobseeker->experience)]);
    }

    public function add_work_experience(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$experienceArray = $request->experience;
    	$experienceUpdateArray = array();
    	foreach ($experienceArray as $experience) {

			array_push($experienceUpdateArray, array("organization_name" => $experience['organization_name'], 
	        									  "job_location" => $experience['job_location'], 
	        									  "job_title" => $experience['job_title'], 
	        									  "job_category_id" => $experience['job_category_id'], 
	        									  "working_here" => isset($experience['working_here']) ? $experience['working_here'] : 0,
	        									  "start_year" => $experience['start_year'],
	        									  "start_month" => $experience['start_month'],
	        									  "end_year" => isset($experience['working_here']) ? '' : $experience['end_year'],
	        									  "end_month" => isset($experience['working_here']) ? '' : $experience['end_month'],
	        									  "duties_responsibilities" => $experience['duties_responsibilities'],
	        									  "created_by" => $jobseeker->name
		    									)
											);
		}
		
		$experienceAdded =  $jobseeker->experience()->createMany($experienceUpdateArray);

        if ($experienceAdded) {
            return response()->json(['status' => 200, "message" => "Job Seekers Experience Added" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
    }

    public function update_work_experience(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$experienceArray = $request->experience;

    	foreach ($experienceArray as $experience) {
			$experienceUpdateArray = array("organization_name" => $experience['organization_name'], 
    									  "job_location" => $experience['job_location'], 
    									  "job_title" => $experience['job_title'], 
    									  "job_category_id" => $experience['job_category_id'], 
    									  "working_here" => isset($experience['working_here']) ? $experience['working_here'] : 0,
    									  "start_year" => $experience['start_year'],
    									  "start_month" => $experience['start_month'],
    									  "end_year" => isset($experience['working_here']) ? '' : $experience['end_year'],
    									  "end_month" => isset($experience['working_here']) ? '' : $experience['end_month'],
    									  "duties_responsibilities" => $experience['duties_responsibilities'],
    									  "updated_by" => $jobseeker->name
    									);
			Experience::where([['id', $experience['id']],['user_id',$request->user_id]])->update($experienceUpdateArray);
		}

        
        return response()->json(['status' => 200, "message" => "Job Seekers Experience Updated" ]);
    }

    /*---------------------------------- Training -------------------------------------------*/

    public function edit_training($id)
    {
    	$jobseeker = User::with('training')->where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	return response()->json(["status" => 200, 'message' => "Jobseeker Training Details", "data" => array("user_id" =>$id ,"training" => $jobseeker->training)]);
    }

    public function add_training(Request $request)
    {

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$trainingArray = $request->training;
    	$trainingUpdateArray = array();
    	foreach ($trainingArray as $training) {

			array_push($trainingUpdateArray, array("name" => $training['name'], 
	        									  "institution" => $training['institution'], 
	        									  "duration_unit" => $training['duration_unit'],
	        									  "duration" => $training['duration'],
	        									  "year" => $training['year'],
	        									  "month" => $training['month'],
	        									  "created_by" => $jobseeker->name
		    									)
											);
		}
		
		$trainingAdded =  $jobseeker->training()->createMany($trainingUpdateArray);

        if ($trainingAdded) {
            return response()->json(['status' => 200, "message" => "Job Seekers Training Added" ]);
        }else{
            return response()->json(['status' => 500, "message" => "Something went wrong" ]);
        }
    }

    public function update_training(Request $request)
    {
    	// return response()->json($request->training[0]['degree']);

    	// dd($_POST);

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}
    	$trainingArray = $request->training;

    	foreach ($trainingArray as $training) {
			$trainingUpdateArray = array("name" => $training['name'], 
										  "institution" => $training['institution'], 
										  "duration_unit" => $training['duration_unit'],
										  "duration" => $training['duration'],
										  "year" => $training['year'],
										  "month" => $training['month'],
										  "updated_by" => $jobseeker->name
    									);
			Training::where([['id', $training['id']],['user_id',$request->user_id]])->update($trainingUpdateArray);
		}

        
        return response()->json(['status' => 200, "message" => "Job Seekers Training Updated" ]);
    }

    /*---------------------------------- Job Preference -------------------------------------------*/

    public function edit_job_preference($id)
    {
    	$jobseeker = User::with('preference')->where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	$preference = JobPreference::with('category_skills')->where('user_id',$jobseeker->id)->first();

    	return response()->json(["status" => 200, 'message' => "Jobseeker Job Preferences Details", "data" => array("user_id" =>$id ,"preference" => $preference)]);
    }

    

    public function update_job_preference(Request $request)
    {
    	// return response()->json($request->education[0]['degree']);

    	// dd($_POST);

    	$validator = Validator::make($request->all(), [
    		'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' =>[ 'errors' => $validator->errors()->all()]]);
        }

        $jobseeker = User::where('id', $request->user_id)->first();

        if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	$preference = $request->preference;
    	// return response()->json($preference['category_skills']);
    	

    	$job_preference = JobPreference::updateOrCreate(
                                        ['user_id' =>$request->user_id],
                                        ["looking_for" => $preference['looking_for'], 
                                          "employment_type" => json_encode($preference['employment_type']), 
                                          "expected_salary" => $preference['expected_salary'], 
                                          "expected_salary_period" => $preference['expected_salary_period'], 
                                          "location_id" => isset($preference['location_id']) ? $preference['location_id'] : 0,
                                          "career_objective" => $preference['career_objective'],
                                          "created_by" => $jobseeker->name
                                        ]
                            );



            
        $jobCategorySkillArray = array();
        $job_preference_skills = $preference['category_skills'];

        foreach ($job_preference_skills as $preference) {
            if (!isset($preference['id'])) {
            	array_push($jobCategorySkillArray, array("job_category_id" => $preference['job_category_id'],
                                                    "skill_ids" => json_encode($preference['skill_ids'])));
            }else{
            	$jobCategorySkillUpdate = array("job_category_id" => $preference['job_category_id'],
                                                    "skill_ids" => json_encode($preference['skill_ids']));
            	CategorySkill::where('id',$preference['id'])->update($jobCategorySkillUpdate);
            }
        }

        // return response()->json($jobCategorySkillArray);
        // dd($jobCategorySkillArray);
        $job_preference->category_skills()->createMany($jobCategorySkillArray);
        // $jobseeker->experience()->createMany($experienceArray);

        
        return response()->json(['status' => 200, "message" => "Job Seekers Job Preference Updated" ]);
    }

    public function apply_job(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'employer_id' => 'required',
            'jobseeker_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $job_id = $request->job_id;
        $employer_id = $request->employer_id;
        $jobseeker_id = $request->jobseeker_id;

        $jobseeker = User::where('id',$jobseeker_id)->first();
        $employer = User::where('id', $employer_id)->first();
        $job = Job::where([['id', $job_id],['user_id',$employer_id]])->first();


        if (!isset($jobseeker)) {
           
            return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
        }elseif (!isset($employer)) {
           
            return response()->json(["status" => 404 ,"message" => 'Employer Not Found!']);
        }elseif (!isset($job)) {
           
            return response()->json(["status" => 404 ,"message" => 'Job Not Found!']);
        }elseif ($jobseeker->role != 2 ) {

            return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
        }

        $alreadyApplied = JobApply::where([['job_id', $job_id],['jobseeker_id',$jobseeker_id]])->exists();

        if ($alreadyApplied) {
            return response()->json(["status" => 422 ,"message" => 'Already Applied Job!']);
        }

        

        $jobApplied = JobApply::create(['job_id' => $job_id,
                                    'employer_id' => $employer_id,
                                    'jobseeker_id' => $jobseeker_id,
                                    'approved' => 0,
                                    'status' => 0,
                                ]);
        
        if ($jobApplied) {
            return response()->json(['status' => 200, "message" => "Job Applied Successfully!" ]);
        }

    }

    public function add_to_watchlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required',
            'jobseeker_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 422, 'message' => 'Validation Error', 'data' => [ 'errors' => $validator->errors()->all()]]);
        }

        $job_id = $request->job_id;
        $jobseeker_id = $request->jobseeker_id;

        $jobseeker = User::where('id',$jobseeker_id)->first();
        $job = Job::where('id', $job_id)->first();


        if (!isset($jobseeker)) {
           
            return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
        }elseif (!isset($job)) {
           
            return response()->json(["status" => 404 ,"message" => 'Job Not Found!']);
        }elseif ($jobseeker->role != 2 ) {

            return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
        }

        $alreadyExists = JobWatchlist::where([['job_id', $job->id],['jobseeker_id', $jobseeker->id]])->exists();

        if ($alreadyExists) {
            return response()->json(["status" => 422 ,"message" => 'Already In Watchlist!']);
        }

        $jobWatchlistAdded = JobWatchlist::create(['job_id' => $job->id,
                                    'jobseeker_id' => $jobseeker->id
                                ]);
        
        if ($jobWatchlistAdded) {
            return response()->json(["status" => 200 ,"message" => 'Job Added to Watchlist.']);
        }
        
    }

}
