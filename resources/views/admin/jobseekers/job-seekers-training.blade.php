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
                        <li class="breadcrumb-item active" aria-current="page">Training</li>
                    </ol>
                </nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/edit-training') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-graduation-cap"></i> Edit Training</a>
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
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/job-preference') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-briefcase"></i>
						Job Preference
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/education') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-graduation-cap"></i>
						Education
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/training') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
							<h5>Training</h5>
							<small>View Users Training Details</small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							@if($trainings->count() > 0)
								@php
									$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ];

									$durationUnit = ['1' => 'Day', '2' => 'Week', '3' => 'Month', '4' => 'Month'];
								@endphp
								@foreach($trainings as $edu)
			                    <div class="card">
		                    		<div class="card-footer">
		                    			<p class="h5">
		                    				{{ $edu->name }}

		                                	<strong class="float-right text-muted">

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
		                	<div class="card">
		                		<div class="card-body">
		                			<div class="row">
            							<div class="col-md-12 col-sm-12 text-center hidden-xs">
            				                <a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/edit-training') }}" class="btn btn-sm btn-outline-primary btn-round" title=""><i class="fa fa-graduation-cap"></i> Edit Training</a>
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
	</div>
</div>

@endsection