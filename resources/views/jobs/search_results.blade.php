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
		<h1>Search Results</h1>
	</div>
</section>

<!--INNER BANNER END--> 



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
				@if($search_results->count() > 0)
					<div class="col-md-9 col-sm-8">
						
						<div id="content-area">
							<div>
								@if(isset($_GET['sort']))
								{{ $search_results->appends(['sort' => $_GET['sort']])->links() }}
								@else
								{{ $search_results->links() }}
								@endif
							</div>
							<ul id="myList">
								@foreach($search_results as $job)
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

					@if($search_results->count() > 0)
					<div class="col-md-3 col-sm-4">
						<h2>Companies</h2>
						<aside>
							<div class="sidebar">
								
								<div class="sidebar-jobs">
									<ul>
										@foreach($companies as $key => $company)
										<li> 
											<a href="{{ route('company_jobs',['slug' => $company->employer_info->slug]) }}">
												{{ $company->employer_info->organization_name }}
											</a> 
											<span><i class="fa fa-map-marker"></i>{{ $company->employer_info->address }} </span> 
											<span><i class="fa fa-globe"></i>{{$company->employer_info->employer_category->title}} </span>
										</li>
										
										@if(($key+1) >= $search_results->count()*2 )
											<li>
												<a href="{{ route('companies') }}" class="btn-style-1">View All Companies</a> 
											</li>
											@php break; @endphp

										@endif
										
										@endforeach
									</ul>
								</div>
							</div>
						</aside>
					</div>
					@endif

				@else
					<div class="col-md-12 col-sm-9">
						<div class="panel panel-info">
							<div class="panel-body text-center">
								<h3>Sorry, No Match Found.</h3>
							</div>
						</div>
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
<script src="{{ asset('frontend/js/form.js') }}"></script>

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>
@endpush