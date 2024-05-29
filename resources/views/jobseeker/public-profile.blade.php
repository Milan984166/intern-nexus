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
						</ul>
					</div>
				</div>

				<div class="col-sm-9">
					<div class="tab-body">
						<!-- Tab panes -->
						<div class="tab-content">

							<div role="tabpanel" class="tab-pane active" id="basic-information">

								<div class="panel panel-info">
									<div class="panel-body">

										<div class="row">

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-user"></i> Name: </small>
												<p>{{ $jobseeker->name }}</p>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-envelope"></i> Email address: </small>
												<p>{{ $jobseeker->email }}</p>                            
												<hr>
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-phone"></i> Mobile Number: </small>
												<p>{{ $jobseeker->phone }}</p>
												<hr>
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-calendar"></i> Date of Birth: </small>
												<p>{{ date('F jS, Y',strtotime($jobseeker->dob)) }}</p>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-map-marker"></i> Current Address: </small>
												<p>{{ $jobseeker->current_address }}</p>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-map-marker"></i> Permanent Address: </small>
												<p>{{ $jobseeker->permanent_address }}</p>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-globe"></i> Nationality: </small>
												<p class="m-b-0">{{ $jobseeker->nationality }}</p>
												<hr>
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-location-arrow"></i> Religion: </small>
												<p class="m-b-0">{{ $jobseeker->religion }}</p>
												<hr>
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
												<p class="m-b-0">
													@if ($jobseeker->gender == 1) 
													    Male
													@elseif ($jobseeker->gender == 2)
													    Female
													@elseif ($jobseeker->gender == 3) 
													    Others
													@else
													    To be Updated
													@endif
												</p>
												<hr>
											</div>

											<div class="col-md-6">
												<small class="text-muted">Maritial Status: </small>
												<p class="m-b-0">{{ $jobseeker->maritial_status }}</p>
												<hr>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div role="tabpanel" class="tab-pane" id="job-preference">

								@if($preferences)
								<div class="panel panel-info">
									<div class="panel-body">

										<div class="row">

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-briefcase"></i> Looking For: </small>
												@php
													$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
												@endphp
												<p><b>{{ $lookingForArray[$preferences->looking_for] }}</b></p>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-clock-o"></i> Available For: </small>
												@php
													$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
													$dbEmploymentType = json_decode($preferences->employment_type);
												@endphp

												<p>
													@for($i=0; $i < count($dbEmploymentType); $i++)
														<b>{{ $employmentTypeArray[$dbEmploymentType[$i]]}}</b> | 
													@endfor
												</p>                            
												<hr>
											</div>
											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-money"></i> Expected Salary: </small>
												@php
													$salPeriod = array('1' => 'Daily', '2' => 'Weekly', '3' => 'Monthly' , '4' => 'Yearly');
												@endphp
												<p>
													<b>NRs. {{ $preferences->expected_salary }} {{ $salPeriod[$preferences->expected_salary_period] }}</b>
												</p>
												<hr>
											</div>
											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-map-marker"></i> Job Preference Location: </small>
												@php
													$location =	\App\Location::where('id',$preferences->location_id)->first();
												@endphp
												<p>{{ isset($location) ? $location->title : 'Location Not Set'}}</p>
												<hr>	
											</div>
											<div class="col-md-12">
												<small class="text-muted"><i class="fa fa-tasks"></i> Career Objectives Summary: </small>
												<div>{!! $preferences->career_objective !!}</div>
												<hr>	
											</div>
											<div class="col-md-12">
												<p>Job Categories</p>
												<div class="row">
												@foreach($preferences->category_skills as $key => $catSkill)
													@php
														$categoryTitle = \App\JobCategory::where('id', $catSkill->job_category_id)->select('title')->first();
													@endphp
													@if($categoryTitle)
													<div class="col-md-4">
														<span class="badge badge-info m-0 mb-1">{{ $categoryTitle->title }} </span><br>
														<small class="text-muted">
															Skills :<br>
															
															@php
																$categorySkills = json_decode($catSkill->skill_ids);
															@endphp

															<ul>
															@for($j=0; $j < count($categorySkills); $j++)
																@php
																	$skillTitle = \App\Skill::where('id', $categorySkills[$j])->select('title')->first();
																@endphp
																@if($skillTitle)
																
																<li>{{ $skillTitle->title }}</li>
																@endif
															@endfor
															</ul>
														</small>
													</div>
													@endif
												@endforeach
												</div>
												<hr>	
											</div>

											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-globe"></i> Nationality: </small>
												<p class="m-b-0">{{ $jobseeker->nationality }}</p>
												<hr>
											</div>
											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-location-arrow"></i> Religion: </small>
												<p class="m-b-0">{{ $jobseeker->religion }}</p>
												<hr>
											</div>
											<div class="col-md-6">
												<small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
												<p class="m-b-0">
													@if ($jobseeker->gender == 1) 
													    Male
													@elseif ($jobseeker->gender == 2)
													    Female
													@elseif ($jobseeker->gender == 3) 
													    Others
													@else
													    To be Updated
													@endif
												</p>
												<hr>
											</div>
											<div class="col-md-6">
												<small class="text-muted">Maritial Status: </small>
												<p class="m-b-0">{{ $jobseeker->maritial_status }}</p>
												<hr>
											</div>
										</div>
									</div>
								</div>
								@else

								<div class="panel panel-info">
			                		<div class="panel-heading">
			                			<div class="row">
	            							<div class="col-md-12 col-sm-12 text-center hidden-xs">
	            				                <i class="fa fa-graduation-cap"></i> Jobseeker has not Job Preference Details
	            				            </div>
			                			</div>
			                		</div>
			                	</div>
								@endif
							</div>
							<div role="tabpanel" class="tab-pane" id="education">
								@if($educations->count() > 0)
									@php
										$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
									@endphp
									@foreach($educations as $edu)
				                    <div class="panel panel-info">
			                    		<div class="panel-heading">
			                    			<strong class="mb-0">
			                    				<i class="fa fa-graduation-cap"></i>
			                                	{{ $edu->institute }}, <small> {{$months[$edu->month].", ". $edu->year }}</small>
			                                </strong>
			                    			<p class="h5">
			                    				{{ $edu->program }}
			                    				@if($edu->student_here != 1)
			                                	<strong class="pull-right text-muted">
			                                		{{ $edu->marks_unit == 1 ? $edu->marks."%" : "CGPA - ".$edu->marks }} 
			                                	</strong>
			                                	@endif
			                                </p>
			                                
			                                <p class="mb-0">{{ $edu->board }}</p>
			                    		</div>
				                    </div>
				                	@endforeach
			                	@else
			                	<div class="panel panel-info">
			                		<div class="panel-heading">
			                			<div class="row">
	            							<div class="col-md-12 col-sm-12 text-center hidden-xs">
	            				                <i class="fa fa-graduation-cap"></i> Jobseeker has not Updated Education Details
	            				            </div>
			                			</div>
			                		</div>
			                	</div>
			                	@endif
							</div>


							<div role="tabpanel" class="tab-pane" id="experience">
								@if($experiences->count() > 0)
									@php
										$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
									@endphp
									@foreach($experiences as $exp)
				                    <div class="panel panel-info">
			                    		<div class="panel-heading">
			                    			<p class="h5">
			                    				<strong><i class="fa fa-building"></i> {{ $exp->job_title }}</strong>
			                                	<strong class="pull-right text-muted">
			                                		{{ $months[$exp->start_month].", ". $exp->start_year }} -
			                                		{{ $exp->working_here != 1 ? $months[$exp->end_month].", ". $exp->end_year : 'Present' }}
			                                	</strong>
			                                </p>
			                                <p class="mb-0">
			                                	{{ $exp->organization_name }} 
			                                	<small>( {{ \App\JobCategory::where('id',$exp->job_category_id)->first()->title }} )</small>
			                                	<br>
			                                	<small> {{ $exp->job_location }}</small>
			                                </p>
			                                <p class="mb-0">{{ $exp->board }}</p>
			                    		</div>
			                    		<div class="panel-body">
			                    			<p class="mb-0">
			                    				{{ $exp->duties_responsibilities }}
			                    			</p>
			                    		</div>
				                    </div>
				                	@endforeach
			                	@else
			                	<div class="panel panel-info">
			                		<div class="panel-heading">
			                			<div class="row">
	            							<div class="col-md-12 col-sm-12 text-center hidden-xs">
	            				                <i class="fa fa-building"></i> Jobseeker has not Updated Work Experience Details
	            				            </div>
			                			</div>
			                		</div>
			                	</div>
			                	@endif
							</div>
							<div role="tabpanel" class="tab-pane" id="training">
								@if($trainings->count() > 0)
									@php
										$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

										$durationUnit = ['1' => 'Day', '2' => 'Week', '3' => 'Month', '4' => 'Month'];
									@endphp
									@foreach($trainings as $edu)
				                    <div class="panel panel-info">
			                    		<div class="panel-heading">
			                    			<p class="h5">
			                    				<strong><i class="fa fa-tasks"></i> {{ $edu->name }}</strong>

			                                	<strong class="pull-right text-muted">

			                                		{{ $edu->duration }} {{ $durationUnit[$edu->duration_unit] }}

			                                	</strong>
			                                </p>
			                                <p class="mb-0">

			                                	{{ $edu->institution }}, <small> {{$months[$edu->month].", ". $edu->year }}</small>
			                                	
			                                </p>
			                                <p class="mb-0">{{ $edu->board }}</p>
			                    		</div>
				                    </div>
				                	@endforeach
			                	@else
			                	<div class="panel panel-info">
			                		<div class="panel-heading">
			                			<div class="row">
	            							<div class="col-md-12 col-sm-12 text-center hidden-xs">
	            				                <i class="fa fa-building"></i> Jobseeker has not Updated Training Details
	            				            </div>
			                			</div>
			                		</div>
			                	</div>
			                	@endif
							</div>
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

<!--MAIN END--> 
@endsection
@push('post-scripts')
<!-- <script src="{{ asset('frontend/js/form.js') }}"></script> -->

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>
@endpush