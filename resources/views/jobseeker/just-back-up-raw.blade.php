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

							<li role="presentation">
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
							<li role="presentation" class="active">
								<a href="#education" aria-controls="education" role="tab" data-toggle="tab">
									<i class="fa fa-graduation-cap"></i>Education 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation" >
								<a href="#experience" aria-controls="experience" role="tab" data-toggle="tab">
									<i class="fa fa-book"></i>Experience 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
							<li role="presentation">
								<a href="#contact" aria-controls="contact" role="tab" data-toggle="tab">
									<i class="fa fa-phone"></i>Contact Information 
									<i class="fa fa-chevron-right arros"></i>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="tab-body">
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane" id="basic-information">
								<form method="POST" action="{{ route('job-seeker.update-basic-info') }}" class="resum-form" enctype="multipart/form-data">
									@csrf
									<div class="row">

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
									<h5>Select Your Preffered Categories</h5>
									<select name="skills" multiple="" class="ui fluid dropdown" id="multi-select">
										<option value="">Select Job Categories</option>
										<option value="angular">Angular</option>
										<option value="css">CSS</option>
										<option value="design">Graphic Design</option>
										<option value="ember">Ember</option>
										<option value="html">HTML</option>
										<option value="ia">Information Architecture</option>
										<option value="javascript">Javascript</option>
										<option value="mech">Mechanical Engineering</option>
										<option value="meteor">Meteor</option>
										<option value="node">NodeJS</option>
										<option value="plumbing">Plumbing</option>
										<option value="python">Python</option>
										<option value="rails">Rails</option>
										<option value="react">React</option>
										<option value="repair">Kitchen Repair</option>
										<option value="ruby">Ruby</option>
										<option value="ui">UI Design</option>
										<option value="ux">User Experience</option>
									</select>
									<br>
									<div class="row">
										<div class="col-sm-6">
											<h5>I am Looking For:</h5>
											<select class="ui dropdown">
												<option value="">Select The Leve You are looking for</option>
												<option value="1">Entry Level</option>
												<option value="3">Junior Level</option>
												<option value="3">Mid Level</option>
												<option value="4">Senior Level</option>
											</select>
										</div>
										<div class="col-sm-6">
											<h5>I am Available For:</h5>
											<select name="Work Schedule" multiple="" class="ui fluid dropdown multi-select">
												<option value="1">Full Time</option>
												<option value="3">Part Time</option>
												<option value="3">Freelance</option>
												<option value="4">Intern</option>
											</select>
										</div>
									</div>
									<h5>Select Your Skills</h5>
									<select name="skills" multiple="" class="ui fluid dropdown multi-select">
										<option value="">Select Job Categories</option>
										<option value="angular">Angular</option>
										<option value="css">CSS</option>
										<option value="design">Graphic Design</option>
										<option value="ember">Ember</option>
										<option value="html">HTML</option>
										<option value="ia">Information Architecture</option>
										<option value="javascript">Javascript</option>
										<option value="mech">Mechanical Engineering</option>
										<option value="meteor">Meteor</option>
										<option value="node">NodeJS</option>
										<option value="plumbing">Plumbing</option>
										<option value="python">Python</option>
										<option value="rails">Rails</option>
										<option value="react">React</option>
										<option value="repair">Kitchen Repair</option>
										<option value="ruby">Ruby</option>
										<option value="ui">UI Design</option>
										<option value="ux">User Experience</option>
									</select>
									<br>
									<div class="row">
										<div class="col-sm-6">
											<label for="">Enter Your Expected Salary: (NRs)</label>
											<div>
												<input type="text" placeholder="e.g. “35, 000”">
											</div>
										</div>
										<div class="col-sm-6">
											<label for="">Enter Your Current Salary: (NRs)</label>
											<div>
												<input type="text" placeholder="e.g. “35, 000”">
											</div>
										</div>
									</div>
									<div class="col-md-12">

										<div class="btn-col">
											<input type="submit" value="Preview Your Resume">
										</div>

									</div>
								</form>
							</div>
							<div role="tabpanel" class="tab-pane active" id="education">
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
							<div role="tabpanel" class="tab-pane " id="experience">
								<form method="POST" action="{{ route('job-seeker.update-education') }}" class="resum-form">
									@csrf
									<input type="hidden" id="experienceCount" value="<?=$experiences->count()+1;?>">
									<div id="experiences_field">
										@php
											$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

		                                	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
		                                @endphp
										@if($experiences->count() != 0)
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
														<a href="#deleteExperience"
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
							<div role="tabpanel" class="tab-pane" id="contact">
								<form action="#" class="resum-form ">

									<div class="row">

										<div class="col-md-6 col-sm-6">
											<label>Your Phone Number *</label>
											<input type="text" placeholder="Your Phone number">
										</div>

										<div class="col-md-6 col-sm-6">
											<label>Your Email *</label>
											<input type="text" placeholder="you@jobsalerts.com">
										</div>

										<div class="col-md-6 col-sm-6">
											<label>Whats App Contact *</label>
											<input type="text" placeholder="e.g. “App Developer”">
										</div>

										<div class="col-md-6 col-sm-6">
											<label>Location *</label>
											<input type="text" placeholder="e.g. whats app number”">
										</div>

										<div class="col-md-6 col-sm-6">
											<label>Viber Number</label>
											<input type="text" placeholder="Viber Number">
										</div>

										<div class="col-md-6 col-sm-6">
											<label>Website URL *</label>
											<input type="text" placeholder="e.g. www.yourresume.com">
										</div>

										<div class="col-md-12">

											<div class="btn-col">
												<input type="submit" value="Preview Your Resume">
											</div>

										</div>

									</div>

								</form>
							</div>
							<!-- end tab content -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<!--MAIN END--> 
@endsection
@push('post-scripts')
<!-- <script src="{{ asset('frontend/js/form.js') }}"></script> -->

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>

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

            $(document).on('click', '.btn_remove', function(){  
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
        	console.log($(this).hasClass('checkboxAreaChecked'));

        	if($(this).hasClass('checkboxAreaChecked')){
        		$("#MarksSecuredIn"+checkbox_id).slideUp();
        		$("#YearGradStartId"+checkbox_id).html('Started Year');
        		$(".marks_secured"+checkbox_id).attr('required',false);
        	}else{
        		$("#MarksSecuredIn"+checkbox_id).slideDown();
        		$("#YearGradStartId"+checkbox_id).html('Graduation Year');
        		$(".marks_secured"+checkbox_id).attr('required',true);
        	}
        });

        $(document).on('click', '.student_here_checkbox_new', function(){
        	
        	var checkbox_id = $(this).attr("id");
        	
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
        	var conn = "{{ url('admin/job-seekers/profile/$id/educations/delete') }}/"+id;

            $('#deleteEducation a').attr("href", conn);
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

            $(document).on('click', '.btn_remove', function(){  
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
        	console.log(checkbox_id);
        	console.log($(this).hasClass('checkboxAreaChecked'));

        	if($(this).hasClass('checkboxAreaChecked')){
        		$("#EndDate"+checkbox_id).slideUp();
        		$(".end_date"+checkbox_id).attr('required',false);
        	}else{
        		$("#EndDate"+checkbox_id).slideDown();
        		$(".end_date"+checkbox_id).attr('required',true);
        	}
        });

    </script>
@endpush