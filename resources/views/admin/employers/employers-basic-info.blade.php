@extends('admin/layouts.header-sidebar')
@section('title', $employer->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-6 col-sm-12">
				<h2>{{ $employer->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers') }}">Employers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers.profile',['id' => $id]) }}">{{ $employer->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Company Information</li>
                    </ol>
                </nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/edit-basic-info') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-info-circle"></i> Edit Basic Info</a>
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

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
			<div class="card">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>Company Information</h5>
							<small>View Users Personal Details</small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					@if($info)
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-user"></i> Organization Name: </small>
								<p>{{ $info->organization_name }}</p>
								<hr>	
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-envelope"></i> Company Email: </small>
								<p>{{ $info->email }}</p>                            
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-map-marker"></i> Address: </small>
								<p>{{ $info->address }}</p>
								<hr>
							</div>

							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
								<p>{{ $info->phone }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-building"></i> Industry: </small>
								<p>{{ \App\JobCategory::where('id', @$info->category_id)->select('title')->first()->title }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-building"></i> Ownership: </small>
								@php
									$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
								@endphp
								<p>{{ @$ownershipArray[$info->ownership_type] }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-globe"></i> Website: </small>
								<p>{{ $info->website }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-building"></i> Size Of Organization: </small>
								@php
									$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
								@endphp
								<p>{{ @$OrgSizeArray[$info->organization_size] }}</p>
								<hr>
							</div>
							<div class="col-md-12">
								<small class="text-muted"><i class="fa fa-bars"></i> Summary: </small>
								<div>{!! $info->about !!}</div>
								<hr>
							</div>
							<div class="col-md-12">
								<strong>Contact Person Information</strong>
								<div class="row">
									<div class="col-md-6">
										<small class="text-muted"><i class="fa fa-user"></i> Name: </small>
										<p>{{ $info->cp_name }}</p>
										<hr>
									</div>
									<div class="col-md-6">
										<small class="text-muted"><i class="fa fa-user"></i> Phone: </small>
										<p>{{ $info->cp_contact }}</p>
										<hr>
									</div>
									<div class="col-md-6">
										<small class="text-muted"><i class="fa fa-user"></i> Designation: </small>
										<p>{{ $info->cp_designation }}</p>
										<hr>
									</div>
									<div class="col-md-6">
										<small class="text-muted"><i class="fa fa-user"></i> Email: </small>
										<p>{{ $info->cp_email }}</p>
										<hr>
									</div>
								</div>
							</div>
						</div>
					</div>
					@else
					<div class="card">
                		<div class="card-body">
                			<div class="row">
    							<div class="col-md-12 col-sm-12 text-center hidden-xs">
    				                <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/edit-basic-info') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-info-circle"></i> Edit Basic Info</a>
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