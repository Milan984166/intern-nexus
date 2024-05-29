@extends('layouts.app')
@section('title',$employer->name." - Subscribe Premium")
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>Subscribe</h1>
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
							
							<div class="text-col">
								<h2>
									<a href="javascript:void(0)">Subscribe to Premium Listings for Enhanced Visibility!</a>
								</h2>
								
							</div>
							<div class="clearfix">
								<h4>Overview</h4>
								<p>
                                    Unlock unparalleled opportunities for your internship listings by subscribing to our Premium Listings package.
                                    Elevate your company's visibility and attract top-tier talent with enhanced features tailored to meet your
                                    recruitment needs.
                                </p>

                                <h3>Subscription Benefits:</h3>

                                <p><strong>1. Prime Placement:</strong><br>
                                    Featured at the top of relevant internship searches, ensuring maximum visibility among potential interns.</p>

                                <p><strong>2. Increased Exposure:</strong><br>
                                    Stand out in the crowded internship landscape and capture the attention of our diverse user base.</p>

                                <p><strong>3. Comprehensive Analytics:</strong><br>
                                    Gain insights into the performance of your listings with detailed analytics, helping you refine your recruitment
                                    strategy.</p>

                                <p><strong>4. Priority Support:</strong><br>
                                    Enjoy dedicated support from our team to address any queries and optimize your premium listing for optimal
                                    results.</p>

                                    <p><strong>Subscription Fee: $69.99</strong></p>

                                <a class="btn btn-primary" href="{{ route('company.paywithpaypal') }}" onclick="subscribe()">Subscribe Now</a>

                                <script>
                                    function subscribe() {
                                        // Add subscription logic here
                                        alert('Are you Sure?');
                                    }
                                </script>
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