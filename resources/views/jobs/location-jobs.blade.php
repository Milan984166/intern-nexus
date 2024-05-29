@extends('layouts.app')
@section('title',  $location->title )
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>{{ $location->title }}</h1>
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
						<div>
							@if(isset($_GET['sort']))
							{{ $jobs->appends(['sort' => $_GET['sort']])->links() }}
							@else
							{{ $jobs->links() }}
							@endif
						</div>
						@if($jobs->count() > 0)
						<ul id="myList">
							@foreach($jobs as $job)
							<li>
                                <div class="box">
                                    <div class="thumb">
                                        <a href="{{ url('job/'.$job->slug) }}">
                                            @if($job->user->employer_info->image != '')
											<img width="65" src="{{ asset('storage/employers/company-info/thumbs/small_'.$job->user->employer_info->image) }}" alt="img">
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
                                    <a href="{{ url('job/'.$job->slug) }}" class="btn-1 btn-color-1 ripple">View Details</a>
                                </div>
                            </li>
							@endforeach
						</ul>
						<div id="loadMore">
							<a href="javascript:void(0)" class="load-more"><i class="fa fa-user"></i>Load More Jobs</a>
						</div>
						@else
						<div class="box">
							<div class="row">
    							<div class="col-md-12 col-sm-12 text-center">
    								<p>No Jobs posted at the moment. </p>
    				            </div>
                			</div>
						</div>
						@endif
					</div>
				</div>
				@if($jobs->count() > 0)
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
									
									@if(($key+1) >= $jobs->count()*2 )
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
@endpush