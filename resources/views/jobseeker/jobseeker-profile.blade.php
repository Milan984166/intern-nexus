@extends('layouts.app')
@section('title',Auth::user()->name)
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush
@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">

	<div class="container">

		<h1>Update Your Information</h1>

	</div>

</section>

<!--INNER BANNER END--> 



<!--MAIN START-->

<div id="main"> 

	<section class="tab-wraps">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="tab-heads">
						<ul class="nav nav-tabs" role="tablist">

							<li role="presentation" class="active">
								<a href="#basic-information" aria-controls="basic-information" role="tab" data-toggle="tab">
									<i class="fa fa-user"></i>Basic Information 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#job-preference" aria-controls="job-preference" role="tab" data-toggle="tab">
									<i class="fa fa-suitcase"></i>Job-Preference 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#education" aria-controls="education" role="tab" data-toggle="tab">
									<i class="fa fa-graduation-cap"></i>Education 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#experience" aria-controls="experience" role="tab" data-toggle="tab">
									<i class="fa fa-building"></i>Experience 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#training" aria-controls="training" role="tab" data-toggle="tab">
									<i class="fa fa-tasks"></i>Training 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#applied-jobs" aria-controls="training" role="tab" data-toggle="tab">
									<i class="fa fa-tasks"></i>Applied Jobs
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#watchlist-jobs" aria-controls="training" role="tab" data-toggle="tab">
									<i class="fa fa-tasks"></i>WatchList Jobs
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#subscribe-premium" aria-controls="training" role="tab" data-toggle="tab">
									<i class="fa fa-check-circle"></i>Subscribe to Premium
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							@if($jobseeker->premium == 1)
							<li role="presentation">
								<a href="#skill-assessments" aria-controls="training" role="tab" data-toggle="tab">
									<i class="fa fa-edit"></i>Skill Assessment
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							@endif
						</ul>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="tab-body">
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="basic-information">
								<form method="POST" action="{{ route('job-seeker.update-basic-info') }}" class="resum-form" enctype="multipart/form-data">
									@csrf
									<div class="row">
										<div class="col-md-12 col-sm-12">
											<h2>
												{{ $jobseeker->name }}
												@if($jobseeker->premium == 1)
													<i style="color: #1b8af3;" class="fa fa-check-circle"></i>
												@endif
											</h2>
											<a href="{{ route('job-seeker.create-resume') }}">Create Resume</a>
											<hr>
										</div>
										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-user"></i> Your Name *</label>
											<input name="name" type="text" placeholder="Your full name" required value="{{ old('name') ? old('name') : $jobseeker->name }}">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-envelope"></i> Your Email *</label>
											<input type="email" name="email" required value="{{ old('email') ? old('email') : $jobseeker->email }}" disabled readonly placeholder="eg: hello@example.com">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-phone"></i> Mobile Number *</label>
											<input type="text" name="phone" required value="{{ old('phone') ? old('phone') : $jobseeker->phone }}" placeholder="eg: 9876543210" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-calendar"></i> Date of Birth *</label>
											<input type="text" name="dob" value="{{ old('dob') ? old('dob') : $jobseeker->dob }}" data-provide="datepicker" data-date-autoclose="true" data-date-format="yyyy-mm-dd">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-map-marker"></i> Current Address</label>
											<input type="text" name="current_address" required value="{{ old('current_address') ? old('current_address') : $jobseeker->current_address }}" placeholder="eg: Kathmandu, Nepal">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-map-marker"></i> Permanent Address</label>
											<input type="text" name="permanent_address" required value="{{ old('permanent_address') ? old('permanent_address') : $jobseeker->permanent_address }}" placeholder="eg: Kathmandu, Nepal">
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-globe"></i> Nationality *</label>
											<select class="ui dropdown" name="nationality" required>
												<option selected="" value="">Choose...</option>
												@php
												$nationalities = DB::table('nationalities')->get();
												@endphp
												@foreach($nationalities as $nation)
												<option value="{{ $nation->Nationality }}" {{ $jobseeker->nationality == $nation->Nationality ? 'selected' : (old('nationality') == $nation->Nationality ? 'selected' : '') }}>{{ $nation->Nationality }}</option>
												@endforeach
											</select>
										</div>

										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-location-arrow"></i> Religion </label>
											<select class="ui dropdown" name="religion">
												<option selected="" value="">Choose...</option>

												<option value="Hinduism" {{ $jobseeker->religion == 'Hinduism' ? 'selected' : (old('religion') == 'Hinduism' ? 'selected' : '') }}>Hinduism</option>

												<option value="Buddism" {{ $jobseeker->religion == 'Buddism' ? 'selected' : (old('religion') == 'Buddism' ? 'selected' : '') }}>Buddism</option>

												<option value="Christianity" {{ $jobseeker->religion == 'Christianity' ? 'selected' : (old('religion') == 'Christianity' ? 'selected' : '') }}>Christianity</option>

												<option value="Jainism" {{ $jobseeker->religion == 'Jainism' ? 'selected' : (old('religion') == 'Jainism' ? 'selected' : '') }}>Jainism</option>

												<option value="NonReligious" {{ $jobseeker->religion == 'NonReligious' ? 'selected' : (old('religion') == 'NonReligious' ? 'selected' : '') }}>NonReligious</option>

												<option value="Sikhism" {{ $jobseeker->religion == 'Sikhism' ? 'selected' : (old('religion') == 'Sikhism' ? 'selected' : '') }}>Sikhism</option>

											</select>
										</div>
										<div class="col-md-6 col-sm-6">
											<label><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender </label>
											<select class="ui dropdown" name="gender" required>
												<option selected="" value="">Choose...</option>
												<option value="1" {{ $jobseeker->gender == 1 ? 'selected' : (old('gender') == 1 ? 'selected' : '') }}>Male</option>
												<option value="2" {{ $jobseeker->gender == 2 ? 'selected' : (old('gender') == 2 ? 'selected' : '') }}>Female</option>
												<option value="3" {{ $jobseeker->gender == 3 ? 'selected' : (old('gender') == 3 ? 'selected' : '') }}>Others</option>
											</select>
										</div>
										<div class="col-md-6 col-sm-6">
											<label>Maritial Status </label>
											<select class="ui dropdown" name="maritial_status">
												<option selected="" value="">Choose...</option>

												<option value="Married" {{ $jobseeker->maritial_status == 'Married' ? 'selected' : (old('maritial_status') == 'Married' ? 'selected' : '') }}>Married</option>

												<option value="Unmarried" {{ $jobseeker->maritial_status == 'Unmarried' ? 'selected' : (old('maritial_status') == 'Unmarried' ? 'selected' : '') }}>Unmarried</option>
											</select>
										</div>


										<div class="col-md-6 col-sm-6">
											<label>Upload Your Photo</label>
											<div class="upload-box">
												<div class="hold">
													<a style="padding-left: 5px;" href="javascript:void(0)">Max file size 2MB(.jpg,.jpeg,.png)</a>
													<span class="btn-file"> Browse File
														<input type="file" name="image" accept=".jpg,.jpeg,.png" {{ $jobseeker->image == '' ? 'required' : '' }} >
													</span>
												</div>
											</div>
										</div>


										<div class="col-md-12">

											<div class="btn-col">
												<input type="submit" value="Save">
											</div>

										</div>

									</div>

								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="job-preference">
								<form action="" class="resum-form">
									<div class="row">
										
                                		<div class="col-md-6">
                                			<label>
                        		                <i class="fa fa-building"></i> &nbsp; Looking For*
                        		            </label>
                            		    	@php
                            		    		$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
                            		    	@endphp
                            		        <select class="ui dropdown" name="looking_for" required>

                            		        	<option selected disabled>Select Job Level</option>

                            		        	@foreach($lookingForArray as $key => $look)
	                                            <option {{ @$preference->looking_for == $key ? 'selected' : '' }} value='{{ $key }}'>{{ $lookingForArray[$key] }}</option>
	                                            @endforeach
	                                            

	                                        </select>
                                		</div>
                                		<div class="col-md-6">
                            		        <label><i class="fa fa-building"></i> &nbsp; Employment Type*  </label>

                            		        @if(isset($preference->employment_type))
                                		        @php
                                		        	$dbEmploymentTypes = json_decode($preference->employment_type);
                                		        @endphp
                                		    @else
                                		    	@php
                                		    		$dbEmploymentTypes = array();
                                		    	@endphp
                            		        @endif

                            		        @php
                            		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer')
                            		        @endphp
                            		        <select id="employmentType" class="ui fluid dropdown multi-select" multiple="multiple" name="employment_type[]" required>

	                                            @foreach($employmentTypeArray as $key => $emptype)
	                                            <option value="{{$key}}" {{ in_array($key, $dbEmploymentTypes)  ? 'selected' : '' }}>{{ $employmentTypeArray[$key] }}</option>

	                                            @endforeach
	                                        </select>
                                		    
                                		</div>
                                		<div class="clearfix"></div>

                                		<div class="col-md-6">
                                			<label style="width: 100%"><i class="fa fa-tasks"></i> &nbsp; Expected Salary*</label>
                                		    
                            		    	<input style="width: 50%" type="text" class="form-control" name="expected_salary" value="{{ @$preference->expected_salary }}" placeholder="eg: 25000" required>

                            		    	@php
                            		    		$salPeriod = array('1' => 'Daily', '2' => 'Weekly', '3' => 'Monthly' , '4' => 'Yearly');
                            		    	@endphp
                            		        <select style="width: 50%" class="ui dropdown" name="expected_salary_period" required>
                            		        	@foreach($lookingForArray as $key => $look)

	                                            <option {{ @$preference->expected_salary_period == $key ? 'selected' : '' }} value='{{ $key }}'>{{ $salPeriod[$key] }}</option>

	                                            @endforeach
	                                        </select>
                                		</div>

                                		<div class="col-md-6">
                                			<label>
                        		                <i class="fa fa-building"></i> &nbsp; Location Preference*
                        		            </label>
                                		    
                            		    	@php
                            		    		$locationsDB = \App\Location::where('display',1)->orderBy('order_item')->get();
                            		    	@endphp
                            		        <select class="ui dropdown" name="location_id" required>

                            		        	<option selected disabled>Select Locaiton</option>

	                                            @foreach($locationsDB as $loc)
	                                            	<option {{ $loc->id == @$preference->location_id ? 'selected' : '' }} value="{{ $loc->id }}">{{ $loc->title }}</option>
	                                            @endforeach
	                                            

	                                        </select>
                                		</div>
                                		@php
											$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

		                                @endphp
                                		<div class="col-md-12">
                                			<div class="panel panel-info">	
                                				<div class="panel-heading">
                                					Job Preferences
                                				</div>
                                				<div  id="category_skills_field">
                                				@if(isset($preference->category_skills))

                                				<input type="hidden" id="categorySkillCount" value="{{$preference->category_skills->count()}}">
                                				@foreach($preference->category_skills as $key => $catSkill)

                                				<input type="hidden" name="category_skill_id[{{ $key+1 }}]" value="{{ $catSkill->id }}">
                                				<div class="panel-body mb-3">
	                                				<div class="row">
	                                					<div class="col-md-5">
	                                						<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
				                        		            </label>
			                                		        <select class="ui dropdown select_job_category" name="job_category_id_db[{{$key+1}}]" required id="{{$key+1}}">

			                                		        	<option selected disabled>Select Job Category</option>

			                                		        	@foreach($jobCategories as $jobCat)
					                                            	<option data-cat-skill-id="{{$catSkill->id }}"  {{ $jobCat->id == $catSkill->job_category_id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
					                                            @endforeach
					                                        </select>

	                                					</div>

	                                					<div class="col-md-6">
	                                						<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Skills*
				                        		            </label>
				                                		    <div class="mb-3" id="skillSelect{{ $key+1 }}">
				                                		        <!-- select skills field -->
				                                		    </div>
				                                		    
	                                					</div>
	                                					@if($preference->category_skills->count() > 1)
				                                		<div class="col-md-1 text-right">
				                                			<a href="#deleteModal"
				                                			   data-toggle="modal"
				                                			   data-id="{{ $catSkill->id }}"
				                                			   id="delete_preference{{ $catSkill->id }}"
				                                			   class="btn btn-sm btn-danger delete"
				                                			   onclick="delete_preference('{{ base64_encode($catSkill->id) }}')">
				                                			   <i class=" fa fa-trash"></i>
				                                			</a>
				                                		</div>	
				                                		@endif
	                                				</div>
                                				</div>
                                				<hr>
                                				@endforeach
                                				@else
                                				<input type="hidden" id="categorySkillCount" value="0">
                                				<div class="panel-body pb-0">
	                                				<div class="row">
	                                					<div class="col-md-5">
	                                						<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
				                        		            </label>
				                                		    
			                                		        <select class="ui dropdown select_job_category" name="job_category_id[1]" required id="1">

			                                		        	<option selected disabled>Select Job Category</option>

			                                		        	@foreach($jobCategories as $jobCat)
					                                            	<option {{ @$preference->job_category_id == $jobCat->id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
					                                            @endforeach
					                                        </select>

	                                					</div>

	                                					<div class="col-md-5">
	                                						<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Skills*
				                        		            </label>
				                                		    <div class="mb-3" id="skillSelect1">
				                                		        <select class="ui dropdown" required>

				                                		        	<option selected disabled>Select Job Category First</option>
						                                        </select>
				                                		    </div>
				                                		    
	                                					</div>
	                                				</div>
                                				</div>
                                				<hr>
                                				@endif
                                				</div>
                                				<div class="panel-footer text-center">
                                					<button type="button" class="btn btn-primary btn-outline-primary" id="addCategorySkills">Add Another Job Preference Field</button>
                                				</div>
                                			</div>
                                		</div>

                                		<div class="col-md-12">
                                			<label>
                        		                <i class="fa fa-building"></i> &nbsp; Career Objectives*
                        		            </label>
                        		            <textarea name="career_objective" id="editor1" class="form-control ckeditor">{{ @$preference->career_objective }}</textarea>
                        		            <hr>
                                		</div>
									</div>
								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="education">
								<form method="POST" action="{{ route('job-seeker.update-education') }}" class="resum-form">
									@csrf
									<input type="hidden" id="educationCount" value="<?=$educations->count()+1;?>">
									<div id="educations_field">
										@if($educations->count() == 0)
										<div class="panel panel-info" id="education1">
											<div class="panel-body">
												<div class="row school-experience">
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
			                                		    <input type="checkbox" name="student_here[]" value="1" class="student_here_checkbox" id="1">
			                                		</div>

			                                		<div class="col-md-6">
			                                			<label style="width: 100%">
			                        		                <i class="fa fa-graduation-cap"></i> &nbsp; 
			                        		                <span id="YearGradStartId1">Graduation Year</span>
			                        		            </label>

			                                		    <select class="ui dropdown" name="year[]" style="width: 50%">
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}">{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        @php
				                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
				                                        @endphp
				                                        <select class="ui dropdown" name="month[]" style="width: 50%">
		                                		        	<option value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}">{{$month}}</option>
				                                            @endforeach
				                                        </select>
			                                		</div>

			                                		<div class="col-md-6" id="MarksSecuredIn1">
			                                			<label style="width: 100%"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</label>
			                                		    <div class="input-group mb-3">
			                                		        <select class="ui dropdown marks_secured1" name="marks_unit[]" style="width: 50%;" required>
					                                            <option value="1">Percentage</option>
					                                            <option value="2">CGPA</option>
					                                        </select>
					                                        <input type="text" class="marks_secured1" name="marks[]" placeholder="Marks Secured" style="width: 50%;" required>
			                                		    </div>
			                                		</div>
												</div>
											</div>
										</div>
										@else
											@foreach($educations as $i => $education)
			                    			<input type="hidden" name="education_id[]" value="{{ $education->id }}">
											<div class="panel panel-info" id="education{{ $i }}">
												<div class="panel-body">
													<div class="row school-experience">
														@if($educations->count() > 1)
														<div class="col-md-12 text-right">
															<a href="#deleteModal"
															   data-toggle="modal"
															   data-id="{{ $education->id }}"
															   id="delete_education{{ $education->id }}"
															   class="btn btn-sm btn-danger delete"
															   onclick="delete_education('{{ base64_encode($education->id) }}')">
															   <i class=" fa fa-trash"></i>
															</a>
														</div>
														@endif
														<div class="col-md-6">
			                                		        <label><i class="fa fa-graduation-cap"></i> &nbsp; Degree* </label>
			                                		        <input type="text" name="degree_db[]" required value="{{ $education->degree }}" placeholder="eg: Intermediate, Bachelors">
				                                		    
				                                		</div>
				                                		<div class="col-md-6">
				                                		    
				                            		        <label><i class="fa fa-graduation-cap"></i> &nbsp; Education Program*</label>

				                            		        <input type="text" name="program_db[]" required value="{{ $education->program }}" placeholder="eg: MBS, BCA, BBA">
				                                		</div>
				                                		<div class="col-md-6">
				                                		    
				                    		                <label><i class="fa fa-graduation-cap"></i> &nbsp; Education Board*</label>
				                    		            
				                    		        		<input type="text" name="board_db[]" required value="{{ $education->board }}" placeholder="eg: TU, KU, PoU...">
				                                		</div>
				                                		<div class="col-md-6">
				                            		        <label><i class="fa fa-graduation-cap"></i> &nbsp; Name of Institute*</label>
				                            		            
				                            		        <input type="text" name="institute_db[]" required value="{{ $education->institute }}" placeholder="eg: KTM RUSH Academics..">

				                                		</div>
				                                		<div class="col-md-6">
				                                			<label><i class="fa fa-graduation-cap"></i> &nbsp;Currently Studying Here</label>
				                                		    <input type="checkbox" name="student_here_db[]" value="1" class="student_here_checkbox" id="{{$i}}" {{ $education->student_here == 1 ? 'checked' : ''}}>
				                                		</div>

				                                		<div class="col-md-6">
				                                			<label style="width: 100%">
				                        		                <i class="fa fa-graduation-cap"></i> &nbsp; 
				                        		                <span id="YearGradStartId{{$i}}">
				                        		                	{{ $education->student_here == 1 ? 'Started Year' : 'Graduation Year' }}
				                        		                </span>
				                        		            </label>

				                                		    <select class="ui dropdown" name="year_db[]" style="width: 50%">
			                                		        	<option value="">Year</option>
			                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
					                                            <option value="{{$year}}" {{ $education->year == $year ? 'selected' : '' }}>{{$year}}</option>
					                                            @endfor
					                                        </select>
					                                        @php
					                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
					                                        @endphp
					                                        <select class="ui dropdown" name="month_db[]" style="width: 50%">
			                                		        	<option value="">Month</option>

			                                		        	@foreach($months as $key => $month)
					                                            <option value="{{$key}}" {{ $education->month == $key ? 'selected' : '' }}>{{$month}}</option>
					                                            @endforeach
					                                        </select>
				                                		</div>

				                                		<div class="col-md-6" id="MarksSecuredIn{{$i}}">
				                                			<label style="width: 100%"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</label>
				                                		    <select class="ui dropdown marks_secured{{$i}}" name="marks_unit_db[]" style="width: 50%;" required>
					                                            <option value="1" {{ $education->marks_unit == 1 ? 'selected' : '' }}>Percentage</option>
			                                            		<option value="2" {{ $education->marks_unit == 2 ? 'selected' : '' }}>CGPA</option>
					                                        </select>
					                                        <input type="text" class="marks_secured{{$i}}" name="marks_db[]" placeholder="Marks Secured" value="{{ $education->marks }}" style="width: 50%;" required>
				                                		</div>
													</div>
												</div>
											</div>
											@endforeach
			                    		@endif
									</div>

									<div class="add-here">

									</div>
									<div class="row">
										<div class="col-md-6">
											
											<div class="btn-col">
												<input type="button" id="addEducationField" value="Add More">
											</div>

										</div>

										<div class="col-md-6 text-right">

											<div class="btn-col">
												<input type="submit" value="Update Information">
											</div>

										</div>
									</div>
								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="experience">
								<form method="POST" action="{{ route('job-seeker.update-experience') }}" class="resum-form">
									@csrf
									<input type="hidden" id="experienceCount" value="<?=$experiences->count()+1;?>">
									<div id="experiences_field">
										@php
											$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

		                                	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
		                                @endphp
										@if($experiences->count() == 0)
										<div class="panel panel-info" id="experience1">
											<div class="panel-body">
												<div class="row school-experience">
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

		                                		        	<option selected disabled>Select Job Category</option>
		                                		        	@foreach($jobCategories as $jobCat)
				                                            <option value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
				                                            @endforeach
				                                        </select>
		                                		    
			                                		</div>
			                                		<div class="clearfix" style="padding-bottom: 0px;"></div>

			                                		<div class="col-md-6">
			                                			<label><i class="fa fa-building"></i> &nbsp;Currently Working Here</label>
			                                		    <input type="checkbox" name="working_here[]" value="1" class="working_here_checkbox" id="1">
			                                		</div>

			                                		<div class="col-md-6">
			                                			<label style="width: 100%;">
			                        		                <i class="fa fa-building"></i> &nbsp; Start Date*
			                        		            </label>
			                                		    
		                                		        <select class="ui dropdown" name="start_year[]" required style="width: 50%">
		                                		        	<option disabled selected>Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}">{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        <select class="ui dropdown" name="start_month[]" required style="width: 50%">
		                                		        	<option disabled selected>Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}">{{$month}}</option>
				                                            @endforeach
				                                        </select>
		                                		    
			                                		</div>

			                                		<div class="col-md-6" id="EndDate1">
			                                			
			                                			<label style="width: 100%;">
			                        		                <i class="fa fa-building"></i> &nbsp; End Date*
			                        		            </label>
			                                		    
			                                		    
		                                		        <select class=" end_date1" name="end_year[]" required style="width: 50%">
		                                		        	<option disabled selected>Year</option>

		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            	<option value="{{$year}}">{{$year}}</option>
				                                            @endfor
				                                        </select>

				                                        <select class=" end_date1" name="end_month[]" required style="width: 50%">
		                                		        	<option disabled selected>Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            	<option value="{{$key}}">{{$month}}</option>
				                                            @endforeach
				                                        </select>
		                                		    
			                                		</div>

			                                		<div class="col-md-12">
			                                			<label>
			                        		                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
			                        		            </label>
			                        		            <textarea name="duties_responsibilities[]"></textarea>
			                                		</div>

			                                	</div>
											</div>
										</div>
										@else
											@foreach($experiences as $i => $experience)
			                    			<input type="hidden" name="experience_id[]" value="{{ $experience->id }}">
											<div class="panel panel-info" id="experience{{ $i }}">
												<div class="panel-body">
													@if($experiences->count() > 1)
													<div class="col-md-12 pull-right">
														<a href="#deleteModal"
														   data-toggle="modal"
														   data-id="{{ $experience->id }}"
														   id="delete_experience{{ $experience->id }}"
														   class="btn btn-sm btn-danger pull-right"
														   onclick="delete_experience('{{ base64_encode($experience->id) }}')">
														   <i class=" fa fa-trash"></i>
														</a>
													</div>
													@endif
													<div class="row school-experience">
				                                		<div class="col-md-6">
			                                		        <label><i class="fa fa-building"></i> &nbsp; Organization name*  </label>
			                                		        <input type="text" name="organization_name_db[]" required value="{{ $experience->organization_name }}" placeholder="eg: Laxmi Bank Ltd.">
				                                		    
				                                		</div>
				                                		<div class="col-md-6">
				                                		    
				                            		        <label><i class="fa fa-building"></i> &nbsp; Job Location*</label>

				                            		        <input type="text" name="job_location_db[]" required value="{{ $experience->job_location }}" placeholder="eg: Kathmandu">
				                                		</div>
				                                		<div class="col-md-6">
				                                		    
				                    		                <label><i class="fa fa-building"></i> &nbsp; Job Title*</label>
				                    		            
				                    		        		<input type="text" name="job_title_db[]" required value="{{ $experience->job_title }}" placeholder="eg: Branch Manager...">
				                                		</div>
				                                		<div class="col-md-6">
				                                			<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
				                        		            </label>
				                                		    
			                                		        <select class="ui dropdown" name="job_category_id_db[]" required>

			                                		        	<option selected disabled>Select Job Category</option>
			                                		        	@foreach($jobCategories as $jobCat)
					                                            <option {{ $experience->job_category_id == $jobCat->id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
					                                            @endforeach
					                                        </select>
				                                		</div>
				                                		<div class="clearfix" style="padding-bottom: 0px;"></div>
				                                		<div class="col-md-6">
				                                			<label><i class="fa fa-building"></i> &nbsp;Currently Working Here</label>
				                                		    <input type="checkbox" name="working_here_db[]" value="1" class="working_here_checkbox" id="{{$i}}" {{ $experience->working_here == 1 ? 'checked' : ''}}>
				                                		</div>

				                                		

				                                		<div class="col-md-6">
				                                			<label style="width: 100%">
				                        		                <i class="fa fa-building"></i> &nbsp; Start Date*
				                        		            </label>
				                                		    
			                                		        <select class="ui dropdown" name="start_year_db[]" required style="width: 50%">
			                                		        	<option value="">Year</option>
			                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
					                                            <option value="{{$year}}" {{ $experience->start_year == $year ? 'selected' : '' }}>{{$year}}</option>
					                                            @endfor
					                                        </select>
					                                        <select class="ui dropdown" name="start_month_db[]" required style="width: 50%">
			                                		        	<option value="">Month</option>

			                                		        	@foreach($months as $key => $month)
					                                            <option value="{{$key}}" {{ $experience->start_month == $key ? 'selected' : '' }}>{{$month}}</option>
					                                            @endforeach
					                                        </select>
				                                		</div>

				                                		<div class="col-md-6" id="EndDate{{$i}}">
				                                			<label style="width: 100%">
				                        		                <i class="fa fa-building"></i> &nbsp; End Date*
				                        		            </label>
				                                		    
			                                		        <select class="ui dropdown end_date{{$i}}" name="end_year_db[]" required="" style="width: 50%">
			                                		        	<option value="">Year</option>
			                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
					                                            <option value="{{$year}}" {{ $experience->end_year == $year ? 'selected' : '' }}>{{$year}}</option>
					                                            @endfor
					                                        </select>
					                                        <select class="ui dropdown end_date{{$i}}" name="end_month_db[]" required="" style="width: 50%">
			                                		        	<option value="">Month</option>

			                                		        	@foreach($months as $key => $month)
					                                            <option value="{{$key}}" {{ $experience->end_month == $key ? 'selected' : '' }}>{{$month}}</option>
					                                            @endforeach
					                                        </select>
			                                		    
				                                		</div>

				                                		<div class="col-md-12">
				                                			<label>
				                        		                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
				                        		            </label>
				                        		            <textarea name="duties_responsibilities_db[]">{{ $experience->duties_responsibilities }}</textarea>
				                                		</div>

				                                	</div>
												</div>
											</div>
											@endforeach
			                    		@endif
									</div>

									<div class="add-here">

									</div>
									<div class="row">
										<div class="col-md-6">
											
											<div class="btn-col">
												<input type="button" id="addExperienceField" value="Add More">
											</div>

										</div>

										<div class="col-md-6 text-right">

											<div class="btn-col">
												<input type="submit" value="Update Information">
											</div>

										</div>
									</div>
								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="training">
								<form method="POST" action="{{ route('job-seeker.update-training') }}" class="resum-form">
									@csrf
									<input type="hidden" id="trainingCount" value="<?=$trainings->count()+1;?>">
									<div id="trainings_field">
										@if($trainings->count() == 0)
										<div class="panel panel-info" id="training1">
											<div class="panel-body">
												<div class="row school-experience">
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
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}">{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        @php
				                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
				                                        @endphp
				                                        <select class="ui dropdown" style="width: 50%;" name="month[]">
		                                		        	<option value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}">{{$month}}</option>
				                                            @endforeach
				                                        </select>
			                                		</div>

			                                	</div>
											</div>
										</div>
										@else
											@foreach($trainings as $i => $training)
			                    			<input type="hidden" name="training_id[]" value="{{ $training->id }}">
											<div class="panel panel-info" id="training{{ $i }}">
												<div class="panel-body">
													<div class="row">
				                                		@if($trainings->count() > 1)
				                                		<div class="col-md-12 text-right">
				                                			<a href="#deleteModal"
				                                			   data-toggle="modal"
				                                			   data-id="{{ $training->id }}"
				                                			   id="delete_training{{ $training->id }}"
				                                			   class="btn btn-sm btn-danger delete"
				                                			   onclick="delete_training('{{ base64_encode($training->id) }}')">
				                                			   <i class=" fa fa-trash"></i>
				                                			</a>
				                                		</div>
				                                		@endif
				                                		<div class="col-md-6">
				                                		        <label><i class="fa fa-tasks"></i> &nbsp; Name of the Training* </label>
				                                		        <input type="text" name="name_db[]" required value="{{ $training->name }}" placeholder="eg: Training of Branch Management">
				                                		        <hr>
				                                		    
				                                		</div>
				                                		<div class="col-md-6">
				                            		        <label><i class="fa fa-tasks"></i> &nbsp; Name of Institution*</label>
				                            		            
				                            		        <input type="text" name="institution_db[]" required value="{{ $training->institution }}" placeholder="eg: Creators Institute of Business and Technology">

				                            		        <hr>
				                                		</div>

				                                		<div class="col-md-6">
				                                			<label style="width: 100%;"><i class="fa fa-tasks"></i> &nbsp; Duration*</label>
			                                		    	<input style="width: 50%;" type="number" min="0" name="duration_db[]" value="{{ $training->duration }}" required>

			                                		        <select style="width: 50%;" class="ui dropdown" name="duration_unit_db[]" required>
					                                            <option value="1" {{ $training->duration_unit == 1 ? 'selected' : '' }}>Day</option>

					                                            <option value="2" {{ $training->duration_unit == 2 ? 'selected' : '' }}>Week</option>

					                                            <option value="3" {{ $training->duration_unit == 3 ? 'selected' : '' }}>Month</option>

					                                            <option value="4" {{ $training->duration_unit == 4 ? 'selected' : '' }}>Year</option>

					                                        </select>
				                                		</div>

				                                		<div class="col-md-6">
				                                			<label style="width:100%;">
				                        		                <i class="fa fa-tasks"></i> &nbsp;Completion Year*
				                        		            </label>
			                                		        <select style="width: 50%;" class="ui dropdown" name="year_db[]">
			                                		        	<option value="">Year</option>
			                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
					                                            <option value="{{$year}}" {{ $training->year == $year ? 'selected' : '' }}>{{$year}}</option>
					                                            @endfor
					                                        </select>
					                                        @php
					                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
					                                        @endphp
					                                        <select class="ui dropdown" style="width: 50%;" name="month_db[]">
			                                		        	<option disabled value="">Month</option>

			                                		        	@foreach($months as $key => $month)
					                                            <option value="{{$key}}" {{ $training->month == $key ? 'selected' : '' }}>{{$month}}</option>
					                                            @endforeach
					                                        </select>
				                                		</div>

				                                	</div>
												</div>
											</div>
											@endforeach
			                    		@endif
									</div>

									<div class="add-here">

									</div>
									<div class="row">
										<div class="col-md-6">
											
											<div class="btn-col">
												<input type="button" id="addTrainingField" value="Add More">
											</div>

										</div>

										<div class="col-md-6 text-right">

											<div class="btn-col">
												<input type="submit" value="Update Information">
											</div>

										</div>
									</div>

								</form>
							</div>
							<div role="tabpanel" class="tab-pane" id="applied-jobs">
								<table class="table table-responsive">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Job Title</th>
											<th>Company/Sole Trader</th>
											<th>Applied Date</th>
											<th>Status</th>
											<th>Remarks</th>
										</tr>
									</thead>
									<tbody>
										@if($applied_jobs->count() > 0)
											@foreach($applied_jobs as $key => $appJobs)
											<tr>
												<td>{{$key+1}}.</td>
												<td>
													<a href="{{ route('job-details',['slug' => $appJobs->job_details->slug]) }}">
														{{ $appJobs->job_details->job_title }}
													</a>
												</td>
												<td>
													<a href="{{ route('company_jobs',['slug' => $appJobs->employer->employer_info->slug]) }}">
														{{ $appJobs->employer->employer_info->organization_name }}
													</a>
												</td>
												<td>{{ date('jS F, Y',strtotime($appJobs->created_at)) }}</td>
												<td>
													@if($appJobs->status == 0)
													<button class="btn btn-xs btn-primary">Pending</button>
													@elseif($appJobs->status == 1)
													<button class="btn btn-xs btn-success">Approved</button>
													@elseif($appJobs->status == 2)
													<button class="btn btn-xs btn-danger">Declined</button>
													@endif
												</td>

												<td>
													@if($appJobs->status == 1)
													<a href="#viewContactDetails" 
														data-toggle="modal"
														data-id="{{ $appJobs->job_details->id }} "
														id="view{{ $appJobs->job_details->id }}" 
														data-contact_email = '{{ $appJobs->job_details->contact_email }}'
														data-contact_phone = '{{ $appJobs->job_details->contact_phone }}'
														data-messenger_link = '{{ $appJobs->job_details->messenger_link }}'
														data-viber_number = '{{ $appJobs->job_details->viber_number }}'
														data-whatsapp_number = '{{ $appJobs->job_details->whatsapp_number }}'
														onclick="view_contact_details('{{ $appJobs->job_details->id }}','{{ addslashes($appJobs->job_details->job_title) }}')"
														class="btn btn-xs btn-default pull-right">
														View Contact Details
													</a>
													@endif
												</td>
											</tr>
											@endforeach
										@else
											<tr>
												<td class="text-center" col="6">You haven't applied to any jobs yet.</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
							<div role="tabpanel" class="tab-pane" id="watchlist-jobs">
								<table class="table table-responsive">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Job Title</th>
											<th>Company/Sole Trader</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($watchlist_jobs as $key => $watchlist)
										<tr>
											<td>{{$key+1}}.</td>
											<td>
												<a href="{{ route('job-details',['slug' => $watchlist->job_details->slug]) }}">
													{{ $watchlist->job_details->job_title }}
												</a>
											</td>
											<td>
												<a href="{{ route('company_jobs',['slug' => $watchlist->job_details->user->employer_info->slug]) }}">
													{{ $watchlist->job_details->user->employer_info->organization_name }}
												</a>
											</td>
											<td>
												<a href="#deleteWatchListJob"
												   data-toggle="modal"
	                                			   data-id="{{ $watchlist->id }}"
	                                			   id="delete_watchlist_job{{ $watchlist->id }}"
	                                			   onclick="delete_watchlist_job('{{ base64_encode($watchlist->id) }}')"
													 class="btn btn-xs btn-danger">
													<i  class="fa fa-trash"></i>
												</a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>

							<div role="tabpanel" class="tab-pane" id="subscribe-premium">
								@if($jobseeker->premium != 1)
								<div class="box">
								
									<div class="text-col">
										<h2>
											<a href="javascript:void(0)">Subscribe to Premium Listings for Enhanced Visibility!</a>
										</h2>
										
									</div>
									<div class="clearfix">
										<h4>Overview</h4>
										<p>
											Unlock unparalleled opportunities for your internship listings by subscribing to our Premium Listings package.
											Elevate your company's visibility and attract top-tier talent with enhanced features tailored to meet your
											recruitment needs.
										</p>

										<h3>Subscription Benefits:</h3>

										<p><strong>1. Prime Placement:</strong><br>
											Featured at the top of relevant internship searches, ensuring maximum visibility among potential interns.</p>

										<p><strong>2. Increased Exposure:</strong><br>
											Stand out in the crowded internship landscape and capture the attention of our diverse user base.</p>

										<p><strong>3. Comprehensive Analytics:</strong><br>
											Gain insights into the performance of your listings with detailed analytics, helping you refine your recruitment
											strategy.</p>

										<p><strong>4. Priority Support:</strong><br>
											Enjoy dedicated support from our team to address any queries and optimize your premium listing for optimal
											results.</p>

											<p><strong>Subscription Fee: $69.99</strong></p>

										<a class="btn btn-primary" href="{{ route('job-seeker.paywithpaypal') }}" onclick="subscribe()">Subscribe Now</a>

										<script>
											function subscribe() {
												// Add subscription logic here
												alert('Are you Sure?');
											}
										</script>
									</div>
								</div>
								@else
								<div class="box">
								
									<div class="text-col">
										<h2>
											<a href="javascript:void(0)">You have already subscribed to premium Account!</a>
										</h2>
										
									</div>
								</div>
								@endif
							</div>
							@if($jobseeker->premium == 1)
							<div role="tabpanel" class="tab-pane" id="skill-assessments">
								
								<div class="box">
								
									<div class="text-col">
										<h2>
											<a href="javascript:void(0)">Subscribe to Premium Listings for Enhanced Visibility!</a>
										</h2>
										
									</div>
									<div class="clearfix">
										
											<?php

											foreach ($skill_assessments as $item):
											?>
											

												<div class="panel panel-primary">
													<div class="panel-body">
														<a href="{{ route('skill-assessment', ['slug' => $item->slug]) }}"><b>{{ $item->title }}</b></a>
													</div>
												</div>
									
											<?php
											endforeach; ?>
										
									</div>
								</div>
								
							</div>
							@endif
							<!-- end tab content -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<div class="modal fade modal-info" id="deleteModal">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete <span id="deleteTitle"></span>?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <p>Are you Sure...??</p>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                <a href="" class="btn btn-round btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-info" id="deleteWatchListJob">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove from WatchList?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <p>Are you Sure...??</p>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                <a href="" class="btn btn-round btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="viewContactDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <label><span class="viewJobTitle"></span></label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page">
                    <div class="row">
                        <div class="col-md-6" id="showHideContactEmail">
                            <label class="text-muted"><i class="fa fa-envelope"></i> Email: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewContactEmail"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideContactPhone">
                            <label class="text-muted"><i class="fa fa-phone"></i> Phone: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewContactPhone"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideMessengerLink">
                            <label class="text-muted"><i class="fa fa-comments"></i> Messenger: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewMessengerLink"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideViberNumber">
                            <label class="text-muted"><i class="fa fa-comments"></i> Viber: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewViberNumber"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideWhatsappNumber">
                            <label class="text-muted"><i class="fa fa-comments"></i> WhatsApp: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewWhatsappNumber"></a></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="text-align: right;" type="button" data-dismiss="modal"
                    class="btn btn-outline-danger">Close
                </button>
            </div>
        </div>
    </div>
