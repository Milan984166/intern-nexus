@extends('layouts.app')
@section('title',$job->job_title)
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">
	<div class="container">
		<h1>{{ $job->job_title }}</h1>
	</div>
</section>

<!--INNER BANNER END--> 



<!--MAIN START-->

<div id="main"> 

	<!--RECENT JOB SECTION START-->

	<section class="recent-row padd-tb job-detail">
		<div class="container">
			<div class="row">
				<div class="col-md-9 col-sm-8">
					<div id="content-area">
						<div class="box">
							<div class="thumb">
								@if($employer_info->image != '')
								<img width="165" class="img-thumbnail" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="{{ $job->slug }}">
								@else
								<img width="165" class="img-thumbnail" src="{{ asset('images/user.jpg') }}">
								@endif
							</div>
							<div class="text-col">
								<h2><a href="#">{{ $job->job_title }}</a></h2>
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
                                        $diffSign = $diff_in_days->format('%R');
                                    @endphp

                                    <!-- <i style="color:{{$diffSign == '-' ? 'red' : 'green'}};">({{ $diffSign == '-' ? ' Expired' : $diff_in_days->format('%d days, %h hours').' Remaining' }} )</i> -->
                                </a> 
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

								<div class="clearfix">
                                    
                                    @if( Auth::check())

                                    	@php
											$alreadyApplied = \App\JobApply::where([['job_id', $job->id],['jobseeker_id', Auth::user()->id]])->first();
										@endphp

										@if($diffSign != '-' && Auth::user()->role == 2 && !isset($alreadyApplied))

											<a href="{{ route('job-seeker.apply_job',['slug' => $job->slug]) }}" class="btn-style-1">Apply for this Job</a> 

										@endif

										@if(isset($alreadyApplied))
											@switch($alreadyApplied->status)
												
												@case(0)
												<a href="javascript:void(0)" disabled class="btn-style-1">Waiting for Approval</a> 
												@break

												@case(1)
												<a href="javascript:void(0)" disabled class="btn-style-1">Approved</a> 


												@break

												@case(2)
												<a href="javascript:void(0)" disabled class="btn-style-1">Declined</a> 
												@break

											@endswitch
											
										@endif
										<a href="{{ route('job-seeker.watchlist',['slug' => $job->slug]) }}" style="margin-left: 10px;" class="btn-style-1">Add To Watchlist</a> 
									@else
										@if($diffSign != '-')
										<a href="{{ route('front_login') }}" class="btn-style-1">Login to Apply Job</a> 
										@endif
									@endif
								</div>

							</div>
							<div class="clearfix">
								<h4>Overview</h4>
								{!! $job->job_description !!}
								<hr>
								<h4>Job Information</h4>
								<p>
									Job level:
									<strong>{{ $job->job_category->title }}</strong>
								</p>

								<p>
									@php
										$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
									@endphp
									Job Category:
									<strong>{{ $lookingForArray[$job->job_level] }}</strong>
								</p>
								<p>
									No. of Vacancy(s): 
									<strong>{{ $job->no_of_vacancy }}</strong>
								</p>
								<p>
									@php
                    		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
                    		        @endphp
									Employment Type: 
									<strong>{{ $employmentTypeArray[$job->employment_type] }}</strong>
								</p>
								<p>
									Job Location:
									<strong>{{ $job->location->title }}</strong>
								</p>
								<p>
									Offered Salary:
									<strong>
										@if($job->salary_type == 1)
										    ${{ $job->min_salary }} - ${{ $job->max_salary }}
										@elseif($job->salary_type == 2)
										    ${{ $job->min_salary }}
										@elseif($job->salary_type == 3)                                     
										    Negotiable
										@endif
									</strong>
								</p>

								<p>
									Deadline: 
									<strong>{{ date('jS F, Y', strtotime($job->deadline)) }} </strong>
								</p>

								<hr>
								<h4>Requirements</h4>
								<p>
									Education Level: 
									@php
										$educationLevel = array('1' => 'Others', '2' => 'SLC', '3' => 'Intermediate' , '4' => 'Bachelor', '5' => 'Master', '6' => 'Ph.D.');
									@endphp
									<strong>{{ $educationLevel[$job->education_level] }}</strong>
								</p>

								<p>
									Experience Required: 
									@php
                    		    		$experienceTypes = array('1' => 'More than', '2' => 'Less than', '3' => 'More than or equals to' , '4' => 'Less than or equals to', '5' => 'Equals to');
                    		    	@endphp
									<strong>{{ $experienceTypes[$job->experience_type] }} {{ $job->experience_year }} {{ $job->experience_year == 1 ? 'Year' : 'Years' }}</strong>
								</p>
								@php
									$skills = json_decode($job->skill_ids);
								@endphp
								@if(count($skills) > 0)
								<p>
									Professional Skills: 
									@for($i=0; $i < count($skills); $i++)
                                    <span class="badge badge-soft-secondary">{{ \App\Skill::where('id',$skills[$i])->first()->title }}</span>&nbsp;
                                    @endfor
                                </p>
                                @endif
                                <hr>
                                @if($job->benefits != '')
                                <h4>Job Benefits</h4>
								{!! $job->benefits !!}
								<hr>
								@endif
								@if( Auth::check())
									@if($diffSign != '-' && Auth::user()->role == 2 && !isset($alreadyApplied))

										<a href="{{ route('job-seeker.apply_job',['slug' => $job->slug]) }}" class="btn-style-1">Apply for this Job</a> 

									@endif
								@else
									@if($diffSign != '-')
									<a href="{{ route('front_login') }}" class="btn-style-1">Login to Apply Job</a> 
									@endif
								@endif
						</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<aside>
						<div class="sidebar">
							<div class="box">
								<div class="text-box">

									<h4><a href="{{ route('company_jobs',['slug' => $job->user->employer_info->slug]) }}">{{ $employer_info->organization_name }}</a></h4>

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

							<h2>Hot Jobs</h2>
							@if($featured_jobs->count() > 0)
							<div class="sidebar-jobs">

								<ul>

									@foreach($featured_jobs as $job)
									<li>
										<a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
										<span><i class="fa fa-map-marker"></i>{{ $job->location->title }} </span>
										<span><i class="fa fa-calendar"></i>{{ date('jS F, Y',strtotime($job->deadline)) }}</span>
									</li>
									@endforeach
								</ul>
							</div>
							@endif

						</div>

					</aside>

				</div>

			</div>

		</div>

	</section>

	<!--RECENT JOB SECTION END--> 

