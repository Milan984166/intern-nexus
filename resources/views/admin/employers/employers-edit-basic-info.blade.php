@extends('admin/layouts.header-sidebar')
@section('title', $employer->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-12 col-sm-12">
				<h2>{{ $employer->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers') }}">Employers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers.profile',['id' => $id]) }}">{{ $employer->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers.basic-info',['id' => $id]) }}">Company Information</a></li>
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
						<div class="details">
							<h5 class="mb-0">{{ $employer->name }}</h5>
							<span class="text-light">
								{{ $employer->email }}
							</span>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id)) }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-user"></i>
						Profile Overview
					</a>

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-primary ">
						<i class="fa fa-info-circle"></i>
						Company Information
					</a>

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/posted-jobs') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-database"></i>
						Posted Jobs
					</a>
					
				</div>
			</div>                    
		</div>
		<div class="col-md-9">
			<div class="card border-secondary">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>Company Information</h5>
							<small>Edit Users Personal Details</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.employers.update-basic-info',['id' => $id]) }}" enctype="multipart/form-data">
					@csrf
					<div class="card-body mb-0">	
						<div class="card-body pb-3 mb-0">
						
							<div class="row">
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Organization Name: </small>
									<input type="text" name="organization_name" class="form-control" required value="{{ old('organization_name') ? old('organization_name') : (isset($info->organization_name) ? $info->organization_name : $employer->name ) }}" placeholder="eg: KTM RUSH PVT. LTD.">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-envelope"></i> Company Email: </small>
									<input type="email" name="email" class="form-control" required value="{{ old('email') ? old('email') : (isset($info->email) ? $info->email : $employer->email ) }}" disabled readonly placeholder="eg: hello@example.com">
									<hr>
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-map-marker"></i> Company Address: </small>
									<input type="text" name="address" class="form-control" required value="{{ old('address') ? old('address') : (isset($info->address) ? $info->address : $employer->current_address ) }}" placeholder="eg: Kathmandu, Nepal">
									<hr>	
								</div>
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
									<input type="text" name="phone" class="form-control" required value="{{ old('phone') ? old('phone') : (isset($info->phone) ? $info->phone : $employer->phone ) }}" placeholder="eg: Kathmandu, Nepal">
									<hr>	
								</div>
								<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Industry*
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
											$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

		                                @endphp
                        		        <select class="form-control" name="category_id" required>

                        		        	<option selected disabled>Select Company Category</option>

                        		        	@foreach($jobCategories as $jobCat)
                                            	<option {{ @$info->category_id == $jobCat->id ? 'selected' : (old('category_id') == $jobCat->id ? 'selected' : '') }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
                                            @endforeach
                                            

                                        </select>
                        		    </div>
                        		    <hr>
                        		</div>
                        		<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Ownership
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
                        		    	@endphp
                        		        <select class="form-control" name="ownership_type">

                        		        	<option value="" selected>--------------------</option>

                        		        	@foreach($ownershipArray as $key => $look)
                                            <option {{ @$info->ownership_type == $key ? 'selected' : (old('ownership_type') == $key ? 'selected' : '') }} value='{{ $key }}'>{{ $ownershipArray[$key] }}</option>
                                            @endforeach
                                            

                                        </select>
                        		    </div>
                        		    <hr>
                        		</div>

                        		<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Size of Organization*
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
                        		    	@endphp
                        		        <select class="form-control" name="organization_size" >

                        		        	<option value="" selected>--------------------</option>

                        		        	@foreach($OrgSizeArray as $key => $look)
                                            <option {{ @$info->organization_size == $key ? 'selected' : (old('organization_size') == $key ? 'selected' : '') }} value='{{ $key }}'>{{ $OrgSizeArray[$key] }}</option>
                                            @endforeach
                                            

                                        </select>
                        		    </div>
                        		    <hr>
                        		</div>
                        		<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Website: </small>
									<input type="text" name="website" class="form-control" required value="{{ old('website') ? old('website') : @$info->website }}" placeholder="eg: https://www.ktmrush.com">
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
                                        <input type="file" name="image" class="bg-primary text-white form-control" {{ (@$info->image == '' ? 'required' : '' ) }}>
                                    </div>
                                    <hr>
                                </div>

                                <div class="col-md-6">
                                	<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Current Image*
                		            </small>
                                    <div class="input-group mb-3">
                                    	@if(@$info->image != '')
                                        <img width="100" src="{{ asset('storage/employers/company-info/thumbs/small_'.$info->image) }}" class="img-thumbnail" alt="no-image">
                                        @else
                                        	<img class="img-thumbnail" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                                        @endif
                                    </div>
                                    <hr>
                                </div>

								<div class="col-md-12">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; About*
                		            </small>
                		            <textarea name="about" id="editor1" class="form-control ckeditor" required>{{ old('about') ? old('about') : @$info->about }}</textarea>
                        		</div>
							</div>
						</div>
						<hr>
						<div class="card-header">
							<div class="d-flex">
								<div class="details">
									<small>Edit Contact Person Information</small>
								</div>                                
							</div>
						</div>
						<div class="card-body pb-0 mb-0">
							<div class="row">
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Name* </small>
									<input type="text" name="cp_name" class="form-control" required value="{{ old('cp_name') ? old('cp_name') : @$info->cp_name }}" placeholder="eg: John Doe">
									<hr>	
								</div>

								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-phone"></i> Mobile Number* </small>
									<input type="text" name="cp_contact" class="form-control" required value="{{ old('cp_contact') ? old('cp_contact') : @$info->cp_contact }}" placeholder="eg: 9876543210" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')">
									<hr>
								</div>

								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Designation* </small>
									<input type="text" name="cp_designation" class="form-control" required value="{{ old('cp_designation') ? old('cp_designation') : @$info->cp_designation }}" placeholder="eg: Managing Director">
									<hr>	
								</div>

								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-user"></i> Email </small>
									<input type="email" name="cp_email" class="form-control" value="{{ old('cp_email') ? old('cp_email') : @$info->cp_email }}" placeholder="eg: hello@example.com">
									<hr>	
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
	                            <a href="{{ route('admin.employers.basic-info',['id' => $id]) }}"
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