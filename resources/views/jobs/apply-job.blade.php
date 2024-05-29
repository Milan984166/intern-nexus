@extends('layouts.app')
@section('title',$job->job_title)
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>Profile Information</h1>
	</div>
</section>

<!--INNER BANNER END--> 



<!--MAIN START-->

<div id="main"> 

	<!--RECENT JOB SECTION START-->

	<section class="recent-row padd-tb job-detail">
		<div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-8">
					<div id="content-area">
						<div class="box">
							<div class="text-col">
								<h2>Are you sure you want to apply for this Job?</h2>
								@php
								    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$job->deadline);
								    $from = \Carbon\Carbon::now();

								    $diff_in_days = $from->diff($to,false);
								    $diffSign = $diff_in_days->format('%R');
								@endphp

								@if( Auth::check())
									@if($diffSign != '-' && Auth::user()->role == 2)
									<form method="POST" action="{{ route('job-seeker.confirm_apply_job') }}">
										@csrf
										<input type="hidden" name="job_id" value="{{ base64_encode($job->id) }}">
										<input type="hidden" name="employer_id" value="{{ base64_encode($job->user->id) }}">
										<input type="hidden" name="jobseeker_id" value="{{ base64_encode($jobseeker->id) }}">

										<button type="submit" href="{{ route('job-seeker.apply_job',['slug' => $job->slug]) }}" class="btn btn-success">Yes, Apply</button> 

										<a href="{{ route('job-details',['slug' => $job->slug]) }}" class="btn btn-danger pull-right">No, Cancel</a> 
									</form>

									@endif
								@else
									<a href="{{ route('front_login') }}" class="btn btn-danger">Login to Apply Job</a> 
								@endif
							</div>

							<div class="clearfix">
								<hr>
								<div class="row">
									<div class="col-md-6" style="border-right: 1px solid #eee;">
										<h4>Job Information</h4>
										<p>
											Job level:
											<strong>{{ $job->job_category->title }}</strong>
										</p>

										<p>
											@php
												$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
											@endphp
											Job Category:
											<strong>{{ $lookingForArray[$job->job_level] }}</strong>
										</p>
										<p>
											No. of Vacancy(s): 
											<strong>{{ $job->no_of_vacancy }}</strong>
										</p>
										<p>
											@php
		                    		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
		                    		        @endphp
											Employment Type: 
											<strong>{{ $employmentTypeArray[$job->employment_type] }}</strong>
										</p>
										<p>
											Job Location:
											<strong>{{ $job->location->title }}</strong>
										</p>
										<p>
											Offered Salary:
											<strong>
												@if($job->salary_type == 1)
												    ${{ $job->min_salary }} - ${{ $job->max_salary }}
												@elseif($job->salary_type == 2)
												    ${{ $job->min_salary }}
												@elseif($job->salary_type == 3)                                     
												    Negotiable
												@endif
											</strong>
										</p>

										<p>
											Deadline: 
											<strong>{{ date('jS F, Y', strtotime($job->deadline)) }} </strong>
										</p>
									</div>
									<div class="col-md-6">
										<h4>Requirements</h4>
										<p>
											Education Level: 
											@php
												$educationLevel = array('1' => 'Others', '2' => 'SLC', '3' => 'Intermediate' , '4' => 'Bachelor', '5' => 'Master', '6' => 'Ph.D.');
											@endphp
											<strong>{{ $educationLevel[$job->education_level] }}</strong>
										</p>

										<p>
											Experience Required: 
											@php
		                    		    		$experienceTypes = array('1' => 'More than', '2' => 'Less than', '3' => 'More than or equals to' , '4' => 'Less than or equals to', '5' => 'Equals to');
		                    		    	@endphp
											<strong>{{ $experienceTypes[$job->experience_type] }} {{ $job->experience_year }} {{ $job->experience_year == 1 ? 'Year' : 'Years' }}</strong>
										</p>
										@php
											$skills = json_decode($job->skill_ids);
										@endphp
										@if(count($skills) > 0)
										<p>
											Professional Skills: 
											@for($i=0; $i < count($skills); $i++)
		                                    <span class="badge badge-soft-secondary">{{ \App\Skill::where('id',$skills[$i])->first()->title }}</span>&nbsp;
		                                    @endfor
		                                </p>
		                                @endif
		                                <hr>
		                                @if(!$jobseeker->preference()->exists())
		                                <p>
		                                	Job Preference : <small style="color: #da334c;"><i>Not Updated</i></small>
		                                </p>
		                                @endif
		                                @if(!$jobseeker->education()->exists())
		                                <p>
		                                	Education : <small style="color: #da334c;"><i>Not Updated</i></small>
		                                </p>
		                                @endif
		                                @if(!$jobseeker->experience()->exists())
		                                <p>
		                                	Work Experience : <small style="color: #da334c;"><i>Not Updated</i></small>
		                                </p>
		                                @endif

		                                @if(!$jobseeker->training()->exists())
		                                <p>
		                                	Training : <small style="color: #da334c;"><i>Not Updated</i></small>
		                                </p>
		                                @endif
		                                <i>Please Update your <strong><a href="{{ url('jobseeker/profile') }}">Profile</a></strong> before Applying Job.</i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<aside>
						<div class="sidebar">
							<div class="box">
								<div class="text-box">

									<h4><a href="{{ route('company_jobs',['slug' => $job->user->employer_info->slug]) }}">{{ $employer_info->organization_name }}</a></h4>

									<p>{{ substr(strip_tags($employer_info->about),0, 200) }}..</p>

									<strong>Industry</strong>
									<p>{{ $employer_info->employer_category->title }}</p>

									<strong>Type of Business Entity</strong>
									@php
									$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
									@endphp
									<p>{{ $ownershipArray[$employer_info->ownership_type] }}</p>

									@if($employer_info->organization_size != '')
									<strong>No. of Employees</strong>
									@php
										$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
									@endphp
									<p>{{ @$OrgSizeArray[$employer_info->organization_size] }}</p>
									@endif

									<strong>Location</strong>
									<p>{{ $employer_info->address }} </p>

								</div>

							</div>

						</div>

					</aside>

				</div>

			</div>

		</div>

	</section>

	<!--RECENT JOB SECTION END--> 

</div>

<!--MAIN END--> 
@endsection
@push('post-scripts')
<script src="{{ asset('frontend/js/form.js') }}"></script>

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>
@endpush