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
                        <li class="breadcrumb-item active" aria-current="page">Profile Overview</li>
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
							@if($employer->image != '')
                            	<img src="{{ asset('storage/employers/thumbs/small_'.$employer->image) }}" class="rounded" alt="no-image">
                            @else
                            	<img class="rounded" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                            @endif

	                    </div>
						<div class="details">
							<h5 class="mb-0">{{ $employer->name }}</h5>
							<span class="text-light">
								{{ $employer->email }}
							</span>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id)) }}" class="btn btn-block btn-round text-left btn-primary disabled">
						<i class="fa fa-user"></i>
						Profile Overview
					</a>

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-outline-info">
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
							<h5>Profile Overview</h5>
							<small>View Users Profile Details</small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-user"></i> Organization Name: </small>
								<p>{{ isset($info->organization_name) ? $info->organization_name : $employer->organization_name }}</p>
								<hr>	
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-envelope"></i> Company Email: </small>
								<p>{{ isset($info->email) ? $info->email : $employer->email }}</p>                            
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-map-marker"></i> Address: </small>
								<p>{{ isset($info->address) ? $info->address : $employer->address }}</p>
								<hr>
							</div>

							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
								<p>{{ isset($info->phone) ? $info->phone : $employer->phone }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-phone"></i> Joined On: </small>
								<p>{{ date('F jS, Y',strtotime($employer->created_at)) }}</p>
								<hr>
							</div>
							<div class="col-md-6">
								<small class="text-muted"><i class="fa fa-building"></i> Number of Jobs Posted: </small>
								<p>12 Jobs Posted</p>
								<hr>
							</div>
							<div class="col-md-12">
								<small class="text-muted"><i class="fa fa-bars"></i> About: </small>
								<p>{!! isset($info->about) ? $info->about : '' !!}</p>
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