</div>

<!--MAIN END--> 
@endsection
@push('post-scripts')
<!-- <script src="{{ asset('frontend/js/form.js') }}"></script> -->

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>

<script type="text/javascript">
	function view_contact_details(id,job_title) {
		var email = $('#view' + id).attr('data-contact_email');
		var phone = $('#view' + id).attr('data-contact_phone');
		var messenger_link = $('#view' + id).attr('data-messenger_link');
		var viber_number = $('#view' + id).attr('data-viber_number');
		var whatsapp_number = $('#view' + id).attr('data-whatsapp_number');

		$(".viewJobTitle").html(job_title);

		if (email != '') {
			$("#showHideContactEmail").show();
			$("#viewContactEmail").html(email);
			$("#viewContactEmail").attr('href','mailto:'+email);
		}else{
			$("#showHideContactEmail").hide();
		}

		if (phone != '') {
			$("#showHideContactPhone").show();
			$("#viewContactPhone").html(phone);
			$("#viewContactPhone").attr('href','tel:'+phone.replace(/[^0-9,+]/,''));
		}else{
			$("#showHideContactPhone").hide();
		}

		if (messenger_link != '') {
			$("#showHideMessengerLink").show();
			$("#viewMessengerLink").html(messenger_link);
			$("#viewMessengerLink").attr('href',messenger_link);
		}else{
			$("#showHideMessengerLink").hide();
		}

		if (viber_number != '') {
			$("#showHideViberNumber").show();
			$("#viewViberNumber").html(viber_number);
			$("#viewViberNumber").attr('href','viber://chat?number='+viber_number.replace(/[^0-9,+]/,''))
		}else{
			$("#showHideViberNumber").hide();
		}

		if (whatsapp_number != '') {
			$("#showHideWhatsappNumber").show();
			$("#viewWhatsappNumber").html(whatsapp_number);
			if ($( window ).width() < 769) {
				$("#viewWhatsappNumber").attr('href','https://api.whatsapp.com/send?phone='+whatsapp_number.replace(/[^0-9,+]/,''))
			}else{
				$("#viewWhatsappNumber").attr('href','https://web.whatsapp.com/send?phone='+whatsapp_number.replace(/[^0-9,+]/,''))
			}
			
		}else{
			$("#showHideWhatsappNumber").hide();
		}
	}
</script>

<script>
	$('#multi-select').dropdown();

$('.multi-select').dropdown();
</script>

<script>
        $(document).ready(function(){
            var i=1;
			i = $('#educationCount').val();

            $('#addEducationField').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('jobseeker/education/add-educations') }}/"+i,
                    cache : false,
                    beforeSend : function (){
                    	
                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#educations_field').append($response.responseText);
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove_education', function(){  
                var button_id = $(this).attr("id");   
                $('#education'+button_id).remove();  
            });  

        });

        $(".student_here_checkbox").each(function(){
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);

        	if($(this).is(':checked')){
        		$("#MarksSecuredIn"+checkbox_id).slideUp();
        		$("#YearGradStartId"+checkbox_id).html('Started Year');
        		$(".marks_secured"+checkbox_id).attr('required',false);
        	}else{
        		$("#MarksSecuredIn"+checkbox_id).slideDown();
        		$("#YearGradStartId"+checkbox_id).html('Graduation Year');
        		$(".marks_secured"+checkbox_id).attr('required',true);
        	}
        });

        $(document).on('click', '.student_here_checkbox', function(){
        	
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);
        	if($(this).is(':checked')){
        		$("#MarksSecuredIn"+checkbox_id).slideUp();
        		$("#YearGradStartId"+checkbox_id).html('Started Year');
        		$(".marks_secured"+checkbox_id).attr('required',false);
        	}else{
        		$("#MarksSecuredIn"+checkbox_id).slideDown();
        		$("#YearGradStartId"+checkbox_id).html('Graduation Year');
        		$(".marks_secured"+checkbox_id).attr('required',true);
        	}
        });


        function delete_education(id) {
        	var conn = "{{ url('jobseeker/educations/delete') }}/"+id;
        	$('#deleteTitle').html('Education');
            $('#deleteModal a').attr("href", conn);
        }

        // ==================================================================

        $(document).ready(function(){
            var j=1;
			j = $('#experienceCount').val();

            $('#addExperienceField').click(function(){  
                j++;

                $.ajax({
                	url : "{{ url('jobseeker/experience/add-experiences') }}/"+j,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#experiences_field').append($response.responseText);
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove_experience', function(){  
                var button_id = $(this).attr("id");   
                $('#experience'+button_id).remove();  
            });  

        });

        $(".working_here_checkbox").each(function(){
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);

        	if($(this).is(':checked')){
        		$("#EndDate"+checkbox_id).slideUp();
        		$(".end_date"+checkbox_id).attr('required',false);
        	}else{
        		$("#EndDate"+checkbox_id).slideDown();
        		$(".end_date"+checkbox_id).attr('required',true);
        	}
        })

        $(document).on('click', '.working_here_checkbox', function(){

        	var checkbox_id = $(this).attr("id");

        	if($(this).is(':checked')){
        		$("#EndDate"+checkbox_id).slideUp();
        		$(".end_date"+checkbox_id).attr('required',false);
        	}else{
        		$("#EndDate"+checkbox_id).slideDown();
        		$(".end_date"+checkbox_id).attr('required',true);
        	}
        });

        function delete_experience(id) {
        	var conn = "{{ url('jobseeker/experiences/delete') }}/"+id;
        	$('#deleteTitle').html('Work Experience');
            $('#deleteModal a').attr("href", conn);
        }
        
        // ======================================================================

        $(document).ready(function(){
            var k=1;
			k = $('#trainingCount').val();

            $('#addTrainingField').click(function(){  
                k++;

                $.ajax({
                	url : "{{ url('jobseeker/training/add-trainings') }}/"+k,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#trainings_field').append($response.responseText);
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove_training', function(){  
                var button_id = $(this).attr("id");   
                $('#training'+button_id).remove();  
            });  

        });


        function delete_training(id) {
        	var conn = "{{ url('jobseeker/trainings/delete') }}/"+id;
        	$('#deleteTitle').html('Training');
            $('#deleteModal a').attr("href", conn);
        }
        /*=========================================================================================*/

        $(document).ready(function(){
            var m=1;
			m = $('#categorySkillCount').val();

            $('#addCategorySkills').click(function(){  
                m++;

                $.ajax({
                	url : "{{ url('jobseeker/job-preference/add-job-preferences') }}/"+m,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#category_skills_field').append($response.responseText);
                            show_job_categories_skills();
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove_preference', function(){  
                var button_id = $(this).attr("id");   
                $('#preference'+button_id).remove();  
            });

            function call_ajax_function(select_id, job_category_id, cat_skill_id){
            	$.ajax({
            		url : "{{ url('jobseeker/job-preference/show-job-categories-skills') }}",
                	type : "POST",
                	data : {
                		'_token': '{{ csrf_token() }}',
                		select_id: select_id,
                		job_category_id: job_category_id,
            			cat_skill_id: cat_skill_id
                	},
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#skillSelect'+select_id).html($response.responseText);
                            $('.skills_multiselect').dropdown();
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            }

            show_job_categories_skills();

            function show_job_categories_skills(){
            	$('.select_job_category').change(function(){  
	                var select_id = $(this).attr("id");   
	                var job_category_id = $(this).val();
	                var cat_skill_id = $('#categorySkillCount').val() == 0 ? '' : $(this).find(':selected').data('cat-skill-id');
	                

	                // console.log(job_category_id);

	                call_ajax_function(select_id, job_category_id, cat_skill_id)

	            });  
            }

            $(".select_job_category").each(function(){

            	var select_id = $(this).attr("id");
            	var job_category_id = $(this).val();
            	var cat_skill_id = $(this).find(':selected').data('cat-skill-id');

            	// console.log(cat_skill_id);
            	// return;

            	call_ajax_function(select_id, job_category_id, cat_skill_id)

            })
        });


        function delete_preference(id) {
        	var conn = "{{ url('jobseeker/job-preferences/delete') }}/"+id;
        	$('#deleteTitle').html('Job Preference');
            $('#deleteModal a').attr("href", conn);
        }

        $('.skills_multiselect').dropdown();

        function delete_watchlist_job(id) {
        	var conn = "{{ url('jobseeker/watchlist-jobs/delete') }}/"+id;
            $('#deleteWatchListJob a').attr("href", conn);
        }

    </script>
@endpush