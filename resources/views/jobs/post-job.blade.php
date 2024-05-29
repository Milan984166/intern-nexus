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
		<h1>{!! $job_id != 0 ? 'Edit "<small style="color: white;">'.$job->job_title.'</small>"' : 'Post A Job' !!}</h1>
	</div>
</section>
<!--INNER BANNER END--> 

<!--MAIN START-->

<div id="main">
	<section class="recent-row padd-tb job-detail">
		<div class="container">
			<div class="row">
				<div class="col-md-9 ">
					<div id="content-area">
						<div class="box">
							<div class="thumb">
								<a href="{{ route($employer_type.'.profile') }}">
									<img width="165" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="no-image">
								</a>
							</div>
							<div class="text-col">
								<h2>
									<a href="{{ route($employer_type.'.profile') }}">{{ $employer_info->organization_name }}</a>
								</h2>


								<ul class="company-small">
									<li>
										<strong>Industry:</strong> 
										{{ $employer_info->employer_category->title }}
									</li>
									<li>
										<strong>Type of Business Entity:</strong> 
										@php
										$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
										@endphp
										{{ $ownershipArray[$employer_info->ownership_type] }}
									</li>

									@if($employer_info->organization_size != '')
									<li>
										<strong>No. of Employees:</strong>
										@php
										$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
										@endphp
										<p>{{ $OrgSizeArray[$employer_info->organization_size] }}</p>
									</li>
									@endif
									<li><strong>Address :</strong> {{ $employer_info->address }} </li>


								</ul>

								<a href="{{ url($employer_type.'/posted-jobs') }}" class="text"><em>(View All Jobs)</em></a>
								<div class="clearfix"> 

									<div class="company-social">
										<ul>
											<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
											<li><a href="#"><i class="fa fa-twitter"></i></a></li>
											<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
											<li><a href="#"><i class="fa fa-facebook-f"></i></a></li>
										</ul>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<aside>
						<div class="sidebar">
							<div class="sidebar-jobs">
								<ul>
									
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.profile') }}"> Profile</a>
									</li>
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.posted-jobs') }}"> Posted Jobs</a>
									</li>
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.post_job') }}"> Post New Job</a>
									</li>
									<li>
										<a class="btn-style-1" href="#"> Edit Profile</a>
									</li>
								</ul>
							</div>
						</div>
					</aside>
				</div>
			</div>
		</div>
	</section>

	<!--RESUME FORM START-->
	<section class="resum-form padd-tb">
		<div class="container">
			<form method="POST" action="{{ $job_id != 0 ? route($employer_type.'.update-job') : route($employer_type.'.add-job') }}" enctype="multipart/form-data">
				@csrf
				<input type="hidden" id="JobId" name="job_id" value="{{ base64_encode($job_id) }}">
				<div class="panel panel-info">
					<div class="panel-body">
					
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<label><i class="fa fa-database"></i> Job Title* </label>
								<input type="text" name="job_title" class="form-control" required value="{{ old('job_title') ? old('job_title') : ( $job_id != 0 ? $job->job_title : '' ) }}" placeholder="eg: Java Developer, HRM..">
								<hr>	
							</div>

							<div class="col-md-6 col-sm-6">
		            			<label>
		    		                <i class="fa fa-building"></i> &nbsp; Job Level*
		    		            </label>
		            		    
	            		    	@php
	            		    		$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
	            		    	@endphp
	            		        <select class="ui dropdown" name="job_level" required style="width: 100%;">

	            		        	<option selected disabled>Select Job Level</option>

	            		        	@foreach($lookingForArray as $key => $look)
	                                	<option {{ $job_id != 0 && $job->job_level == $key ? 'selected' : (old('job_level') == $key ? 'selected' : '' ) }} value='{{ $key }}'>{{ $lookingForArray[$key] }}</option>
	                                @endforeach
	                                
	                            </select>
	            		    
		            		    <hr>
		            		</div>
		            		<div class="clearfix"></div>
		            		@php
								$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

		                    @endphp
		            		<div class="col-md-6 col-sm-6">
								<label>
		    		                <i class="fa fa-building"></i> &nbsp; Job Category*
		    		            </label>
		            		    
	            		        <select class="ui dropdown" name="job_category_id" required id="JobCategory" style="width: 100%;">

	            		        	<option value="0" selected disabled>Select Job Category</option>

	            		        	@foreach($jobCategories as $jobCat)
	                                	<option {{ $job_id != 0  && $job->job_category_id == $jobCat->id ? 'selected' : (old('job_category_id') == $jobCat->id ? 'selected' : '' ) }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
	                                @endforeach
	                            </select>
	            		    
		            		    <hr>
							</div>

							<div class="col-md-6 col-sm-6">
								<label>
		    		                <i class="fa fa-building"></i> &nbsp; Skills*
		    		            </label>
		            		    <div class="mb-3 multiselect_div" id="SkillSelect">

		            		        <select class="form-control" required>
		            		        	<option selected disabled>Select Job Category First</option>
		                            </select>
		            		    </div>
		            		    <hr>
							</div>

							<div class="col-md-6 col-sm-6">
		        		        <label><i class="fa fa-building"></i> &nbsp; Employment Type*  </label>
		        		        @php
		        		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
		        		        @endphp
	            		        <select class="ui dropdown" name="employment_type" required>

	                                @foreach($employmentTypeArray as $key => $emptype)
	                                <option value="{{$key}}" {{ $job_id != 0 && $job->employment_type == $key  ? 'selected' : (old('employment_type') == $key ? 'selected' : '' ) }}>{{ $employmentTypeArray[$key] }}</option>

	                                @endforeach
	                            </select>
		        		        <hr>
		            		</div>

		            		<div class="col-md-6 col-sm-6">
								<label><i class="fa fa-users"></i> No. of Vacancy* </label>
								<input type="number" min="1" name="no_of_vacancy" class="form-control" value="{{ old('no_of_vacancy') ? old('no_of_vacancy') : ($job_id != 0 ? $job->no_of_vacancy : 1) }}" required>
								<hr>
							</div>

		            		<div class="col-md-6 col-sm-6">
								<label><i class="fa fa-calendar"></i> Deadline: </label>
								<input type="text" name="deadline" class="form-control" value="{{ old('deadline') ? old('deadline') : ($job_id != 0 ? date('Y-m-d',strtotime($job->deadline)) : '') }}" data-provide="datepicker" data-date-start-date="{{ $job_id != 0 ? date('Y-m-d',strtotime($job->deadline)) : 'tomorrow' }}" data-date-autoclose="true" class="form-control" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" required>
								<hr>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-body pb-0 mb-3">
					
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div>
									<label style="margin-bottom:12px;"><i class="fa fa-money"></i> &nbsp;Offered Salary</label>
								</div>


		            			<label class="chekbox-inline" for="Range">
		            			  	<input class="form-check-input d-none" type="radio" id="Range" name="salary_type" value="1" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 1 ? 'checked' : (old('salary_type') == 1 ? 'checked' : '' )}}> Range <small style="font-size: 10px;">(Provide the minimum to maximum salary in range.)</small>
		            			</label><br>

		            			<label class="chekbox-inline" for="Fixed">
		            			  	<input class="form-check-input d-none" type="radio" id="Fixed" name="salary_type" value="2" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 2 ? 'checked' : (old('salary_type') == 2 ? 'checked' : '' )}}> Fixed <small style="font-size: 10px;">(Provide the minimum offered salary.)</small>
		            			</label><br>

		            			<label class="chekbox-inline" for="Negotiable">
		            			  	<input class="form-check-input d-none" type="radio" id="Negotiable" name="salary_type" value="3" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 3 ? 'checked' : (old('salary_type') == 3 ? 'checked' : '' )}}> Negotiable 
		            			</label>

		            		</div>

							<div class="col-md-6 col-sm-6 salary_div">
								<span id="MinSalary">
									<label>
										<i class="fa fa-money"></i> Minimum Salary 
									</label>
									<input id="MinSalaryInput" type="text" name="min_salary" class="form-control" required value="{{ old('min_salary') ? old('min_salary') : ( $job_id != 0 ? $job->min_salary : '' ) }}" placeholder="$25000">
									<hr>
								</span>

								<span id="MaxSalary">
									<label>
										<i class="fa fa-money"></i> Maximum Salary 
									</label>
									<input id="MaxSalaryInput" type="text" name="max_salary" class="form-control" required value="{{ old('max_salary') ? old('max_salary') : ( $job_id != 0 ? $job->max_salary : '' ) }}" placeholder="$25000">
									<hr>
								</span>	
		            		</div>
		            	</div>
		            	<hr>
		            	<div class="row">

		                    <div class="col-md-6 col-sm-6">
		            			<label>
		    		                <i class="fa fa-building"></i> &nbsp; Education Level*
		    		            </label>
		            		    
	            		    	@php
	            		    		$educationLevel = array('1' => 'Others', '2' => 'SLC', '3' => 'Intermediate' , '4' => 'Bachelor', '5' => 'Master', '6' => 'Ph.D.');
	            		    	@endphp
	            		        <select class="ui dropdown" name="education_level" required>

	            		        	<option selected disabled>-------------------------</option>

	            		        	@foreach($educationLevel as $key => $look)
	                                <option {{ $job_id != 0 && $job->education_level == $key ? 'selected' : (old('education_level') == $key ? 'selected' : '' ) }} value='{{ $key }}'>{{ $educationLevel[$key] }}</option>
	                                @endforeach
	                                
	                            </select>
	            		    
		            		</div>

		            		<div class="col-md-6 col-sm-6">
		            			<label style="width: 100%">
		    		                <i class="fa fa-building"></i> &nbsp; Experience(In Years)
		    		            </label>
	            		    	@php
	            		    		$experienceTypes = array('1' => 'More than', '2' => 'Less than', '3' => 'More than or equals to' , '4' => 'Less than or equals to', '5' => 'Equals to');
	            		    	@endphp
	            		        <select class="form-control" name="experience_type" style="width: 50%;">

	            		        	<option selected disabled>-------------------------</option>

	            		        	@foreach($experienceTypes as $key => $look)
	                                <option {{ $job_id != 0 && $job->experience_type == $key ? 'selected' : (old('experience_type') == $key ? 'selected' : (old('experience_type') == $key ? 'selected' : '') ) }} value='{{ $key }}'>{{ $experienceTypes[$key] }}</option>
	                                @endforeach
	                                
	                            </select>
	                            <select class="form-control" name="experience_year" style="width: 50%;">

	            		        	@for($i=1; $i<=50; $i++)
	                                <option {{ $job_id != 0 && $job->experience_year == $i ? 'selected' : (old('experience_year') == $i ? 'selected' : '') }} value='{{ $i }}'>{{ $i }} {{ $i == 1 ? 'Year' : 'Years' }}</option>
	                                @endfor
	                                
	                            </select>
	            		   
		            		</div>

		            		<div class="col-md-6 col-sm-6">
		            			<label>
		    		                <i class="fa fa-building"></i> &nbsp; Location Preference*
		    		            </label>

	            		    	@php
	            		    		$locationsDB = \App\Location::where('display',1)->orderBy('order_item')->get();
	            		    	@endphp

	            		        <select class="ui dropdown" name="location_id" required>

	            		        	<option selected disabled>Select Locaiton</option>

	                                @foreach($locationsDB as $loc)
	                                	<option {{ $job_id != 0 && $loc->id == $job->location_id ? 'selected' : (old('location_id') == $loc->id ? 'selected' : '' ) }} value="{{ $loc->id }}">{{ $loc->title }}</option>
	                                @endforeach

	                            </select>
		            		</div>

		            		<div class="col-md-12 col-sm-12">
		            			<hr>
		            			<strong class="text-muted"><i class="fa fa-comments"></i> Contact Via </strong>
		            			<small class="pull-right text-muted">Leave Blank if not necessary!<br>The Contact will be shown once you apporve the applied jobs</small>
		            			<hr>
		            			<div class="col-md-6 col-sm-6">
									<label><i class="fa fa-comments"></i> Contact Email* </label>
									<input type="text" name="contact_email" class="form-control" value="{{ old('contact_email') ? old('contact_email') : ( $job_id != 0 ? $job->contact_email : '' ) }}" placeholder="eg: hello@intern-nexus.com">
									<hr>	
								</div>
								<div class="col-md-6 col-sm-6">
									<label><i class="fa fa-comments"></i> Contact Phone* </label>
									<input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') ? old('contact_phone') : ( $job_id != 0 ? $job->contact_phone : '' ) }}" placeholder="eg: 9876543210">
									<hr>	
								</div>
								<div class="col-md-4 col-sm-6">
									<label><i class="fa fa-comments"></i> Messenger Link* </label>
									<input type="text" name="messenger_link" class="form-control" value="{{ old('messenger_link') ? old('messenger_link') : ( $job_id != 0 ? $job->messenger_link : '' ) }}" placeholder="eg: https://m.me/username">
									<hr>	
								</div>
								<div class="col-md-4 col-sm-6">
									<label><i class="fa fa-comments"></i> Viber Number </label>
									<input type="text" name="viber_number" class="form-control" value="{{ old('viber_number') ? old('viber_number') : ( $job_id != 0 ? $job->viber_number : '' ) }}" placeholder="eg: 9876543210">
									<hr>	
								</div>
								<div class="col-md-4 col-sm-6">
									<label><i class="fa fa-comments"></i> WhatsApp Number* </label>
									<input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') ? old('whatsapp_number') : ( $job_id != 0 ? $job->whatsapp_number : '' ) }}" placeholder="eg: 9876543210">
									<hr>	
								</div>
							</div>

		            	</div>
		            </div>
		        </div>
		        <div class="panel panel-info">
		            <div class="panel-body">
					
						<div class="row">
							<div class="col-md-12">
		            			<label>
		    		                <i class="fa fa-building"></i> &nbsp; Job Description*
		    		            </label>
		    		            <div class="text-editor-box">
			    		            <textarea name="job_description" class="ckeditor" id="jobDescription" required>{{ old('job_description') ? old('job_description') : @$job->job_description }}</textarea>
			    		        </div>
		            		</div>
		            		<hr>
						</div>
					</div>

					<div class="panel-body">
					
						<div class="row">
							<div class="col-md-12">
		            			<label>
		    		                <i class="fa fa-building"></i> &nbsp; Job Benefits*
		    		            </label>
		    		            <div class="text-editor-box">
			    		            <textarea name="benefits" class="ckeditor" id="JobBenefits" required>{{ old('benefits') ? old('benefits') : @$job->benefits }}</textarea>
			    		        </div>
		            		</div>
		            		<hr>
						</div>
					</div>

					<div class="col-md-12">
						<div class="btn-col">

							<input type="submit" value="{{ $job_id == 0 ? 'Post Job' : 'Update Job' }}"/>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!--RESUME FORM END--> 
