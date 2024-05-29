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
use GuzzleHttp\Client;
use App\Mail\JobApplyMail;
use App\User;
use App\JobCategory;
use App\Skill;
use App\Location;
use App\Education;
use App\Experience;
use App\Training;
use App\JobPreference;
use App\CategorySkill;
use App\Job;
use App\JobApply;
use App\JobWatchlist;
use App\SkillAssessment;
use App;

class CustomerController extends Controller
{
    public function jobseeker_profile()
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (Auth::user()->role != 2) {
        		return redirect()->back()->with('error', 'You are not Job Seeker!!');
        	}
        }

        $jobseeker = User::where('id', Auth::user()->id)->firstOrFail(); 

        $educations = $jobseeker->education;
        $experiences = $jobseeker->experience;
        $trainings = $jobseeker->training;
        $preference = $jobseeker->preference;
        $applied_jobs = $jobseeker->applied_jobs()->orderBy('created_at','desc')->get();
        $watchlist_jobs = $jobseeker->watchlist_jobs()->orderBy('created_at','desc')->get();

        $skill_assessments = SkillAssessment::where('display',1)->get();
        // dd($watchlist_jobs);
        // dd($preference);
        return view('jobseeker.jobseeker-profile', compact('jobseeker','educations','experiences','trainings','preference','applied_jobs','watchlist_jobs', 'skill_assessments'));
    }

    public function update_basic_info(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => [
                    'required',
                    Rule::unique('users')->ignore(Auth::user()->id)
                    ],
            'dob' => 'required',
            'current_address' => 'required',
            'permanent_address' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'maritial_status' => 'required',
            'image' => 'sometimes|required|mimes:jpg,jpeg,pdf|max:2048'
        ]);

        $jobseeker = User::findOrFail(Auth::user()->id);

        $jobseeker->name = $request['name'];
        $jobseeker->phone = $request['phone'];
        $jobseeker->dob = $request['dob'];
        $jobseeker->current_address = $request['current_address'];
        $jobseeker->permanent_address = $request['permanent_address'];
        $jobseeker->religion = $request['religion'];
        $jobseeker->nationality = $request['nationality'];
        $jobseeker->gender = $request['gender'];
        $jobseeker->maritial_status = $request['maritial_status'];

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $validatedData = $request->validate([
                'image' => 'required|mimes:jpg,jpeg,pdf|max:2048',
            ]);

            $oldjobseeker = $jobseeker->image;

            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $folderPath = "public/jobseekers";
            $thumbPath = "public/jobseekers/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

            Storage::delete($folderPath .'/'. $oldjobseeker);
            Storage::delete($folderPath .'/thumbs/large_'. $oldjobseeker);
            Storage::delete($folderPath .'/thumbs/small_'. $oldjobseeker);

            $jobseeker->image = $filename;

        }

        $jobseeker->save();
        return redirect()->back()->with("status", "Basic Information Updated Successfully!");
    }

    public function update_education(Request $request)
    {
        // dd($request);
        if (isset($request->education_id)) {

            for($i=0; $i < count($request->degree_db); $i++){
                $educationUpdateArray = array("degree" => $request->degree_db[$i], 
                                              "program" => $request->program_db[$i], 
                                              "board" => $request->board_db[$i], 
                                              "institute" => $request->institute_db[$i], 
                                              "student_here" => isset($request->student_here_db[$i]) ? $request->student_here_db[$i] : 0,
                                              "year" => $request->year_db[$i],
                                              "month" => $request->month_db[$i],
                                              "marks_unit" => isset($request->student_here_db[$i]) ? '' : $request->marks_unit_db[$i],
                                              "marks" => isset($request->student_here_db[$i]) ? '' : $request->marks_db[$i],
                                              "updated_by" => Auth::user()->name
                                            );
                Education::where('id', $request->education_id[$i])->update($educationUpdateArray);
            }

        }
        

        if (isset($request->degree)) {
            $jobseeker = User::where('id', Auth::user()->id)->firstOrFail();

            $educationArray = array();  

            for($i=0; $i < count($request->degree); $i++){
                array_push($educationArray, array("degree" => $request->degree[$i], 
                                                  "program" => $request->program[$i], 
                                                  "board" => $request->board[$i], 
                                                  "institute" => $request->institute[$i], 
                                                  "student_here" => isset($request->student_here[$i]) ? $request->student_here[$i] : 0,
                                                  "year" => $request->year[$i],
                                                  "month" => $request->month[$i],
                                                  "marks_unit" => isset($request->student_here[$i]) ? '' : $request->marks_unit[$i],
                                                  "marks" => isset($request->student_here[$i]) ? '' : $request->marks[$i],
                                                  "created_by" => Auth::user()->name
                                                )
                          );
            }

            $jobseeker->education()->createMany($educationArray);
        }
        return redirect()->back()->with("status", "Education Updated Successfully!");

    }

    public function delete_education($education_id){
        // dd(base64_decode($education_id));
        $education = Education::findOrFail(base64_decode($education_id));

        if ($education->delete()) {
            return redirect()->back()->with('status','Education Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    public function add_educations($cIndex){
        if($cIndex){
            $responseText = '<div class="panel panel-info" id="education'.$cIndex.'">
                        <div class="panel-body">
                            <div class="row school-experience">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-danger btn_remove_education" id="'.$cIndex.'">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="fa fa-graduation-cap"></i> &nbsp; Degree* </label>
                                    <input type="text" name="degree[]" required value="" placeholder="eg: Intermediate, Bachelors">
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                    <label><i class="fa fa-graduation-cap"></i> &nbsp; Education Program*</label>

                                    <input type="text" name="program[]" required value="" placeholder="eg: MBS, BCA, BBA">
                                </div>
                                <div class="col-md-6">
                                    
                                    <label><i class="fa fa-graduation-cap"></i> &nbsp; Education Board*</label>
                                
                                    <input type="text" name="board[]" required value="" placeholder="eg: TU, KU, PoU...">
                                </div>
                                <div class="col-md-6">
                                    <label><i class="fa fa-graduation-cap"></i> &nbsp; Name of Institute*</label>
                                        
                                    <input type="text" name="institute[]" required value="" placeholder="eg: KTM RUSH Academics..">

                                </div>
                                <div class="col-md-6">
                                    <label><i class="fa fa-graduation-cap"></i> &nbsp;Currently Studying Here</label>
                                    <input type="checkbox" name="student_here[]" value="'.$cIndex.'" class="student_here_checkbox" id="'.$cIndex.'">
                                </div>

                                <div class="col-md-6">
                                    <label style="width: 100%">
                                        <i class="fa fa-graduation-cap"></i> &nbsp; 
                                        <span id="YearGradStartId'.$cIndex.'">Graduation Year</span>
                                    </label>

                                    <select class="ui dropdown" name="year[]" style="width: 50%">
                                        <option value="">Year</option>';
                                        for($year = date('Y'); $year >= date('Y')-50; $year--){
                                            $responseText .= '<option value="'.$year.'">'.$year.'</option>';
                                        }
                                    $responseText .= '</select>';
                                    
                                    $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];
                                    
                                    $responseText .= '<select class="ui dropdown" name="month[]" style="width: 50%">
                                        <option value="">Month</option>';

                                        foreach($months as $key => $month){
                                            $responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                        }
                                    $responseText .= '</select>
                                </div>

                                <div class="col-md-6" id="MarksSecuredIn'.$cIndex.'">
                                    <label style="width: 100%"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</label>
                                    <div class="input-group mb-3">
                                        <select class="ui dropdown marks_secured'.$cIndex.'" name="marks_unit[]" style="width: 50%;" required>
                                            <option value="1">Percentage</option>
                                            <option value="2">CGPA</option>
                                        </select>
                                        <input type="text" class="marks_secured'.$cIndex.'" name="marks[]" placeholder="Marks Secured" style="width: 50%;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            
            return $responseText;
        }
    }

    /*========================================================================================================
      ========================================================================================================*/
    public function update_experience(Request $request)
    {
        // dd($_POST);

        if (isset($request->experience_id)) {
            for($i=0; $i < count($request->organization_name_db); $i++){
                $experienceUpdateArray = array("organization_name" => $request->organization_name_db[$i], 
                                              "job_location" => $request->job_location_db[$i], 
                                              "job_title" => $request->job_title_db[$i], 
                                              "job_category_id" => $request->job_category_id_db[$i], 
                                              "working_here" => isset($request->working_here_db[$i]) ? $request->working_here_db[$i] : 0,
                                              "start_year" => $request->start_year_db[$i],
                                              "start_month" => $request->start_month_db[$i],
                                              "end_year" => isset($request->working_here_db[$i]) ? '' : $request->end_year_db[$i],
                                              "end_month" => isset($request->working_here_db[$i]) ? '' : $request->end_month_db[$i],
                                              "duties_responsibilities" => $request->duties_responsibilities_db[$i],
                                              "updated_by" => Auth::user()->name
                                            );
                Experience::where('id',$request->experience_id[$i])->update($experienceUpdateArray);
            }

        }
        

        if (isset($request->organization_name)) {
            $jobseeker = User::where('id', Auth::user()->id)->firstOrFail();

            $experienceArray = array(); 

            for($i=0; $i < count($request->organization_name); $i++){
                array_push($experienceArray, array("organization_name" => $request->organization_name[$i], 
                                                  "job_location" => $request->job_location[$i], 
                                                  "job_title" => $request->job_title[$i], 
                                                  "job_category_id" => $request->job_category_id[$i], 
                                                  "working_here" => isset($request->working_here[$i]) ? $request->working_here[$i] : 0,
                                                  "start_year" => $request->start_year[$i],
                                                  "start_month" => $request->start_month[$i],
                                                  "end_year" => isset($request->working_here[$i]) ? '' : $request->end_year[$i],
                                                  "end_month" => isset($request->working_here[$i]) ? '' : $request->end_month[$i],
                                                  "duties_responsibilities" => $request->duties_responsibilities[$i],
                                                  "created_by" => Auth::user()->name
                                                )
                          );
            }

            $jobseeker->experience()->createMany($experienceArray);
        }

        return redirect()->back()->with("status", "Experience Updated Successfully!");

    }

    public function delete_experience($experience_id){
        
        $experience = Experience::findOrFail(base64_decode($experience_id));

        if ($experience->delete()) {
            return redirect()->back()->with('status','Work Experience Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    public function add_experiences($cIndex){
        if($cIndex){
            $jobCategories = JobCategory::where('display',1)->orderBy('order_item')->get();

            $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

            $responseText = '<div class="panel panel-info" id="experience'.$cIndex.'">
                                <div class="panel-body">
                                    <div class="row school-experience">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-sm btn-danger btn_remove_experience" id="'.$cIndex.'">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <label><i class="fa fa-building"></i> &nbsp; Organization name* </label>
                                            <input type="text" name="organization_name[]" required value="" placeholder="eg: Laxmi Bank Ltd.">
                                            
                                        </div>
                                        <div class="col-md-6">
                                            
                                            <label><i class="fa fa-building"></i> &nbsp; Job Location*</label>

                                            <input type="text" name="job_location[]" required value="" placeholder="eg: Kathmandu">
                                        </div>
                                        <div class="col-md-6">
                                            
                                            <label><i class="fa fa-building"></i> &nbsp; Job Title*</label>
                                        
                                            <input type="text" name="job_title[]" required value="" placeholder="eg: Branch Manager...">
                                        </div>

                                        <div class="col-md-6">
                                            <label style="width: 100%;">
                                                <i class="fa fa-building"></i> &nbsp; Job Category*
                                            </label>
                                            
                                            <select name="job_category_id[]" required>

                                                <option selected disabled>Select Job Category</option>';

                                                foreach($jobCategories as $jobCat){
                                                    $responseText .= '<option value="'. $jobCat->id .'">'.$jobCat->title .'</option>';
                                                }
                                            $responseText .= '</select>
                                        
                                        </div>
                                        <div class="clearfix" style="padding-bottom: 0px;"></div>

                                        <div class="col-md-6">
                                            <label><i class="fa fa-building"></i> &nbsp;Currently Working Here</label>
                                            <input type="checkbox" name="working_here[]" value="1" class="working_here_checkbox" id="'.$cIndex.'">
                                        </div>

                                        <div class="col-md-6">
                                            <label style="width: 100%;">
                                                <i class="fa fa-building"></i> &nbsp; Start Date*
                                            </label>
                                            
                                            <select class="ui dropdown" name="start_year[]" required style="width: 50%">
                                                <option disabled selected>Year</option>';
                                                    for($year = date('Y'); $year >= date('Y')-50; $year--){
                                                    $responseText .= '<option value="'.$year.'">'.$year.'</option>';
                                                    }
                                                $responseText .= '</select>
                                            <select class="ui dropdown" name="start_month[]" required style="width: 50%">
                                                <option disabled selected>Month</option>';

                                                    foreach($months as $key => $month){
                                                    $responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                                    }
                                                $responseText .= '</select>
                                        
                                        </div>

                                        <div class="col-md-6" id="EndDate'.$cIndex.'">
                                            
                                            <label style="width: 100%;">
                                                <i class="fa fa-building"></i> &nbsp; End Date*
                                            </label>
                                            
                                            
                                            <select class="end_date'.$cIndex.'" name="end_year[]" required style="width: 50%">
                                                <option disabled selected>Year</option>';

                                                    for($year = date('Y'); $year >= date('Y')-50; $year--){
                                                        $responseText .=  '<option value="'.$year.'">'.$year.'</option>';
                                                    }
                                                $responseText .= '</select>

                                            <select class=" end_date'.$cIndex.'" name="end_month[]" required style="width: 50%">
                                                <option disabled selected>Month</option>';

                                                    foreach($months as $key => $month){
                                                        $responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                                    }

                                                $responseText .= '</select>
                                        
                                        </div>

                                        <div class="col-md-12">
                                            <label>
                                                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
                                            </label>
                                            <textarea name="duties_responsibilities[]"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>';
            
            return $responseText;
        }
    }

    /*========================================================================================================
      ========================================================================================================*/

    public function update_training(Request $request)
    {
        // dd($_POST);
        
        if (isset($request->training_id)) {
            for($i=0; $i < count($request->name_db); $i++){
                $trainingUpdateArray = array("name" => $request->name_db[$i], 
                                              "institution" => $request->institution_db[$i], 
                                              "duration_unit" => $request->duration_unit_db[$i],
                                              "duration" => $request->duration_db[$i],
                                              "year" => $request->year_db[$i],
                                              "month" => $request->month_db[$i],
                                              "updated_by" => Auth::user()->name
                                            );
                Training::where('id',$request->training_id[$i])->update($trainingUpdateArray);
            }

        }
        

        if (isset($request->name)) {
            $jobseeker = User::where('id', Auth::user()->id)->firstOrFail();

            $trainingArray = array();   

            for($i=0; $i < count($request->name); $i++){
                array_push($trainingArray, array("name" => $request->name[$i], 
                                                  "institution" => $request->institution[$i], 
                                                  "duration_unit" => $request->duration_unit[$i],
                                                  "duration" => $request->duration[$i],
                                                  "year" => $request->year[$i],
                                                  "month" => $request->month[$i],
                                                  "created_by" => Auth::user()->name
                                                )
                          );
            }

            $jobseeker->training()->createMany($trainingArray);
        }

        return redirect()->back()->with("status", "Training Updated Successfully!");

    }

    public function add_trainings($cIndex){
        if($cIndex){
            $responseText = '<div class="panel panel-info" id="training'.$cIndex.'">
                                <div class="panel-body">
                                    <div class="row school-experience">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-sm btn-danger btn_remove_training" id="'.$cIndex.'">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                                <label><i class="fa fa-tasks"></i> &nbsp; Name of the Training* </label>
                                                <input type="text" name="name[]" required value="" placeholder="eg: Training of Branch Management">
                                                <hr>
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <label><i class="fa fa-tasks"></i> &nbsp; Name of Institution*</label>
                                                
                                            <input type="text" name="institution[]" required value="" placeholder="eg: Creators Institute of Business and Technology">
                                            <hr>
                                        </div>

                                        <div class="col-md-6">
                                            <label style="width: 100%;"><i class="fa fa-tasks"></i> &nbsp; Duration*</label>
                                            <input style="width: 50%;" type="number" min="0" value="0" name="duration[]" required>

                                            <select style="width: 50%;" class="ui dropdown" name="duration_unit[]" required>
                                                <option value="1">Day</option>
                                                <option value="2">Week</option>
                                                <option value="3">Month</option>
                                                <option value="4">Year</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label style="width:100%;">
                                                <i class="fa fa-tasks"></i> &nbsp; Completion Year*
                                            </label>
                                            <select style="width: 50%;" class="ui dropdown" name="year[]">
                                                <option value="" disabled selected>Year</option>';

                                                        for($year = date('Y'); $year >= date('Y')-50; $year--){
                                                        $responseText .= '<option value="'.$year.'">'.$year.'</option>';
                                                        }
                                                    $responseText .= '</select>';

                                                    $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

                                                    $responseText .= '<select style="width: 50%;" class="ui dropdown" name="month[]">
                                                        <option value="" disabled selected>Month</option>';

                                                        foreach($months as $key => $month){
                                                        $responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                                        }
                                                    $responseText .= '</select>
                                        </div>

                                    </div>
                                </div>
                            </div>';

            return $responseText;
        }
    }

    public function delete_training($training_id){
        $training = Training::findOrFail(base64_decode($training_id));

        if ($training->delete()) {
            return redirect()->back()->with('status','Training Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    /*=======================================================================================================*/

    public function update_job_preference(Request $request)
    {   
        
        dd($_POST);

        $jobseeker = User::where('id', base64_decode($id))->firstOrFail();

        $job_preference = JobPreference::updateOrCreate(
                                        ['user_id' =>base64_decode($id)],
                                        ["looking_for" => $request->looking_for, 
                                          "employment_type" => json_encode($request->employment_type), 
                                          "expected_salary" => $request->expected_salary, 
                                          "expected_salary_period" => $request->expected_salary_period, 
                                          "location_id" => isset($request->location_id) ? $request->location_id : 0,
                                          "career_objective" => $request->career_objective,
                                          "created_by" => Auth::user()->name
                                        ]
                            );

        // dd($job_preference->id);

        
        if (isset($request->job_category_id)) {
            
            $job_category_id = array_values($request->job_category_id);
            $skills = array_values($request->skills);
            $jobCategorySkillArray = array();

            for($i=0; $i < count($job_category_id); $i++){
                
                array_push($jobCategorySkillArray, array("job_category_id" => $job_category_id[$i],
                                                        "skill_ids" => json_encode($skills[$i])));
            }

            // dd($jobCategorySkillArray);
            $job_preference->category_skills()->createMany($jobCategorySkillArray);
            // $jobseeker->experience()->createMany($experienceArray);
        }

        if (isset($request->category_skill_id)) {
            $category_skill_id = array_values($request->category_skill_id);
            $job_category_id_db = array_values($request->job_category_id_db);
            $skills_db = array_values($request->skills_db);
            $jobCategorySkillUpdate = array();

            for($i=0; $i < count($job_category_id_db); $i++){
                $jobCategorySkillUpdate = array("job_category_id" => $job_category_id_db[$i],
                                                "skill_ids" => json_encode($skills_db[$i]));
                CategorySkill::where('id',$category_skill_id[$i])->update($jobCategorySkillUpdate);
            }
        }
        

        return redirect()->route('admin.job-seekers.job-preference', ['id' => $id])->with("status", "Job Seekers Job Preferences Updated Successfully!");

    }

    public function add_job_preferences($cIndex){
        if($cIndex){

            $jobCategories = JobCategory::where('display',1)->orderBy('order_item')->get();

            $responseText = '<div class="panel-body pb-0" id="preference'.$cIndex.'">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>
                                            <i class="fa fa-building"></i> &nbsp; Job Category*
                                        </label>
                                        <select class="ui dropdown select_job_category" name="job_category_id['.$cIndex.']" required id="'.$cIndex.'">

                                            <option selected disabled>Select Job Category</option>';

                                            foreach($jobCategories as $jobCat){
                                                $responseText .= '<option value="'.$jobCat->id.'">'. $jobCat->title.'</option>';
                                            }
                                        $responseText .='</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>
                                            <i class="fa fa-building"></i> &nbsp; Skills*
                                        </label>
                                        <div class="mb-3" id="skillSelect'.$cIndex.'">
                                            <select class="ui dropdown" required>

                                                <option selected disabled>Select Job Category First</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-md-1 text-right">
                                        <button type="button" class="btn btn-sm btn-danger btn_remove_preference" id="'.$cIndex.'">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                    </div>  
                                </div>
                            </div><hr>';

            
            return $responseText;
        }
    }

    public function show_job_categories_skills(Request $request){
        $job_category_id = $request->job_category_id;
        $select_id = $request->select_id;
        $dbSkills = array();


        if($job_category_id){

            $jobCategorySkills = Skill::where('job_category_id', $job_category_id)->get();
            $responseText ='<select class="ui fluid dropdown multi-select skills_multiselect"  name="skills['.$select_id.'][]" required multiple="multiple"><option selected disabled>Select Skills</option>';

            if ($request->cat_skill_id != '') {
                $responseText ='<select class="ui fluid dropdown multi-select skills_multiselect"  name="skills_db['.$select_id.'][]" required multiple="multiple"><option selected disabled>Select Skills</option>';                         
                $dbSkills = CategorySkill::where('id',$request->cat_skill_id)->first()->skill_ids;
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

    public function delete_category_skill($category_skill_id){
        $category_skill = CategorySkill::findOrFail(base64_decode($category_skill_id));

        if ($category_skill->delete()) {
            return redirect()->back()->with('status','Job Category Preference Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    public function apply_job($job_slug)
    {
        if (!Auth::check()) {
            
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            
            if (Auth::user()->role != 2) {
                return redirect()->back()->with('error', 'Only Jobseekers can Apply Job!!');
            }

        }   

        $job = Job::where('slug', $job_slug)->firstOrFail();
        $employer_info = $job->user->employer_info;
        $featured_jobs = Job::where([['id','!=',$job->id],['display',1]])->orderBy('views','desc')->limit(4)->get();

        $jobseeker = User::where('id', Auth::user()->id)->firstOrFail(); 
        return view('jobs.apply-job',compact('job','employer_info','jobseeker','featured_jobs'));
    }

    public function confirm_apply_job(Request $request)
    {
        if (!Auth::check()) {
            
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            
            if (Auth::user()->role != 2) {
                return redirect()->back()->with('error', 'Only Jobseekers can Apply Job!!');
            }

        }   

        // dd($_POST);
        $validatedData = $request->validate([
            'job_id' => 'required',
            'employer_id' => 'required',
            'jobseeker_id' => 'required'
        ]);

        $job_id = base64_decode($request->job_id);
        $employer_id = base64_decode($request->employer_id);
        $jobseeker_id = base64_decode($request->jobseeker_id);

        $employer = User::where('id', $employer_id)->first();

        $job = Job::where([['id', $job_id],['user_id',$employer_id]])->first();

        $alreadyApplied = JobApply::where([['job_id', $job_id],['jobseeker_id',$jobseeker_id]])->exists();

        if ($alreadyApplied) {
            return redirect()->back()->with('error','You have already applied for this Job!');
        }

        $job = Job::where('id', $job_id)->first();

        if (Auth::user()->id != $jobseeker_id || !$job || $job->user_id != $employer_id) {
            return redirect()->back()->with('error','Something went Wrong!');
        }


        $jobApplied = JobApply::create(['job_id' => $job_id,
                                    'employer_id' => $employer_id,
                                    'jobseeker_id' => $jobseeker_id,
                                    'approved' => 0,
                                    'status' => 0,
                                ]);
        
        if (isset($jobApplied)) {

            $jobApplyMessage = array(
                
                'jobseeker_name' => Auth::user()->name,
                'jobseeker_email' => Auth::user()->email,
                'employer_name' => $employer->employer_info->organization_name,
                'employer_email' => $employer->employer_info->email,
                'job_title' => $job->job_title,
                'job_url' => route('job-details',['slug' => $job->slug]),
                'intern_nexus_email' => \App\Setting::where('id', 1)->first()->siteemail,
            );


            Mail::to($jobApplyMessage['employer_email'])->send(new JobApplyMail($jobApplyMessage));
    
            $jobApplyMessage['send_message_to'] = 'jobseeker';
            Mail::to($jobApplyMessage['jobseeker_email'])->send(new JobApplyMail($jobApplyMessage));
        
            // dd('success');

            return redirect()->route('job-details', ['slug' => $job->slug])->with('status','Your Application has been sent Successfully!');
        }else{

            return redirect()->route('job-details', ['slug' => $job->slug])->with('error','Something Went Wrong!');
        }
    }

    public function add_to_watchlist($job_slug)
    {
        if (!Auth::check()) {
            
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            
            if (Auth::user()->role != 2) {
                return redirect()->back()->with('error', 'Only Jobseekers can Apply Job!!');
            }
        }   

        $job = Job::where('slug', $job_slug)->firstOrFail();

        $alreadyExists = JobWatchlist::where([['job_id', $job->id],['jobseeker_id',Auth::user()->id]])->exists();

        if ($alreadyExists) {
            return redirect()->back()->with('error','Job Already Exists in your Watchlist.');
        }

        $jobWatchlistAdded = JobWatchlist::create(['job_id' => $job->id,
                                    'jobseeker_id' => Auth::user()->id
                                ]);
        
        if ($jobWatchlistAdded) {
            return redirect()->back()->with('status','Job Added to Watchlist.');
        }
        
    }

    public function remove_from_watchlist($id){
        $watchlist = JobWatchlist::findOrFail(base64_decode($id));

        if ($watchlist->delete()) {
            return redirect()->back()->with('status','Job Removed from your WatchList Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    // ==================================================================================================

    public function create_resume(Request $request)
    {
        
        $client = new Client();
        // dd(env('OPENAI_API_KEY'));
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        // ])->post('https://api.openai.com/v1/chat/completions', [
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => "hello",
        // ]);

        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
        	if (Auth::user()->role != 2) {
        		return redirect()->back()->with('error', 'You are not Job Seeker!!');
        	}
        }

        $jobseeker = User::where('id', Auth::user()->id)->firstOrFail(); 

        $educations = $jobseeker->education;
        $educationText = "";
        foreach ($educations as $key => $education) {
            $educationSingle = " Education ".($key+1).": ". $education->degree . " in ". $education->program . ", " . $education->board;
            if($education->student_here == 1){
                $educationSingle .= " Started at: ".$education->year . "/" . $education->month;
            }else{
                $educationSingle .= " Completed at: ".$education->year . "/" . $education->month . " Marks secured: ";
                if($education->marks_unit == 1){
                    $educationSingle .= $education->marks. "%";
                }else{
                    $educationSingle .= $education->marks. " CGPA \n\n";
                }
            }
            $educationText .= $educationSingle;
        }
        
        $experiences = $jobseeker->experience;

        
        $experienceText = "";
        foreach ($experiences as $key => $experience) {
            $experienceSingle = " experience ".($key+1).": Organization Name: ". $experience->organization_name . " Location :". $experience->job_location . ", Job Title : " . $experience->job_title;
            $experienceSingle .= " Start date: ".$experience->start_year . "/" . $experience->start_month;

            if($experience->working_here != 1){

                $experienceSingle .= " End Date: ".$experience->end_year . "/" . $experience->end_month ;
            }

            $experienceSingle .= " Duties/Responsibilities : ". $education->duties_responsibilities;
            $experienceText .= $experienceSingle;
        }

        $trainings = $jobseeker->training;
        
        $trainingText = "";
        foreach ($trainings as $key => $training) {
            $trainingSingle = " training ".($key+1).": Training Name: ". $training->name . " Institution :". $training->institution . ", Duration : " . $training->duration;
            $durationArray = ['1' => "Day", '2' => "Week", '3'=> "Month", '4' => "Year"];
            $trainingSingle .= " ". $durationArray[$training->duration_unit];

            $trainingSingle .= " Completion date: ".$training->year . "/" . $training->month;

            
            $trainingText .= $trainingSingle;
        }

        // dd($trainingText);

        $preference = $jobseeker->preference;

        $prompt = "Please generate a resume for ".$jobseeker->name."
                  with the following details:\n\n
                  Name: ".$jobseeker->name."\n
                  Email: ".$jobseeker->email."\n
                  Phone: ".$jobseeker->phone."\n
                  Current Address: ".$jobseeker->current_address."\n
                  Permanent Address: ".$jobseeker->permanent_address."\n
                  Education: ".$educationText."\n
                  Experience: ".$experienceText."\n
                  Training: ".$trainingText."\n";
        // dd($prompt);
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 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',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-1106',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt. "in paragraphs and bullet points in 1000 words in html tags just body part"],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1600,
                'top_p' => 1,
                'n' => 1,
                'stop' => ['\n'],
            ],
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        // dd($result);
        $resume_output = $result['choices'][0]['message']['content'];
        // dd($resume_output);
        return view('jobseeker.create-resume', compact('resume_output'));

        // return redirect()->route('job-seeker.resume-result')->with("status", "Resume created Successfully!");
        // return response()->json($result['choices'][0]['text']);
    }

    // public function resume_result(Request $request){

    //     if (!Auth::check()) {
    //         return redirect()->route('front_login')->with('error', 'Please Log In!!');
    //     }else{
    //     	if (Auth::user()->role != 2) {
    //     		return redirect()->back()->with('error', 'You are not Job Seeker!!');
    //     	}
    //     }

    //     $jobseeker = User::where('id', Auth::user()->id)->firstOrFail(); 

    //     $educations = $jobseeker->education;
    //     $experiences = $jobseeker->experience;
    //     $trainings = $jobseeker->training;
    //     $preference = $jobseeker->preference;
    //     $applied_jobs = $jobseeker->applied_jobs()->orderBy('created_at','desc')->get();
    //     $watchlist_jobs = $jobseeker->watchlist_jobs()->orderBy('created_at','desc')->get();
    //     // dd($watchlist_jobs);
    //     // dd($preference);
    //     return view('jobseeker.jobseeker-profile', compact('jobseeker','educations','experiences','trainings','preference','applied_jobs','watchlist_jobs'));
    // }


}
