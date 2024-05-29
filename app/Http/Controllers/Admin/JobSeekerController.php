<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\User;
use App\JobCategory;
use App\Skill;
use App\Education;
use App\Training;
use App\Experience;
use App\JobPreference;
use App\CategorySkill;

class JobSeekerController extends Controller
{
    public function index()
    {
        $jobseekers = User::where('role',2)->get();
        return view('admin.jobseekers.job-seekers',array('jobseekers' => $jobseekers, 'id' => 0));
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
            $folderPath = "public/jobseekers";
            $thumbPath = "public/jobseekers/thumbs";
            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }
            
            Storage::putFileAs($folderPath, new File($image), $filename);
            
            User::resize_crop_images(800, 800, $image, $thumbPath."/large_". $filename);
            User::resize_crop_images(200, 200, $image, $thumbPath."/small_". $filename);

            // $jobseeker->image = $filename;

        }

        $jobseeker = User::create([
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

        return redirect()->to('admin/job-seekers')->with('status', 'Job Seeker added Successfully!');

    }

    public function edit($id)
    {
        $id = base64_decode($id);
       	$jobseeker = User::findOrFail($id);
       	return view('admin.jobseekers.job-seekers',array('jobseeker' => $jobseeker, 'id' => $id));
    }

    public function update(Request $request)
    {
        $jobseeker = User::findOrFail($request->id);
        $request['status'] = isset($request['status']) ? $request['status'] : '0';
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => [
                    'required',
                    Rule::unique('users')->ignore($jobseeker->id)
                    ],
        ]);
        $jobseeker->name = $request['name'];
        $jobseeker->role = $request['role'];
        $jobseeker->status = $request['status'];
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
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
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
        return redirect('admin/job-seekers')->with('status', 'Job Seeker Updated Successfully!');

    }

    public function delete($id)
    {

        $jobseeker = User::where('id', $id)->firstOrFail();
        if ($jobseeker) {
            $jobseeker->delete();
            return redirect()->back()->with('status', 'Job Seeker Deleted Successfully!');
            
        }
        return redirect()->back()->with('status', 'Something Went Wrong!');
    }

    public function profile($id)
    {
    	$jobseeker = User::where('id',base64_decode($id))->firstOrFail();
        return view('admin.jobseekers.job-seekers-profile',array('jobseeker' => $jobseeker, 'id' => $id));
    }

    /*-----------------------------------BASIC INFORMATION PART-------------------------------------------*/

    public function basic_info($id)
    {
    	$jobseeker = User::where('id',base64_decode($id))->firstOrFail();
        return view('admin.jobseekers.job-seekers-basic-info',array('jobseeker' => $jobseeker, 'id' => $id));
    }

    public function edit_basic_info($id)
    {
    	$jobseeker = User::where('id',base64_decode($id))->firstOrFail();
        return view('admin.jobseekers.job-seekers-edit-basic-info',array('jobseeker' => $jobseeker, 'id' => $id));
    }

    public function update_basic_info($id, Request $request)
    {
    	// dd($_POST);
    	$jobseeker = User::findOrFail(base64_decode($id));

        $request['status'] = isset($request['status']) ? $request['status'] : '0';

        $validatedData = $request->validate([
            'name' => 'required|max:255'
        ]);

        $jobseeker->name = $request['name'];
        $jobseeker->status = $request['status'];
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
                'image' => 'required|mimes:jpg,jpeg,png|max:2048',
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
        return redirect()->route('admin.job-seekers.basic-info', ['id' => $id])->with("status", "Job Seekers Basic Information Updated Successfully!");
    }

    /*-----------------------------------EDUCATION PART-------------------------------------------*/

	public function education($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$educations = $jobseeker->education;
        return view('admin.jobseekers.job-seekers-education',array('jobseeker' => $jobseeker, 'educations' => $educations, 'id' => $id));
    }

    public function edit_education($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$educations = $jobseeker->education;
        return view('admin.jobseekers.job-seekers-edit-education',array('jobseeker' => $jobseeker, 'educations' => $educations, 'id' => $id));
    }

    public function update_education($id, Request $request)
    {
    	
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
    			Education::where('id',$request->education_id[$i])->update($educationUpdateArray);
    		}

    	}
    	

    	if (isset($request->degree)) {
    		$jobseeker = User::where('id', base64_decode($id))->firstOrFail();

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

		return redirect()->route('admin.job-seekers.education', ['id' => $id])->with("status", "Job Seekers Education Updated Successfully!");

    }

    public function add_educations($id, $cIndex){
        if($cIndex){
            $responseText = '<div class="card" id="education'.$cIndex.'">
                        <div class="card-body">
                        	<div class="row">
                        		<div class="offset-md-10 col-md-2 text-right">
                        			<button type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
                        				<i class="fa fa-trash"></i>
                        			</button>
                        		</div>
                        		<div class="col-md-6">
                        		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Degree* </small>
                        		        <input type="text" name="degree[]" class="form-control" required value="" placeholder="eg: Intermediate, Bachelors">
                        		        <hr>
                        		    
                        		</div>
                        		<div class="col-md-6">
                        		    
                    		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Program*</small>

                    		        <input type="text" name="program[]" class="form-control" required value="" placeholder="eg: MBS, BCA, BBA">
                    		        <hr>
                        		</div>
                        		<div class="col-md-6">
                        		    
            		                <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Board*</small>
            		            
            		        		<input type="text" name="board[]" class="form-control" required value="" placeholder="eg: TU, KU, PoU...">
            		        		<hr>
                        		</div>
                        		<div class="col-md-6">
                    		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Name of Institute*</small>
                    		            
                    		        <input type="text" name="institute[]" class="form-control" required value="" placeholder="eg: KTM RUSH Academics..">

                    		        <hr>
                        		</div>
                        		<div class="col-md-6">
                        			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp;Currently Studying Here</small>
                        		    <div class="input-group mb-3">
                        		        <div class="input-group-prepend">
                        		            <div class="input-group-text" style="background-color: #e1e8ed">
                        		                <input type="checkbox" name="student_here[]" value="1" aria-label="Checkbox for following text input" id="'.$cIndex.'"  class="student_here_checkbox">
                        		            </div>
                        		        </div>
                        		        <span class="form-control disabled">Currently Studying Here</span>
                        		    </div>
                        		    <hr>
                        		</div>

                        		<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-graduation-cap"></i> &nbsp; 
                		                <span id="YearGradStartId'.$cIndex.'">Graduation Year</span>
                		            </small>
                        		    <div class="input-group mb-3">
                        		        <select class="form-control" name="year[]">
                        		        	<option value="">Year</option>';

                        		        	for($year = date('Y'); $year >= date('Y')-50; $year--){
                                            	$responseText .= '<option value="'.$year.'">'.$year.'</option>';
                                            }
                                        $responseText .= '</select>';
                                        
                                        $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

                                        $responseText .= '<select class="form-control" name="month[]">
                        		        	<option value="">Month</option>';

                        		        	foreach($months as $key => $month){
                                            $responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                            }
                                        $responseText .= '</select>
                        		    </div>
                        		</div>

                        		<div class="col-md-6" id="MarksSecuredIn'.$cIndex.'">
                        			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</small>
                        		    <div class="input-group mb-3">
                        		        <select class="form-control marks_secured'.$cIndex.'" name="marks_unit[]" required>
                                            <option value="1">Percentage</option>
                                            <option value="2">CGPA</option>
                                        </select>
                                        <input type="text" class="form-control marks_secured'.$cIndex.'" name="marks[]" placeholder="Marks Secured" required>
                        		    </div>
                        		</div>

                        	</div>
                        </div>
                    </div>';
            return $responseText;
        }
    }

    public function delete_education($id, $education_id){
        $education = Education::findOrFail(base64_decode($education_id));

        if ($education->delete()) {
            return redirect()->back()->with('status','Education Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    /*-----------------------------------TRAINING PART-------------------------------------------*/

	public function training($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$trainings = $jobseeker->training;
        return view('admin.jobseekers.job-seekers-training',array('jobseeker' => $jobseeker, 'trainings' => $trainings, 'id' => $id));
    }

    public function edit_training($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$trainings = $jobseeker->training;
        return view('admin.jobseekers.job-seekers-edit-training',array('jobseeker' => $jobseeker, 'trainings' => $trainings, 'id' => $id));
    }

    public function update_training($id, Request $request)
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
    		$jobseeker = User::where('id', base64_decode($id))->firstOrFail();

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

		return redirect()->route('admin.job-seekers.training', ['id' => $id])->with("status", "Job Seekers Training Updated Successfully!");

    }

    public function add_trainings($id, $cIndex){
        if($cIndex){
        	$responseText = '<div class="card" id="training'.$cIndex.'">
	                                <div class="card-body">
	                                	<div class="row">
	                                		<div class="offset-md-10 col-md-2 text-right">
			                        			<button type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
			                        				<i class="fa fa-trash"></i>
			                        			</button>
			                        		</div>
	                                		<div class="col-md-6">
	                                		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of the Training* </small>
	                                		        <input type="text" name="name[]" class="form-control" required value="" placeholder="eg: Training of Branch Management">
	                                		        <hr>
	                                		    
	                                		</div>
	                                		<div class="col-md-6">
	                            		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of Institution*</small>
	                            		            
	                            		        <input type="text" name="institution[]" class="form-control" required value="" placeholder="eg: Creators Institute of Business and Technology">
	                            		        <hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Duration*</small>
	                                		    <div class="input-group mb-3">
	                                		    	<input type="number" min="0" class="form-control" name="duration[]" required>

	                                		        <select class="form-control" name="duration_unit[]" required>
			                                            <option value="1">Day</option>
			                                            <option value="2">Week</option>
			                                            <option value="3">Month</option>
			                                            <option value="4">Year</option>
			                                        </select>
	                                		    </div>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-tasks"></i> &nbsp; Completion Year*
	                        		            </small>
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control" name="year[]">
	                                		        	<option value="" disabled selected>Year</option>';

	                                		        	for($year = date('Y'); $year >= date('Y')-50; $year--){
			                                            $responseText .= '<option value="'.$year.'">'.$year.'</option>';
			                                            }
			                                        $responseText .= '</select>';

			                                        $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

			                                        $responseText .= '<select class="form-control" name="month[]">
	                                		        	<option value="" disabled selected>Month</option>';

	                                		        	foreach($months as $key => $month){
			                                            $responseText .= '<option value="'.$key.'">'.$month.'</option>';
			                                            }
			                                        $responseText .= '</select>
	                                		    </div>
	                                		</div>

	                                	</div>
	                                </div>
			                    </div>';

            return $responseText;
        }
    }

    public function delete_training($id, $training_id){
        $training = Training::findOrFail(base64_decode($training_id));

        if ($training->delete()) {
            return redirect()->back()->with('status','Training Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    /*-----------------------------------WORK EXPERIENCE PART-------------------------------------------*/

	public function work_experience($id)
    {

    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$experiences = $jobseeker->experience;
        return view('admin.jobseekers.job-seekers-experience',array('jobseeker' => $jobseeker, 'experiences' => $experiences, 'id' => $id));
    }

    public function edit_work_experience($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$experiences = $jobseeker->experience;
        return view('admin.jobseekers.job-seekers-edit-experience',array('jobseeker' => $jobseeker, 'experiences' => $experiences, 'id' => $id));
    }

    public function update_work_experience($id, Request $request)
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
    		$jobseeker = User::where('id', base64_decode($id))->firstOrFail();

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

		return redirect()->route('admin.job-seekers.work-experience', ['id' => $id])->with("status", "Job Seekers Experience Updated Successfully!");

    }

    public function add_work_experiences($id, $cIndex){
        if($cIndex){

        	$jobCategories = JobCategory::where('display',1)->orderBy('order_item')->get();

        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

        	$responseText = '<div class="card" id="experience'.$cIndex.'">
                                <div class="card-body">
                                	<div class="row">
                                		<div class="offset-md-10 col-md-2 text-right">
			                    			<button type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
			                    				<i class="fa fa-trash"></i>
			                    			</button>
			                    		</div>
                                		<div class="col-md-6">
                                		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Organization name* </small>
                                		        <input type="text" name="organization_name[]" class="form-control" required value="" placeholder="eg: Laxmi Bank Ltd.">
                                		        <hr>
                                		    
                                		</div>
                                		<div class="col-md-6">
                                		    
                            		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Location*</small>

                            		        <input type="text" name="job_location[]" class="form-control" required value="" placeholder="eg: Kathmandu">
                            		        <hr>
                                		</div>
                                		<div class="col-md-6">
                                		    
                    		                <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Title*</small>
                    		            
                    		        		<input type="text" name="job_title[]" class="form-control" required value="" placeholder="eg: Branch Manager...">
                    		        		<hr>
                                		</div>

                                		<div class="col-md-6">
                                			<small class="text-muted">
                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
                        		            </small>
                                		    <div class="input-group mb-3">
                                		        <select class="form-control" name="job_category_id[]" required>

                                		        	<option selected disabled>Select Job Category</option>';

                                		        	foreach($jobCategories as $jobCat){
		                                            	$responseText .= '<option value="'.$jobCat->id.'">'.$jobCat->title.'</option>';

		                                            }

		                                        $responseText .= '</select>
                                		    </div>
                                		    <hr>
                                		</div>

                                		<div class="col-md-6">
                                			<small class="text-muted"><i class="fa fa-building"></i> &nbsp;Currently Working Here</small>
                                		    <div class="input-group mb-3">
                                		        <div class="input-group-prepend">
                                		            <div class="input-group-text" style="background-color: #e1e8ed">
                                		                <input type="checkbox" name="working_here[]" value="1" class="working_here_checkbox" id="'.$cIndex.'">
                                		            </div>
                                		        </div>
                                		        <span class="form-control disabled">Currently Working Here</span>
                                		    </div>
                                		    <hr>
                                		</div>

                                		<div class="col-md-6">
                                			<small class="text-muted">
                        		                <i class="fa fa-building"></i> &nbsp; Start Date*
                        		            </small>
                                		    <div class="input-group mb-3">
                                		        <select class="form-control" name="start_year[]" required>
                                		        	<option disabled selected>Year</option>';
                                		        	for($year = date('Y'); $year >= date('Y')-50; $year--){
		                                            $responseText .= '<option value="'.$year.'">'.$year.'</option>';
		                                            }
		                                        $responseText .= '</select>
		                                        <select class="form-control" name="start_month[]" required>
                                		        	<option disabled selected>Month</option>';

                                		        	foreach($months as $key => $month){
		                                            $responseText .= '<option value="'.$key.'">'.$month.'</option>';
		                                            }
		                                        $responseText .= '</select>
                                		    </div>
                                		    <hr>
                                		</div>

                                		<div class="col-md-6" id="EndDate'.$cIndex.'">
                                			
                                			<small class="text-muted">
                        		                <i class="fa fa-building"></i> &nbsp; End Date*
                        		            </small>
                                		    
                                		    <div class="input-group mb-3">
                                		        <select class="form-control end_date'.$cIndex.'" name="end_year[]" required>
                                		        	<option disabled selected>Year</option>';

                                		        	for($year = date('Y'); $year >= date('Y')-50; $year--){
		                                            	$responseText .=  '<option value="'.$year.'">'.$year.'</option>';
                                		        	}
		                                        $responseText .= '</select>

		                                        <select class="form-control end_date'.$cIndex.'" name="end_month[]" required>
                                		        	<option disabled selected>Month</option>';

                                		        	foreach($months as $key => $month){
		                                            	$responseText .= '<option value="'.$key.'">'.$month.'</option>';
                                		        	}

		                                        $responseText .= '</select>
                                		    </div>
                                		    <hr>
                                		</div>
                                		<div class="col-md-12">
                                			<small class="text-muted">
                        		                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
                        		            </small>
                        		            <textarea name="duties_responsibilities[]" class="form-control"></textarea>
                        		            <hr>
                                		</div>

                                	</div>
                                </div>
		                    </div>';
            return $responseText;
        }
    }

    public function delete_work_experience($id, $experience_id){
        $experience = Experience::findOrFail(base64_decode($experience_id));

        if ($experience->delete()) {
            return redirect()->back()->with('status','Experience Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    /*-----------------------------------JOB PREFERENCE PART-------------------------------------------*/

	public function job_preference($id)
    {

    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$preferences = $jobseeker->preference;
        return view('admin.jobseekers.job-seekers-preference',array('jobseeker' => $jobseeker, 'preferences' => $preferences, 'id' => $id));
    }

    public function edit_job_preference($id)
    {
    	$jobseeker = User::where('id', base64_decode($id))->firstOrFail();
    	$preference = $jobseeker->preference;
        return view('admin.jobseekers.job-seekers-edit-preference',array('jobseeker' => $jobseeker, 'preference' => $preference, 'id' => $id));
    }

    public function update_job_preference($id, Request $request)
    {   
        
        // dd($_POST);

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

    public function add_job_preferences($id, $cIndex){
        if($cIndex){

        	$jobCategories = JobCategory::where('display',1)->orderBy('order_item')->get();

        	$responseText = '<div class="card-body pb-0" id="preference'.$cIndex.'">
                				<div class="row">
                					<div class="col-md-5">
                						<small class="text-muted">
                    		                <i class="fa fa-building"></i> &nbsp; Job Category*
                    		            </small>
                            		    <div class="input-group mb-3">
                            		        <select class="form-control select_job_category" name="job_category_id['.$cIndex.']" required id="'.$cIndex.'">

                            		        	<option selected disabled>Select Job Category</option>';

                            		        	foreach($jobCategories as $jobCat){
		                                            $responseText .= '<option value="'.$jobCat->id.'">'. $jobCat->title.'</option>';
		                                        }
	                                        $responseText .='</select>
                            		    </div>
                					</div>
                					<div class="col-md-5">
                						<small class="text-muted">
                    		                <i class="fa fa-building"></i> &nbsp; Skills*
                    		            </small>
                                        <div class="mb-3 multiselect_div" id="skillSelect'.$cIndex.'">
                                            <select class="form-control" required>

                                                <option selected disabled>Select Job Category First</option>
                                            </select>
                                        </div>
                            		    
                					</div>
                					
                            		<div class="col-md-2 text-right">
                            			<button type="button" class="btn btn-sm btn-danger btn_remove" id="'.$cIndex.'">
			                    				<i class="fa fa-trash"></i>
			                    			</button>
                            		</div>	
                				</div>
            				</div>';

        	
            return $responseText;
        }
    }

    public function show_job_categories_skills($id, Request $request){
        $job_category_id = $request->job_category_id;
        $select_id = $request->select_id;
        $dbSkills = array();


        if($job_category_id){

            $jobCategorySkills = Skill::where('job_category_id', $job_category_id)->get();
            $responseText ='<select class="form-control multiselect multiselect-custom skills_multiselect"  name="skills['.$select_id.'][]" required multiple="multiple">';

            if ($request->cat_skill_id != '') {
                $responseText ='<select class="form-control multiselect multiselect-custom skills_multiselect"  name="skills_db['.$select_id.'][]" required multiple="multiple">';                         
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

    public function delete_category_skill($id, $category_skill_id){
        $category_skill = CategorySkill::findOrFail(base64_decode($category_skill_id));

        if ($category_skill->delete()) {
            return redirect()->back()->with('status','Job Category Preference Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

}