</div>

<div class="modal fade " id="viewContactDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <label><span class="viewJobTitle"></span></label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page">
                    <div class="row">
                        <div class="col-md-6" id="showHideContactEmail">
                            <label class="text-muted"><i class="fa fa-envelope"></i> Email: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewContactEmail"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideContactPhone">
                            <label class="text-muted"><i class="fa fa-phone"></i> Phone: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewContactPhone"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideMessengerLink">
                            <label class="text-muted"><i class="fa fa-comments"></i> Messenger: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewMessengerLink"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideViberNumber">
                            <label class="text-muted"><i class="fa fa-comments"></i> Viber: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewViberNumber"></a></p>
                        </div>
                        <div class="col-md-6" id="showHideWhatsappNumber">
                            <label class="text-muted"><i class="fa fa-comments"></i> WhatsApp: </label>
                            <p><a href="" target="_blank" class="mb-0" id="viewWhatsappNumber"></a></p>
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

<!--MAIN END--> 
@endsection
@push('post-scripts')
<script src="{{ asset('frontend/js/form.js') }}"></script>

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script type="text/javascript">
	function view_contact_details(id,job_title) {
		var email = $('#view' + id).attr('data-contact_email');
		var phone = $('#view' + id).attr('data-contact_phone');
		var messenger_link = $('#view' + id).attr('data-messenger_link');
		var viber_number = $('#view' + id).attr('data-viber_number');
		var whatsapp_number = $('#view' + id).attr('data-whatsapp_number');

		$(".viewJobTitle").html(job_title);

		if (email != '') {
			$("#showHideContactEmail").show();
			$("#viewContactEmail").html(email);
			$("#viewContactEmail").attr('href','mailto:'+email);
		}else{
			$("#showHideContactEmail").hide();
		}

		if (phone != '') {
			$("#showHideContactPhone").show();
			$("#viewContactPhone").html(phone);
			$("#viewContactPhone").attr('href','tel:'+phone.replace(/[^0-9,+]/,''));
		}else{
			$("#showHideContactPhone").hide();
		}

		if (messenger_link != '') {
			$("#showHideMessengerLink").show();
			$("#viewMessengerLink").html(messenger_link);
			$("#viewMessengerLink").attr('href',messenger_link);
		}else{
			$("#showHideMessengerLink").hide();
		}

		if (viber_number != '') {
			$("#showHideViberNumber").show();
			$("#viewViberNumber").html(viber_number);
			$("#viewViberNumber").attr('href','viber://chat?number='+viber_number.replace(/[^0-9,+]/,''))
		}else{
			$("#showHideViberNumber").hide();
		}

		if (whatsapp_number != '') {
			$("#showHideWhatsappNumber").show();
			$("#viewWhatsappNumber").html(whatsapp_number);

			if ($( window ).width() < 769) {
				$("#viewWhatsappNumber").attr('href','https://api.whatsapp.com/send?phone='+whatsapp_number.replace(/[^0-9,+]/,''))
			}else{
				$("#viewWhatsappNumber").attr('href','https://web.whatsapp.com/send?phone='+whatsapp_number.replace(/[^0-9,+]/,''))
			}
			
		}else{
			$("#showHideWhatsappNumber").hide();
		}
	}
</script>
@endpush