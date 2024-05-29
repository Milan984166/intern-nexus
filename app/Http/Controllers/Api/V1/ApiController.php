<?php

namespace app\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App;
use App\Pages;
use App\Slider;
use App\JobCategory;
use App\Skill;
use App\NewsEvent;
use App\Location;
use App\Setting;
use App\User;
use App\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function settings(){
        $setting = Setting::find('1');
        return response()->json(["status" => 200,'message' =>'Site Setting Datas', "data" => array("setting" => $setting)]);
    }

    public function jobs($filter_type)
    {
    	if ($filter_type == 'all') {

    		$jobs = Job::with(['user'])->where('display',1)->get();
    	}elseif ($filter_type == 'featured') {

    		$jobs = Job::with(['user'])->where([['display',1],['featured',1]])->get();
    	}elseif ($filter_type == 'popular') {

    		$jobs = Job::with(['user'])->where('display',1)->orderBy('views','desc')->get();
    	}elseif ($filter_type == 'hot') {

    		$jobs = Job::with(['user'])->where('display',1)->orderBy('applied','desc')->get();
    	}elseif ($filter_type == 'recent') {

    		$jobs = Job::with(['user'])->where('display',1)->orderBy('created_at','desc')->get();
    	}elseif ($filter_type == 'most-applied') {

    		$jobs = Job::with(['user'])->where('display',1)->orderBy('applied','desc')->get();
    	}elseif ($filter_type == 'most-viewed') {

    		$jobs = Job::with(['user'])->where('display',1)->orderBy('views','desc')->get();
    	}else{

    		return response()->json(["status" => 404 ,"message" => 'Jobs Not Found!']);
    	}
    	// $harga = harga::whereHas('produk', function ($query) use($id) {
					// 		    $query->where('id',$id);
					// 		  })->get()->groupBy(column_name_you_want_to_group_by);
    	// dd($jobs[0]->skills)
    	// ===================================================
    	// foreach ($jobs as $key => $job) {
    	// 	$skill_ids = json_decode($job->skill_ids);
    	// 	$skillsNameArray = array();
    	// 	for ($i=0; $i < count($skill_ids); $i++) { 
    	// 		$skillsNameArray[$skill_ids[$i]] = \App\Skill::where('id',$skill_ids[$i])->select('title')->first()->title;
    	// 	}
    	// 	$job->skill_titles = $skillsNameArray;
    	// 	// var_dump();
    	// 	// echo "</pre>";
    	// }
    	// ======================================================
    	// dd($jobs);

    	// $skills = Skill::all();
    	// $job_categories = JobCategory::all();
    	// $locations = Location::all();
    	$job_levels = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
    	$employment_types = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
    	$salary_types = array('1' => 'Range', '2' => 'Fixed', '3' => 'Negotiable');
    	$education_levels = array('1' => 'Others', '2' => 'SLC', '3' => 'Intermediate' , '4' => 'Bachelor', '5' => 'Master', '6' => 'Ph.D.');
    	$experience_types = array('1' => 'More than', '2' => 'Less than', '3' => 'More than or equals to' , '4' => 'Less than or equals to', '5' => 'Equals to');

    	$data = array("jobs" => $jobs,
					  // "skills" => $skills,
					  // "job_categories" => $job_categories,
					  // "locations" => $locations,
					  // "job_levels" => $job_levels,
					  // "employment_types" => $employment_types,
					  // "salary_types" => $salary_types,
					  // "education_levels" => $education_levels,
					  // "experience_types" => $experience_types,
					);
    	return response()->json(["status" => 200,'message' => 'Jobs List', "data" => $data]);
    }

    public function job_details($id)
    {
    	$job = Job::with(['user','job_category', 'location'])->where('id',$id)->first();

    	if (!$job) {
    		return response()->json(["status" => 404 ,"message" => 'Job Not Found!']);
    	}

		$skill_ids = json_decode($job->skill_ids);
		$skillsNameArray = array();
		for ($i=0; $i < count(json_decode($job->skill_ids)); $i++) { 
			$skillsNameArray[$skill_ids[$i]] = \App\Skill::where('id',$skill_ids[$i])->select('title')->first()->title;
		}
		$job->skill_titles = $skillsNameArray;
    		

    	// ======================================================
    	// dd($jobs);

    	$skills = Skill::all();

    	$data = array("job" => $job,
					  "skills" => $skills
					);
    	return response()->json(["status" => 200 ,'message' => 'Job Details retrieved successfully!', "data" => $data]);
    }

    public function jobseekers(){
        $jobseekers = User::with(['education','experience','training' ,'preference.category_skills.job_category'])->where('role',2)->get();
        

        // foreach ($jobseekers as $j => $jobseeker) {
        	
        // 	$category_skills = $jobseeker->preference->category_skills;
        	
        // 	foreach ($category_skills as $c => $catSkill) {
        		
        // 		$skillsNameArray = array();
        // 		$categorySkills = json_decode($catSkill->skill_ids);
        		
        // 		for ($i=0; $i < count($categorySkills); $i++) { 
        // 			$skillsNameArray[$categorySkills[$i]] = \App\Skill::where('id',$categorySkills[$i])->select('title')->first()->title;
        // 		}
        // 		// fetch skill titles
        // 		$catSkill->skill_titles = $skillsNameArray;

        // 	}
        // }
        // dd($jobseekers[0]['preference']->category_skills);
        return response()->json(["status" => 200 ,'message' => 'jobseekers list', "data" => array("jobseekers" => $jobseekers)]);
    }

    public function jobseeker_details($id)
    {
    	$jobseeker = User::with(['education','experience','training' ,'preference.category_skills.job_category'])->where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	return response()->json(["status" => 200 ,'message' => "Jobseeker Details", "data" => array("jobseeker" => $jobseeker)]);
    }


    public function jobseeker_single_detail($id, $detail_type)
    {
    	$jobseeker = User::where('id',$id)->first();

    	if (!isset($jobseeker)) {
    		return response()->json(["status" => 404 ,"message" => 'Job Seeker Not Found!']);
    	}

    	if ($jobseeker->role != 2 ) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Job Seeker!']);
    	}

    	if ($detail_type == 'education') {
    		$education_details = $jobseeker->education;
    		return response()->json(["status" => 200 , 'mesage' => "Jobseeker Education", "data" => array("education_details" => $education_details)]);
    	}elseif ($detail_type == 'training') {
    		$training_details = $jobseeker->training;
    		return response()->json(["status" => 200 , 'mesage' => "Jobseeker Training", "data" => array("training_details" => $training_details)]);
    	}elseif ($detail_type == 'experience') {
    		$experience_details = $jobseeker->experience;
    		return response()->json(["status" => 200 , 'mesage' => "Jobseeker Experience", "data" => array("experience_details" => $experience_details)]);
    	}elseif ($detail_type == 'job-preference') {

    		$job_preferences = $jobseeker->preference;
    		$category_skills = $jobseeker->preference->category_skills;
    	
	    	foreach ($category_skills as $c => $catSkill) {
	    		
	    		$skillsNameArray = array();
	    		$categorySkills = json_decode($catSkill->skill_ids);
	    		
	    		for ($i=0; $i < count($categorySkills); $i++) { 
	    			$skillsNameArray[$categorySkills[$i]] = \App\Skill::where('id',$categorySkills[$i])->select('title')->first()->title;
	    		}
	    		// fetch skill titles
	    		$catSkill->skill_titles = $skillsNameArray;

	    		// job category details
	    		$catSkill->job_category;
	    	}

	    	return response()->json(["status" => 200 ,'mesage' => "Jobseeker Job Preference", "data" => array("job_preferences" => $job_preferences)]);
    	}else{
    		return response()->json(["status" => 404 ,"message" => 'Url Not Found!']);
    	}

    }


    public function employers(){

    		$employers = User::with(['employer_info.employer_category'])->whereIn('role', [3,4])->get();

    		return response()->json(["status" => 200 ,'message' => 'employers list', "data" => array("employers" => $employers)]);

    	// }elseif ($emp_type == 'sole-traders') {
    	// 	$soletraders = User::where('role', 3)->get();
    	// 	foreach ($soletraders as $j => $soletrader) {
     //    		$soletrader->employer_info->employer_category;
     //    	}
    	// 	return response()->json(["status" => 200 ,"data" => array("soletraders" => $soletraders)]);
    	// }else{
    	// 	return response()->json(["status" => 404 ,"message" => 'Not Found!']);
    	// }
    }

    public function employer_details($id){
    	$employer = User::with(['employer_info'])->where('id', $id)->first();

    	if (!isset($employer)) {
    		return response()->json(["status" => 404 ,"message" => 'Employer Not Found!']);
    	}

    	if (!in_array($employer->role, ['3','4'])) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Employer!']);
    	}
    	
		$employer->employer_info->employer_category;
		$jobs = $employer->jobs;

		foreach ($jobs as $key => $job) {

			$job->job_category;
			$job->location;

			$skill_ids = json_decode($job->skill_ids);
			$skillsNameArray = array();
			for ($i=0; $i < count($skill_ids); $i++) { 
				$skillsNameArray[$skill_ids[$i]] = \App\Skill::where('id',$skill_ids[$i])->select('title')->first()->title;
			}
			$job->skill_titles = $skillsNameArray;
			// var_dump();
			// echo "</pre>";
		}

		// $employer->jobs->location;
	
		return response()->json(["status" => 200 ,'message' => 'Employer Details', "data" => array("employer_info" => $employer)]);
    }

    public function employer_company_detail($id){
    	$employer = User::where('id', $id)->first();

    	if (!isset($employer)) {
    		return response()->json(["status" => 404 ,"message" => 'Employer Not Found!']);
    	}

    	if (!in_array($employer->role, ['3','4'])) {
    		return response()->json(["status" => 404 ,"message" => 'Not a Employer!']);
    	}
    	
		$company_info = $employer->employer_info;
	
		return response()->json(["status" => 200 ,'message' => 'Employer Company Information', "data" => array("company_details" => $company_info)]);
    }

    public function job_categories()
    {
    	$job_categories = JobCategory::where('display',1)->get();
    	return response()->json(["status" => 200 ,'message' => 'Job Categories List', "data" => array("job_categories" => $job_categories)]);
    }

    public function job_category_details($id)
    {
    	$job_category = JobCategory::with(['skill'])->where([['id', $id],['display',1]])->first();
    	if ($job_category) {
    		return response()->json(["status" => 200 ,'message' => 'Job Category Details', "data" => array("job_category" => $job_category)]);
    	}else{
    		return response()->json(["status" => 404 ,"message" => 'Job Category Not Found!']);
    	}
    	
    }

    public function skills()
    {
    	$skills = Skill::all();
    	return response()->json(["status" => 200 ,'message' => 'Skills list', "data" => array("skills" => $skills)]);
    }

    public function skill_details($id)
    {
    	$skill = Skill::with(['job_category'])->where('id',$id)->get();
    	if ($skill) {
    		return response()->json(["status" => 200 ,'message' => 'Skill Details', "data" => array("skill" => $skill)]);
    	}else{
    		return response()->json(["status" => 404 ,"message" => 'Skill Not Found!']);
    	}
    }

    public function locations()
    {
    	$locations = Location::where('display',1)->get();
    	return response()->json(["status" => 200 ,'message' => 'Locations list', "data" => array("locations" => $locations)]);
    }

    public function location_details($id)
    {
    	$location = Location::where('id',$id)->first();
    	if ($location) {
    		return response()->json(["status" => 200 ,'message' => 'Location Details', "data" => array("location" => $location)]);
    	}else{
    		return response()->json(["status" => 404 ,"message" => 'Location Not Found!']);
    	}
    }

    public function location_jobs($id)
    {
    	$location = Location::where('id',$id)->first();
    	if ($location) {
    		$jobs = $location->jobs;
    		return response()->json(["status" => 200 ,'message' => 'Jobs list By Location', "data" => array("jobs" => $jobs,"total_jobs" => $jobs->count())]);
    	}else{
    		return response()->json(["status" => 404 ,"message" => 'Location Not Found!']);
    	}
    }

    // public function post_jobs(Request $request)
    // {
    // 	return response()->json(['request' => $request]);
    // }
}
