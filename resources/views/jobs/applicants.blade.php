@extends('layouts.app')
@section('title',$employer->name)
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>{{ $employer_info->organization_name }}</h1>
	</div>
</section>

<!--INNER BANNER END--> 

<!--MAIN START-->
<div id="main"> 
	<!--RECENT JOB SECTION START-->
	<section class="recent-row padd-tb">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-9">
					<div id="content-area">
						<h2>Applicants for "{{ $job->job_title }}"</h2>
						@if($applicants->count() > 0)
						<table class="table table-responsive">
							<thead>
								<tr>
									<th>SN.</th>
									<th>Job Title</th>
									<th>Job Seeker</th>
									<th>Applied Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($applicants as $key => $appJobs)
								<tr>
									<td>{{$key+1}}.</td>
									<td>
										<a href="{{ route('job-details',['slug' => $appJobs->job_details->slug]) }}">
											{{ $appJobs->job_details->job_title }}
										</a>
									</td>
									<td>
										<a target="_blank" href="{{ route('jobseeker-details',['id' => base64_encode($appJobs->jobseeker_id), 'client' => urlencode($appJobs->job_seeker->name)]) }}">
											{{ $appJobs->job_seeker->name }}
										</a>
									</td>
									<td>{{ date('jS F, Y',strtotime($appJobs->created_at)) }}</td>
									<td>
										@if($appJobs->status == 0)
										<button class="btn btn-xs btn-primary">Pending</button>
										@elseif($appJobs->status == 1)
										<button class="btn btn-xs btn-success">Approved</button>
										@elseif($appJobs->status == 2)
										<button class="btn btn-xs btn-danger">Declined</button>
										@endif
									</td>
									<td>
										@if($appJobs->status == 0)
										<div class="btn-group">
											<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Action <span class="caret"></span>
											</button>
											<ul class="dropdown-menu" style="padding: 0;">
												<li>
													<a href="#changeStatusModal"
													data-toggle="modal"
													id="confirmStatus{{ $appJobs->id }}" 
													onclick="change_status('{{ base64_encode($appJobs->id) }}' ,'{{ base64_encode('1') }}', '{{ $appJobs->job_seeker->name }}','{{ $appJobs->job_details->job_title }}')">Approve</a>
												</li>

												<li>
													<a href="#changeStatusModal"
													data-toggle="modal"
													id="confirmStatus{{ $appJobs->id }}" 
													onclick="change_status('{{ base64_encode($appJobs->id) }}' ,'{{ base64_encode('2') }}', '{{ $appJobs->job_seeker->name }}','{{ $appJobs->job_details->job_title }}')">Decline</a>
												</li>
											</ul>
										</div>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@else
						<div class="box">
							<div class="row">
								<div class="col-md-12 col-sm-12 text-center">
									<p>You don't have any Applicants for this job at the moment. </p>
								</div>
							</div>
						</div>
						@endif
						<!-- <div id="loadMore">
							<a href="javascript:void(0)" class="load-more"><i class="fa fa-user"></i>Load More Jobs</a>
						</div> -->
					</div>
				</div>
				
				
			</div>
		</div>
	</section>
	<!--RECENT JOB SECTION END--> 
</div>
<!--MAIN END--> 

<div class="modal fade modal-info" id="changeStatusModal">
	<div class="modal-dialog modal-danger" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background: #369c46;">
				<h5 class="modal-title" id="exampleModalLabel">Confirm Status?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="background: #369c4659;">
				<p>Applicant : <strong id="ApplicantName"></strong></p>
				<p>Are you Sure, you want to <strong class="job_status"></strong> this Application for Job "<strong id="jobTitle"></strong>"?</p>
			</div>
			<div class="modal-footer " style="background: #369c46;">
				<button type="button" class="btn btn-round btn-danger" data-dismiss="modal">Cancel</button>
				<a href="" class="btn btn-round btn-primary">Yes, <span class="job_status"></span> It!</a>
			</div>
		</div>
	</div>
</div>
@endsection
@push('post-scripts')

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>

<script type="text/javascript">
	function change_status(job_id, status, applicant_name, job_title) {

		var conn = "{{ url($employer_type.'/jobs/applications/change-status') }}/"+job_id+"/status/"+status;

		$('#ApplicantName').html(applicant_name);
		$('#jobTitle').html(job_title);
		$('.job_status').html(atob(status) == 1 ? 'Approve' : (atob(status) == 2 ? 'Decline' : 'Pending'));
		$('#changeStatusModal a').attr("href", conn);
	}
</script>
@endpush