</div>
<!--MAIN END--> 
@endsection
@push('post-scripts')

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>
<script src="{{ asset('frontend/js/form.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>

<script>
    $(document).ready(function(){

    	job_category_id = $("#JobCategory").val();
     	job_id = $("#JobId").val();

		call_ajax_function(job_category_id, job_id);         	


    	$('#JobCategory').change(function(){  

    		job_category_id = $(this).val();
            job_id = $("#JobId").val();

            call_ajax_function(job_category_id, job_id);

        });  

        function call_ajax_function(job_category_id, job_id){

        	$.ajax({
            	url : "{{ url('company/post-job/show-job-categories-skills') }}",
            	type : "POST",
            	data : {
            		'_token': '{{ csrf_token() }}',
            		job_category_id: job_category_id,
            		job_id: job_id
            	},
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        $('#SkillSelect').html($response.responseText);
                        $('.multi-select').dropdown();
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            });
        }

    });

    $('input:radio[name="salary_type"]').change(function(){

        if ($(this).is(':checked')) {
            check_radio_status($(this).val())
        }else{
            
        }
    });

    check_radio_status($('input:radio[name="salary_type"]:checked').val());

    function check_radio_status(salary_type_radio) {
    	if (salary_type_radio == 1) {
        	$(".salary_div").slideDown(500);
        	$("#MinSalary").slideDown(500);
        	$("#MaxSalary").slideDown(500);
        	$("#MinSalaryInput").attr('required',true);
        	$("#MinSalaryInput").attr('disabled',false);
        	$("#MaxSalaryInput").attr('required',true);
        	$("#MaxSalaryInput").attr('disabled',false);
        }else if (salary_type_radio == 2){
        	$(".salary_div").slideDown(500);
        	$("#MinSalary").slideDown(500);
        	$("#MaxSalary").slideUp(500);
        	$("#MinSalaryInput").attr('required',true);
        	$("#MinSalaryInput").attr('disabled',false);
        	$("#MaxSalaryInput").attr('required',false);
        	$("#MaxSalaryInput").attr('disabled',true);
        }else if (salary_type_radio == 3){
        	$(".salary_div").slideUp(500);
        	$("#MinSalary").slideUp(500);
        	$("#MaxSalary").slideUp(500);
        	$("#MinSalaryInput").attr('required',false);
        	$("#MinSalaryInput").attr('disabled',true);
        	$("#MaxSalaryInput").attr('required',false);
        	$("#MaxSalaryInput").attr('disabled',true);
        }
    }

</script>

<script>
	$('#multi-select').dropdown();
	$('.multi-select').dropdown();
</script>
@endpush