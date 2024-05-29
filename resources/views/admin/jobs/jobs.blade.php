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
                        <li class="breadcrumb-item active" aria-current="page">Posted Jobs</li>
                    </ol>
                </nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/post-jobs') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-database"></i> Post a Job!</a>
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

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-info-circle"></i>
						Company Information
					</a>

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/posted-jobs') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
							<h5>Posted Jobs</h5>
							<small>
                                View Jobs Posted By <strong>{{ isset($employer->employer_info->organization_name) ? $employer->employer_info->organization_name : $employer->name }}</strong>
                            </small>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					@if($jobs->count() > 0)
					
						<div class="table-responsive">
                            <table id="only-bodytable" class="table table-hover table-custom spacing8">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>No. of Vacancy</th>
                                        <th>Location</th>
                                        <th>Deadline</th>
                                        <th class="w100">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">
                                                {{ $job->job_title }}
                                                {!! $job->featured == 1 ? '<i style="color: green;" class="fa fa-check-circle"></i>' : '' !!}
                                            </h6>
                                            <small>{{ $job->job_category->title }}</small>
                                        </td>
                                        <td>
                                            {{ $job->no_of_vacancy }}
                                        </td>
                                        <td>
                                        	{{ $job->location->title }}
                                        </td>

                                        <td>
                                            {{ date('jS F, Y',strtotime($job->deadline)) }}
                                            @if($job->deadline < date('Y-m-d H:i:s'))
                                            <i style="color:red;" class="fa fa-clock-o"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- <a href="#viewModal" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                            data-id="{{ $job->id }} "
                                            id="view{{ $job->id }}"
                                            data-email="{{ $job->email }}"
                                            data-gender="{{ $job->gender }}"
                                            data-phone="{{ $job->phone }}"
                                            data-current_address="{{ $job->current_address }}"
                                            data-permanent_address="{{ $job->permanent_address }}"
                                            data-dob="{{ date('jS F,Y',strtotime($job->dob)) }}"
                                            data-religion="{{ $job->religion }}"
                                            data-nationality="{{ $job->nationality }}"
                                            data-maritial_status="{{ $job->maritial_status }}"
                                            onclick="view_employer('{{ $job->id }}','{{ addslashes($job->job_title) }}','{{ $job->status }}')"
                                            title="View">
	                                            <i class="fa fa-eye"></i>
	                                        </a> -->

	                                        <a href="{{ url('admin/employers/profile/'.base64_encode($job->id)).'/posted-jobs/view/'.base64_encode($job->id) }}" class="btn btn-sm btn-info" title="Preview Posted Jobs">
	                                            <i class="fa fa-eye"></i>
	                                        </a>
	                                        <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/posted-jobs/edit/'.base64_encode($job->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit Posted Jobs">
	                                            <i class="fa fa-edit"></i>
	                                        </a>


	                                        <a href="#delete" data-toggle="modal" data-id="{{ $job->id }}" id="delete{{ $job->id }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="delete_employer('{{ $job->id }}')">
	                                            <i class="fa fa-trash"></i>
	                                        </a>
	                                        

	                                    </td>
	                                </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
					
					@else
					<div class="card">
                		<div class="card-body">
                			<div class="row">
    							<div class="col-md-12 col-sm-12 text-center">
    								<p>You don't have posted any jobs at the moment. </p>
    				                <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/post-jobs') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-database"></i> Post a Job, now!</a>
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

<div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6><span class="viewName"></span><span class="mb-0" id="viewStatus"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-user"></i> Name: </small>
                                        <p class="mb-0 viewName"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-envelope"></i> Email: </small>
                                        <p class="mb-0" id="viewEmail"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
                                        <p class="mb-0" id="viewGender"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
                                        <p class="mb-0" id="viewPhone"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-user"></i> Maritial Status: </small>
                                        <p class="mb-0" id="viewMaritialStatus"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> Current Address: </small>
                                        <p class="mb-0" id="viewCurrentAddress"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> Permanent Address: </small>
                                        <p class="mb-0" id="viewPermanentAddress"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-calendar"></i> Date of Birth: </small>
                                        <p class="mb-0" id="viewDOB"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-location-arrow"></i> Religion: </small>
                                        <p class="mb-0" id="viewReligion"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-globe"></i> Nationality: </small>
                                        <p class="mb-0" id="viewNationality"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="text-align: right;" type="button" data-dismiss="modal"
                    class="btn btn-outline-danger">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="delete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Delete Employer</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pricing_page">
                <p>Are your Sure?</p>
            </div>
            <div class="modal-footer">
                <a style="text-align: right;" type="button" class="btn btn-outline-success" href="">Yes, Delete It!</a>
                <button style="text-align: left;" type="button" data-dismiss="modal" class="btn btn-outline-danger">No</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function view_employer(id, name, status) {
        var email = $('#view' + id).attr('data-email');
        var gender = $('#view' + id).attr('data-gender');
        var phone = $('#view' + id).attr('data-phone');
        var current_address = $('#view' + id).attr('data-current_address');
        var permanent_address = $('#view' + id).attr('data-permanent_address');
        var dob = $('#view' + id).attr('data-dob');
        var religion = $('#view' + id).attr('data-religion');
        var nationality = $('#view' + id).attr('data-nationality');
        var maritial_status = $('#view' + id).attr('data-maritial_status');
        $('.viewName').html(name);
        $('#viewEmail').html(email);
        $('#viewPhone').html(phone);
        $('#viewCurrentAddress').html(current_address);
        $('#viewPermanentAddress').html(permanent_address);
        $('#viewDOB').html(dob);
        $('#viewReligion').html(religion);
        $('#viewNationality').html(nationality);
        $('#viewMaritialStatus').html(maritial_status);

        if (gender == 1) {
            $('#viewGender').html('Male');
        }else if (gender == 2) {
            $('#viewGender').html('Female');
        }else if (gender == 3) {
            $('#viewGender').html('Others');
        }else{
            $('#viewGender').html('To be Updated');
        }
        if (status == 0) {
            $('#viewStatus').html('<span class="badge badge-danger">Inactive</span>');
        }else{
            $('#viewStatus').html('<span class="badge badge-success">Active</span>');
        }
    }

    function delete_employer(id) {
        var conn = './employers/delete/' + id;
        $('#delete a').attr("href", conn);
    }
</script>

@endsection