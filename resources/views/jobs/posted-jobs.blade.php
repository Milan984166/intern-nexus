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
				<div class="col-md-9 col-sm-8">
					<div id="content-area">
						<h2>Posted Jobs</h2>
						@if($jobs->count() > 0)
						<ul id="myList">
							@foreach($jobs as $job)
							<li>
								<div class="box">
									<div class="thumb">
										<a href="{{ url('job/'.$job->slug) }}">
											<img width="65" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="{{ $job->slug }}">
										</a>
									</div>
									<div class="text-col">
										<div class="hold">
											<h4>
												<a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
											</h4>
											<p>{{ substr(strip_tags($job->job_description),0, 100) }}..</p>
											<a href="javascript:void(0)" class="text">
												<i class="fa fa-map-marker"></i> {{ $job->location->title }}
											</a> 

											<a href="javascript:void(0)" class="text">
												<i class="fa fa-globe"></i>
												{{ $job->job_category->title }}
											</a>

											@php
												$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
											@endphp
											<a href="javascript:void(0)" class="text">
												<i class="fa fa-clock-o"></i>
												{{ $employmentTypeArray[$job->employment_type] }}
											</a>

											<a href="javascript:void(0)" class="text">
                                                <i class="fa fa-calendar"></i> {{ date('jS F, Y', strtotime($job->deadline)) }} 

                                                @php
                                                    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$job->deadline);

                                                    $from = \Carbon\Carbon::now();
                                                    $diff_in_days = $from->diff($to,false);
                                                @endphp

                                                <i style="color:{{$diff_in_days->format('%R') == '-' ? 'red' : 'green'}};">({{ $diff_in_days->format('%R') == '-' ? ' Expired' : $diff_in_days->format('%d days, %h hours').' Remaining' }} )</i>
                                            </a> 
										</div>
									</div>
									<strong class="price">
										<i class="fa fa-money"></i> 
										@if($job->salary_type == 1)
											${{ $job->min_salary }} - ${{ $job->max_salary }}
										@elseif($job->salary_type == 2)
											${{ $job->min_salary }}
										@elseif($job->salary_type == 3)										
											Negotiable
										@endif
									</strong>
									<a style="margin-left: 10px;" href="{{ route($employer_type.'.edit-posted-jobs',['slug' => base64_encode($job->slug)]) }}" class="btn-1 btn-style-1">
                    		        	<i class="fa fa-edit"></i> Edit
                    		        </a>
									<a style="margin-left: 10px;" href="{{ route($employer_type.'.applications',['job_slug' => $job->slug]) }}" class="btn-1 btn-color-1">
                    		        	<i class="fa fa-edit"></i> Applicants ({{ $job->applicants->count() }})
                    		        </a>
								</div>
							</li>
							@endforeach
						</ul>
						@else
						<div class="box">
							<div class="row">
    							<div class="col-md-12 col-sm-12 text-center">
    								<p>You don't have posted any jobs at the moment. </p>
    				                <a href="{{ route($employer_type.'.post_job') }}" class="btn btn-sm btn-primary btn-round" title=""><i class="fa fa-database"></i> Post a Job, now!</a>
    				            </div>
                			</div>
						</div>
						@endif
						<div id="loadMore">
							<a href="javascript:void(0)" class="load-more"><i class="fa fa-user"></i>Load More Jobs</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<aside>
						<div class="sidebar">
							<h2>Company Details</h2>

							<div class="box">
								<div class="thumb">
									<a href="{{ route($employer_type.'.profile') }}">
										<img src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="img">
									</a>
								</div>

								<div class="text-box">

									<h4><a href="{{ route($employer_type.'.profile') }}">{{ $employer_info->organization_name }}</a></h4>

									<p>{{ substr(strip_tags($employer_info->about),0, 200) }}..</p>

									<strong>Industry</strong>
									<p>{{ $employer_info->employer_category->title }}</p>

									<strong>Type of Business Entity</strong>
									@php
									$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
									@endphp
									<p>{{ $ownershipArray[$employer_info->ownership_type] }}</p>

									@if($employer_info->organization_size != '')
									<strong>No. of Employees</strong>
									@php
										$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
									@endphp
									<p>{{ @$OrgSizeArray[$employer_info->organization_size] }}</p>
									@endif

									<strong>Location</strong>
									<p>{{ $employer_info->address }} </p>

								</div>

							</div>
						</div>
					</aside>
				</div>
				@if($job_categories->count() > 0)
				<div class="col-md-3 col-sm-4">
                    <h2>Jobs By Categories</h2>
                    <aside>
                        <div class="sidebar">
                            <div class="sidebar-jobs">
                                <ul>
                                    @foreach($job_categories as $key => $job_category)
                                    <li>
                                        <a href="{{ url('category/'.$job_category->slug) }}">{{ $job_category->title }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
				@endif
			</div>
		</div>
	</section>
	<!--RECENT JOB SECTION END--> 
</div>
<!--MAIN END--> 
@endsection
@push('post-scripts')

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>
@endpush