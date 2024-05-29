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
                        <li class="breadcrumb-item active" aria-current="page">Basic Information</li>
                    </ol>
                </nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/edit-basic-info') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-info-circle"></i> Edit Basic Info</a>
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

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
							<h5>Basic Information</h5>
							<small>View Users Personal Details</small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<div class="card-body">
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
		</div>
	</div>
</div>

@endsection