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
		<h1>Profile Information</h1>
	</div>
</section>

<!--INNER BANNER END--> 



<!--MAIN START-->

<div id="main"> 
	<!--RECENT JOB SECTION START-->
	<section class="recent-row padd-tb job-detail">
		<div class="container">
			@if($employer_info)
			<div class="row">

				<div class="{{ $jobs->count() > 0 ? 'col-md-9 col-sm-8' : 'col-md-12 col-sm-12' }}">

					<div id="content-area">
						<div class="box">
							<div class="thumb">
								<a href="javascript:void(0)">
									@if($employer_info->image != '')
									<img width="165" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="no-image">
									@else
									<img class="img-thumbnail" width="165" src="{{ asset('images/user.jpg') }}" alt="img">
									@endif
								</a>
							</div>
							<div class="text-col">
								<h2>
									
										{{ $employer_info->organization_name }}
										
										@if($employer->premium == 1)
											<i style="color: #1b8af3;" class="fa fa-check-circle"></i>
										@endif
									
									
								</h2>
								@if($employer->premium != 1)
									<a href="{{ route('company.subscribe-premium') }}" class="btn btn-info btn-sm">Subscribe to Premium</a>
									<br><br>
								@endif
								@if($employer_info->category_id != 0)
								<ul class="company-small">
									<li>
										<strong>Industry:</strong> 
										{{ @$employer_info->employer_category->title }}
									</li>
									<li>
										<strong>Type of Business Entity:</strong> 
										@php
										$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
										@endphp
										@if(isset($ownershipArray[$employer_info->ownership_type]) )
											{{ $ownershipArray[$employer_info->ownership_type] }}
										@else
											<small style="color: red;">Please Update your Profile</small>
										@endif
									</li>

									@if($employer_info->organization_size != '')
									<li>
										<strong>No. of Employees:</strong>
										@php
										$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
										@endphp
										<p>{{ @$OrgSizeArray[$employer_info->organization_size] }}</p>
									</li>
									@endif
									<li><strong>Address :</strong> {{ $employer_info->address }} </li>
								</ul>

								<div class="clearfix"> 
									<a style="margin-right: 10px;" href="{{ url($employer_type.'/posted-jobs') }}" class="btn-style-1">View All Jobs</a>

									<a href="{{ url($employer_type.'/post-job') }}" class="btn-style-1">Post New Job</a>

									<div class="company-social">
										<ul>
											@if($employer_info->twitter != NULL || $employer_info->twitter != '')	
												<li>
													<a href="{{ $employer_info->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a>
												</li>
											@endif

											@if($employer_info->linkedin != NULL || $employer_info->linkedin != '')
												<li>
													<a href="{{ $employer_info->linkedin }}" target="_blank"><i class="fa fa-linkedin"></i></a>
												</li>
											@endif

											@if($employer_info->facebook != NULL || $employer_info->facebook != '')
												<li>
													<a href="{{ $employer_info->facebook }}" target="_blank"><i class="fa fa-facebook-f"></i></a>
												</li>
											@endif
										</ul>
									</div>

								</div>
								@else
								<div class="panel panel-info">
									<div class="panel-body text-center">
										<small style="color: red;">Please Update Your Profile</small>
									</div>
								</div>
								<a href="{{ url($employer_type.'/update-information') }}" class="btn-style-1 text-center">Update Profile</a>
								@endif

							</div>
							<div class="clearfix">
								<h4>Overview</h4>
								{!! $employer_info->about != '' ? $employer_info->about : 'Update Your Profile' !!}
							</div>
						</div>
					</div>
				</div>
				@if($jobs->count() > 0)
				<div class="col-md-3 col-sm-4">
					<aside>
						<div class="sidebar">
							<h2>Jobs Gallery</h2>
							<div class="sidebar-jobs">
								<ul>
									@foreach($jobs as $job)
									<li>
										<a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
										<span><i class="fa fa-map-marker"></i>{{ $job->location->title }} </span>
										<span><i class="fa fa-calendar"></i>{{ date('jS F, Y',strtotime($job->deadline)) }}</span>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
					</aside>
				</div>
				@endif
			</div>
			@else
			<div id="content-area">
				<div class="box">
					<div class="row">
						<div class="col-md-12 col-sm-12 text-center">
							<p>You haven't Updated Your Information. </p>
			                <a href="{{ route($employer_type.'.edit-information') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-database"></i> Update Your Information</a>
			            </div>
	    			</div>
				</div>
			</div>
			
			@endif
		</div>
	</section>
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