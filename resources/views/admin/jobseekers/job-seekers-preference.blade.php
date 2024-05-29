@extends('admin/layouts.header-sidebar')
@section('title', $jobseeker->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-6 col-sm-12">
				<h2>{{ $jobseeker->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers') }}">Job Seekers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.profile',['id' => $id]) }}">{{ $jobseeker->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Job Preference</li>
                    </ol>
                </nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/edit-job-preference') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-briefcase"></i> Edit Job Preference</a>
            </div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-3">
			<div class="card social">
				<div class="profile-header d-flex justify-content-between justify-content-center">
					<div class="d-flex">
						<div class="mr-3">
							@if($jobseeker->image != '')
                            	<img src="{{ asset('storage/jobseekers/thumbs/small_'.$jobseeker->image) }}" class="rounded" alt="no-image">
                            @else
                            	<img class="rounded" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                            @endif

	                    </div>
						<div class="details">
							<h5 class="mb-0">{{ $jobseeker->name }}</h5>
							<span class="text-light">
								@if ($jobseeker->gender == 1) 
								    Male
								@elseif ($jobseeker->gender == 2)
								    Female
								@elseif ($jobseeker->gender == 3) 
								    Others
								@else
								    To be Updated
								@endif
							</span>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id)) }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-user"></i>
						Profile Overview
					</a>

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-info-circle"></i>
						Basic Information
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/job-preference') }}" class="btn btn-block btn-round text-left btn-primary disabled">
						<i class="fa fa-briefcase"></i>
						Job Preference
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/education') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-graduation-cap"></i>
						Education
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/training') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-tasks"></i>
						Training
					</a>

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/work-experience') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-building"></i>
						Work Experience
					</a>
				</div>
			</div>                    
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>Job Preference</h5>
							<small>View Users Job Preference Details</small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					@if($preferences)
					<div class="card-body">
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
					@else
					<div class="card">
                		<div class="card-body">
                			<div class="row">
    							<div class="col-md-12 col-sm-12 text-center hidden-xs">
    				                <a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/edit-job-preference') }}" class="btn btn-sm btn-outline-primary btn-round" title=""><i class="fa fa-briefcase"></i> Edit Job Preferences</a>
    				            </div>
                			</div>
                		</div>
                	</div>
                	@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection