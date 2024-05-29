@extends('layouts.app')
@section('title','Jobs')
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>Jobs </h1>
	</div>
</section>

<!--INNER BANNER END--> 




<!--POPULAR JOB CATEGPRIES START-->
@if($featured_jobs->count() > 0)
<section class="popular-job-caregries popular-categories candidates-listing">
	<div class="holder">
		<div class="container">
			<h4>Featured Jobs</h4>
			<div id="popular-job-slider" class="owl-carousel owl-theme">
				@foreach($featured_jobs as $key => $featJob)
				<a href="{{ url('job/'.$featJob->slug) }}">
					<div class="item">
						<div class="box"> 
							@if($featJob->user->employer_info->image != '')
							<img width="90" src="{{ asset('storage/employers/company-info/thumbs/small_'.$featJob->user->employer_info->image) }}" alt="img">
							@else
							<img width="90" src="{{ asset('images/user.jpg') }}" alt="img">
							@endif
							<h4><a href="{{ url('job/'.$featJob->slug) }}">{{ $featJob->job_title }}</a></h4>
							<p><a href="{{ route('company_jobs',['slug' => $featJob->user->employer_info->slug]) }}">{{ $featJob->user->employer_info->organization_name }}</a></p>

							<p>
							<a href="{{ route('location_jobs',['slug' => $featJob->location->slug]) }}" class="text">
	                            <i class="fa fa-map-marker"></i> {{ $featJob->location->title }}
	                        </a> 
	                        </p>

	                        <p>
	                        <a href="{{ route('category_jobs',['slug' => $featJob->job_category->slug]) }}" class="text">
	                            <i class="fa fa-globe"></i>
	                            {{ $featJob->job_category->title }}
	                        </a>
	                        </p>

	                        @php
	                        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
	                        @endphp
	                        <p>
	                        <a href="javascript:void(0)" class="text">
	                        	<i class="fa fa-clock-o"></i>
	                        	{{ $employmentTypeArray[$featJob->employment_type] }}
	                        </a>
	                        </p>

						</div>
					</div>
				</a>
				@endforeach
			</div>
		</div>
	</div>

</section>
@endif
<!--POPULAR JOB CATEGPRIES END--> 



<!--MAIN START-->

<div id="main"> 
	<!--SEARCH BAR SECTION START-->
	<section class="candidates-search-bar">
		<div class="container">
			<form action="{{ route('job_search') }}" method="GET" autocomplete="off">
				<div class="row">
					<div class="col-md-6 col-sm-6">
                        <div class="auto-complete-div">
                            <input type="text" name="location"  placeholder="Enter Location" id="inputLocations">
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-5">
                        <div class="auto-complete-div">
                            <input type="text" name="category"  placeholder="Enter Category" id="inputCategories">
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-1">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
				</div>
			</form>
		</div>
	</section>
	<!--SEARCH BAR SECTION END--> 
	<!--RECENT JOB SECTION START-->
	<section class="recent-row padd-tb">
		<div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-8">
					<div class="check-filter">
						<form action="#">
							<ul>
								<li>
									<input id="id1" type="checkbox" />
									<label>All Jobs</label>
								</li>
								<li>
									<input id="id2" type="checkbox" />
									<label>Part Time</label>
								</li>
								<li>
									<input id="id3" type="checkbox" />
									<label>Full Time</label>
								</li>
								<li>
									<input id="id4" type="checkbox" />
									<label>Freelance</label>
								</li>
								<li>
									<input id="id5" type="checkbox" />
									<label>Contract</label>
								</li>
								<li>
									<input id="id6" type="checkbox" />
									<label>Internship</label>
								</li>
							</ul>
						</form>
					</div>
					<div id="content-area">
						<h2>Latest Jobs</h2>
						
						<ul id="myList">
							@foreach($jobs as $job)
							<li>
                                <div class="box">
                                    <div class="thumb">
                                        <a href="{{ url('job/'.$job->slug) }}">
                                            @if($job->user->employer_info->image != '')
											<img width="65" class="img-thumbnail" src="{{ asset('storage/employers/company-info/thumbs/small_'.$job->user->employer_info->image) }}" alt="img">
											@else
											<img width="65" class="img-thumbnail" width="165" src="{{ asset('images/user.jpg') }}" alt="img">
											@endif
                                        </a>
                                    </div>
                                    <div class="text-col">
                                        <div class="hold">
                                            <h4>
                                                <a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
                                            </h4>
                                            <p><a href="{{ route('company_jobs',['slug' => $job->user->employer_info->slug]) }}">{{ $job->user->employer_info->organization_name }}</a></p>

                                            <a href="{{ route('location_jobs',['slug' => $job->location->slug]) }}" class="text">
                                                <i class="fa fa-map-marker"></i> {{ $job->location->title }}
                                            </a> 

                                            <a href="{{ route('category_jobs',['slug' => $job->job_category->slug]) }}" class="text">
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
                                            	<i class="fa fa-eye"></i>
                                            	{{ $job->views}} Views
                                            </a>

                                            <a href="javascript:void(0)" class="text">
                                                <i class="fa fa-calendar"></i> {{ date('jS F, Y', strtotime($job->deadline)) }} 

                                                @php
                                                    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$job->deadline);

                                                    $from = \Carbon\Carbon::now();
                                                    $diff_in_days = $from->diff($to,false);
                                                @endphp

                                                <!-- <i style="color:{{$diff_in_days->format('%R') == '-' ? 'red' : 'green'}};">({{ $diff_in_days->format('%R') == '-' ? ' Expired' : $diff_in_days->format('%d days, %h hours').' Remaining' }} )</i> -->
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
                                    
                                    <a href="{{ url('job/'.$job->slug) }}" class="btn-1 btn-color-1 ripple">View Details</a>
                                </div>
                            </li>
							@endforeach
						</ul>
						<div id="loadMore"> <a href="javascript:void(0)" class="load-more"><i class="fa fa-user"></i>Load More Jobs</a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<h2>Companies</h2>
					<aside>
						<div class="sidebar">
							
							<div class="sidebar-jobs">
								<ul>
									@foreach($companies as $company)
									<li> 
										<a href="{{ route('company_jobs',['slug' => $company->employer_info->slug]) }}">
											{{ $company->employer_info->organization_name }}
										</a> 
										<span><i class="fa fa-map-marker"></i>{{ $company->employer_info->address }} </span> 
										<span><i class="fa fa-globe"></i>{{$company->employer_info->employer_category->title}} </span>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
					</aside>
				</div>
			</div>
		</div>
	</section>
	<!--RECENT JOB SECTION END--> 
</div>
<!--MAIN END--> 
@endsection
@push('post-scripts')
<script src="{{ asset('frontend/js/form.js') }}"></script>

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>
@endpush