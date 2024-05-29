@extends('admin/layouts.header-sidebar')
@section('title', $jobseeker->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-12 col-sm-12">
				<h2>{{ $jobseeker->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers') }}">Job Seekers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.profile',['id' => $id]) }}">{{ $jobseeker->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.basic-info',['id' => $id]) }}">Basic Information</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Personal Information</li>
                    </ol>
                </nav>
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

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-primary ">
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
			<div class="card border-secondary">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>Basic Information</h5>
							<small>Edit Users Personal Details</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.job-seekers.update-basic-info',['id' => $id]) }}" enctype="multipart/form-data">
					@csrf
					<div class="card-body mb-0">	
						<div class="card-body pb-0 mb-0">
						
							<div class="row">
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Name: </small>
									<input type="text" name="name" class="form-control" required value="{{ old('name') ? old('name') : $jobseeker->name }}" placeholder="eg: John Doe">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-envelope"></i> Email address: </small>
									<input type="email" name="email" class="form-control" required value="{{ old('email') ? old('email') : $jobseeker->email }}" disabled readonly placeholder="eg: hello@example.com">
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Role: </small>
									<input type="text" class="form-control" disabled readonly value="Job Seeker">
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Job Seeker's Status: </small>
									<div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text" style="background-color: #e1e8ed">
                                                <?php $status =  old('status') ? old('status') : $jobseeker->status  ?>
                                                <input type="checkbox" name="status" value="1" aria-label="Checkbox for following text input" <?=$status == 1 ? 'checked' : (old('status') == 1 ? 'checked' : '') ?>>
                                            </div>
                                        </div>
                                        <span class="form-control disabled">Check of Active User</span>
                                    </div>
									<hr>
								</div>

								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-phone"></i> Mobile Number: </small>
									<input type="text" name="phone" class="form-control" required value="{{ old('phone') ? old('phone') : $jobseeker->phone }}" placeholder="eg: 9876543210" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')">
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-calendar"></i> Date of Birth: </small>
									<input type="text" name="dob" class="form-control" value="{{ old('dob') ? old('dob') : $jobseeker->dob }}" data-provide="datepicker" data-date-autoclose="true" class="form-control" data-date-format="yyyy-mm-dd">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-map-marker"></i> Current Address: </small>
									<input type="text" name="current_address" class="form-control" required value="{{ old('current_address') ? old('current_address') : $jobseeker->current_address }}" placeholder="eg: Kathmandu, Nepal">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-map-marker"></i> Permanent Address: </small>
									<input type="text" name="permanent_address" class="form-control" required value="{{ old('permanent_address') ? old('permanent_address') : $jobseeker->permanent_address }}" placeholder="eg: Kathmandu, Nepal">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-globe"></i> Nationality: </small>
									<select class="custom-select" name="nationality" required>
									    <option selected="" value="">Choose...</option>
									    @php
									    $nationalities = DB::table('nationalities')->get();
									    @endphp
									    @foreach($nationalities as $nation)
									    <option value="{{ $nation->Nationality }}" {{ $jobseeker->nationality == $nation->Nationality ? 'selected' : (old('nationality') == $nation->Nationality ? 'selected' : '') }}>{{ $nation->Nationality }}</option>
									    @endforeach
									</select>
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-location-arrow"></i> Religion: </small>
									<select class="custom-select" name="religion">
									    <option selected="" value="">Choose...</option>
									    
									    <option value="Hinduism" {{ $jobseeker->religion == 'Hinduism' ? 'selected' : (old('religion') == 'Hinduism' ? 'selected' : '') }}>Hinduism</option>

									    <option value="Buddism" {{ $jobseeker->religion == 'Buddism' ? 'selected' : (old('religion') == 'Buddism' ? 'selected' : '') }}>Buddism</option>

									    <option value="Christianity" {{ $jobseeker->religion == 'Christianity' ? 'selected' : (old('religion') == 'Christianity' ? 'selected' : '') }}>Christianity</option>

									    <option value="Jainism" {{ $jobseeker->religion == 'Jainism' ? 'selected' : (old('religion') == 'Jainism' ? 'selected' : '') }}>Jainism</option>

									    <option value="NonReligious" {{ $jobseeker->religion == 'NonReligious' ? 'selected' : (old('religion') == 'NonReligious' ? 'selected' : '') }}>NonReligious</option>

									    <option value="Sikhism" {{ $jobseeker->religion == 'Sikhism' ? 'selected' : (old('religion') == 'Sikhism' ? 'selected' : '') }}>Sikhism</option>

									</select>
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
									<select class="custom-select" name="gender" required>
		                                <option selected="" value="">Choose...</option>
		                                <option value="1" {{ $jobseeker->gender == 1 ? 'selected' : (old('gender') == 1 ? 'selected' : '') }}>Male</option>
		                                <option value="2" {{ $jobseeker->gender == 2 ? 'selected' : (old('gender') == 2 ? 'selected' : '') }}>Female</option>
		                                <option value="3" {{ $jobseeker->gender == 3 ? 'selected' : (old('gender') == 3 ? 'selected' : '') }}>Others</option>
		                            </select>
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted">Maritial Status: </small>
									<select class="custom-select" name="maritial_status">
									    <option selected="" value="">Choose...</option>
									    
									    <option value="Married" {{ $jobseeker->maritial_status == 'Married' ? 'selected' : (old('maritial_status') == 'Married' ? 'selected' : '') }}>Married</option>

									    <option value="Unmarried" {{ $jobseeker->maritial_status == 'Unmarried' ? 'selected' : (old('maritial_status') == 'Unmarried' ? 'selected' : '') }}>Unmarried</option>
									</select>
									<hr>
								</div>

								<div class="col-md-6">	
	                    			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Profile Image*
                		            </small>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-image"></i> &nbsp;Profile Image</span>
                                        </div>
                                        <input type="file" name="image" class="bg-primary text-white form-control" {{ ($jobseeker->image == '' ? 'required' : '' ) }}>
                                    </div>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                	<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Current Image*
                		            </small>
                                    <div class="input-group mb-3">
                                    	@if($jobseeker->image != '')
                                        <img width="100" src="{{ asset('storage/jobseekers/thumbs/small_'.$jobseeker->image) }}" class="img-thumbnail" alt="no-image">
                                        @else
                                        	<img class="img-thumbnail" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                                        @endif
                                    </div>
                                    <hr>
                                </div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
	                            <a href="{{ route('admin.job-seekers.basic-info',['id' => $id]) }}"
	                            class="btn btn-outline-danger">CANCEL</a>

	                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
	                        </div>
	                    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection