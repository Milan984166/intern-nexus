@extends('admin/layouts.header-sidebar')
@section('title', $employer->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-12 col-sm-12">
				<h2>{{ $employer->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers') }}">Employers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers.profile',['id' => $id]) }}">{{ $employer->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers.posted-jobs',['id' => $id]) }}">Posted Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $job_id == 0 ? 'Post A Job' : 'Update Jobs' }} </li>
                    </ol>
                </nav>
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

					<a href="{{ url('admin/employers/profile/'.base64_encode($employer->id).'/posted-jobs') }}" class="btn btn-block btn-round text-left btn-primary">
						<i class="fa fa-database"></i>
						Posted Jobs
					</a>
					
				</div>
			</div>                    
		</div>
		<div class="col-md-9">
			<div class="card border-secondary">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>{{ $job_id == 0 ? 'Post A Job' : 'Update Jobs' }}</h5>
							<small>{{ isset($employer->employer_info->organization_name) ? $employer->employer_info->organization_name : $employer->name }}</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ $job_id != 0 ? route('admin.employers.update-job',['id' => $id]) : route('admin.employers.add-job',['id' => $id]) }}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" id="JobId" name="job_id" value="{{ base64_encode($job_id) }}">
					<div class="card-body mb-0">	
						<div class="card-body pb-0 mb-3">
						
							<div class="row">
								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-database"></i> Job Title* </small>
									<input type="text" name="job_title" class="form-control" required value="{{ old('job_title') ? old('job_title') : ( $job_id != 0 ? $job->job_title : '' ) }}" placeholder="eg: Java Developer, HRM..">
									<hr>	
								</div>

								<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Job Level*
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
                        		    	@endphp
                        		        <select class="form-control" name="job_level" required>

                        		        	<option selected disabled>Select Job Level</option>

                        		        	@foreach($lookingForArray as $key => $look)
                                            <option {{ $job_id != 0 && $job->job_level == $key ? 'selected' : (old('job_level') == $key ? 'selected' : '' ) }} value='{{ $key }}'>{{ $lookingForArray[$key] }}</option>
                                            @endforeach
                                            
                                        </select>
                        		    </div>
                        		    <hr>
                        		</div>
                        		@php
									$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

                                @endphp
                        		<div class="col-md-6">
            						<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Job Category*
                		            </small>
                        		    <div class="input-group mb-3">
                        		        <select class="form-control" name="job_category_id" required id="JobCategory">

                        		        	<option value="0" selected disabled>Select Job Category</option>

                        		        	@foreach($jobCategories as $jobCat)
                                            	<option {{ $job_id != 0  && $job->job_category_id == $jobCat->id ? 'selected' : (old('job_category_id') == $jobCat->id ? 'selected' : '' ) }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
                                            @endforeach
                                        </select>
                        		    </div>
                        		    <hr>
            					</div>

            					<div class="col-md-6">
            						<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Skills*
                		            </small>
                        		    <div class="mb-3 multiselect_div" id="SkillSelect">

                        		        <select class="form-control" required>
                        		        	<option selected disabled>Select Job Category First</option>
                                        </select>
                        		    </div>
                        		    <hr>
            					</div>

            					<div class="col-md-6">
                    		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Employment Type*  </small>
                    		        @php
                    		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
                    		        @endphp
                    		        <div class="input-group mb-3">
	                    		        <select class="form-control" name="employment_type" required>

	                                        @foreach($employmentTypeArray as $key => $emptype)
	                                        <option value="{{$key}}" {{ $job_id != 0 && $job->employment_type == $key  ? 'selected' : (old('employment_type') == $key ? 'selected' : '' ) }}>{{ $employmentTypeArray[$key] }}</option>

	                                        @endforeach
	                                    </select>
	                                </div>
                    		        <hr>
                        		</div>

                        		<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-users"></i> No. of Vacancy* </small>
									<input type="number" min="1" name="no_of_vacancy" class="form-control" value="{{ old('no_of_vacancy') ? old('no_of_vacancy') : ($job_id != 0 ? $job->no_of_vacancy : 1) }}" required>
									<hr>
								</div>

                        		<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-calendar"></i> Deadline: </small>
									<input type="text" name="deadline" class="form-control" value="{{ old('deadline') ? old('deadline') : ($job_id != 0 ? date('Y-m-d',strtotime($job->deadline)) : '') }}" data-provide="datepicker" data-date-autoclose="true" class="form-control" data-date-format="yyyy-mm-dd" placeholder="YYYY-MM-DD" required>
									<hr>
								</div>

								<div class="col-md-6">
									<small class="text-muted"><i class="fa fa-database"></i> Job Control* </small>
									<div class="input-group mb-3">
	                    		        <div class="input-group-prepend">
	                    		            <div class="input-group-text" style="background-color: #e1e8ed">
	                    		                <input type="checkbox" name="display" value="1" {{ $job_id != 0 && $job->display == 1 ? 'checked' : (old('display') == 1 ? 'checked' : '' )}}>
	                    		            </div>
	                    		        </div>
	                    		        <span class="form-control disabled">Display</span>
	                    		        <div class="input-group-prepend">
	                    		            <div class="input-group-text" style="background-color: #e1e8ed">
	                    		                <input type="checkbox" name="featured" value="1" {{ $job_id != 0 && $job->featured == 1 ? 'checked' : (old('featured') == 1 ? 'checked' : '' )}}>
	                    		            </div>
	                    		        </div>
	                    		        <span class="form-control disabled">Featured</span>
	                    		    </div>
	                    		    <hr>
								</div>
							</div>
						</div>
						<div class="card-body pb-0 mb-3">
						
							<div class="row">
								<div class="col-md-6">
	                    			<small class="text-muted"><i class="fa fa-money"></i> &nbsp;Offered Salary</small>
	                    		    <div class="input-group mb-3">
	                    		        <div class="input-group-prepend">
	                    		            <div class="input-group-text" style="background-color: #e1e8ed">
	                    		                <input type="radio" name="salary_type" value="1" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 1 ? 'checked' : (old('salary_type') == 1 ? 'checked' : '' )}}>
	                    		            </div>
	                    		        </div>
	                    		        <span class="form-control disabled">Range <small style="font-size: 10px;">(Provide the minimum to maximum salary in range.)</small></span>
	                    		    </div>
	                    		    <div class="input-group mb-3">

	                    		        <div class="input-group-prepend">
	                    		            <div class="input-group-text" style="background-color: #e1e8ed">
	                    		                <input type="radio" name="salary_type" value="2" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 2 ? 'checked' : (old('salary_type') == 2 ? 'checked' : '' )}}>
	                    		            </div>
	                    		        </div>
	                    		        <span class="form-control disabled">Fixed <small style="font-size: 10px;">(Provide the minimum offered salary.)</small></span>
	                    		    </div>

	                    		    <div class="input-group mb-3">
	                    		        <div class="input-group-prepend">
	                    		            <div class="input-group-text" style="background-color: #e1e8ed">
	                    		                <input type="radio" name="salary_type" value="3" class="salary_type_radio" {{ $job_id != 0 && $job->salary_type == 3 ? 'checked' : (old('salary_type') == 3 ? 'checked' : '' )}}>
	                    		            </div>
	                    		        </div>
	                    		        <span class="form-control disabled">Negotiable </span>
	                    		    </div>
	                    		    <hr>
	                    		</div>

								<div class="col-md-6 salary_div">
									<span id="MinSalary">
										<small class="text-muted">
											<i class="fa fa-money"></i> Minimum Salary 
										</small>
										<input id="MinSalaryInput" type="text" name="min_salary" class="form-control" required value="{{ old('min_salary') ? old('min_salary') : ( $job_id != 0 ? $job->min_salary : '' ) }}" placeholder="$25000">
										<hr>
									</span>

									<span id="MaxSalary">
										<small class="text-muted">
											<i class="fa fa-money"></i> Maximum Salary 
										</small>
										<input id="MaxSalaryInput" type="text" name="max_salary" class="form-control" required value="{{ old('max_salary') ? old('max_salary') : ( $job_id != 0 ? $job->max_salary : '' ) }}" placeholder="$25000">
										<hr>
									</span>	
                        		</div>
                        	</div>
                        	<div class="row">

                                <div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Education Level*
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$educationLevel = array('1' => 'Others', '2' => 'SLC', '3' => 'Intermediate' , '4' => 'Bachelor', '5' => 'Master', '6' => 'Ph.D.');
                        		    	@endphp
                        		        <select class="form-control" name="education_level" required>

                        		        	<option selected disabled>-------------------------</option>

                        		        	@foreach($educationLevel as $key => $look)
                                            <option {{ $job_id != 0 && $job->education_level == $key ? 'selected' : (old('education_level') == $key ? 'selected' : '' ) }} value='{{ $key }}'>{{ $educationLevel[$key] }}</option>
                                            @endforeach
                                            
                                        </select>
                        		    </div>
                        		</div>

                        		<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Experience(In Years)
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$experienceTypes = array('1' => 'More than', '2' => 'Less than', '3' => 'More than or equals to' , '4' => 'Less than or equals to', '5' => 'Equals to');
                        		    	@endphp
                        		        <select class="form-control" name="experience_type">

                        		        	<option selected disabled>-------------------------</option>

                        		        	@foreach($experienceTypes as $key => $look)
                                            <option {{ $job_id != 0 && $job->experience_type == $key ? 'selected' : (old('experience_type') == $key ? 'selected' : (old('experience_type') == $key ? 'selected' : '') ) }} value='{{ $key }}'>{{ $experienceTypes[$key] }}</option>
                                            @endforeach
                                            
                                        </select>
                                        <select class="form-control" name="experience_year">

                        		        	@for($i=1; $i<=50; $i++)
                                            <option {{ $job_id != 0 && $job->experience_year == $i ? 'selected' : (old('experience_year') == $i ? 'selected' : '') }} value='{{ $i }}'>{{ $i }} {{ $i == 1 ? 'Year' : 'Years' }}</option>
                                            @endfor
                                            
                                        </select>
                        		    </div>
                        		</div>

                        		<div class="col-md-6">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Location Preference*
                		            </small>
                        		    <div class="input-group mb-3">
                        		    	@php
                        		    		$locationsDB = \App\Location::where('display',1)->orderBy('order_item')->get();
                        		    	@endphp
                        		        <select class="form-control" name="location_id" required>

                        		        	<option selected disabled>Select Locaiton</option>

                                            @foreach($locationsDB as $loc)
                                            	<option {{ $job_id != 0 && $loc->id == $job->location_id ? 'selected' : (old('location_id') == $loc->id ? 'selected' : '' ) }} value="{{ $loc->id }}">{{ $loc->title }}</option>
                                            @endforeach

                                        </select>
                        		    </div>
                        		    
                        		</div>

                                <hr>
                                <div class="col-md-12">
                                	<hr>
                                	<strong class="text-muted"><i class="fa fa-comments"></i> Contact Via </strong>
                                	<small class="pull-right text-muted">Leave Blank if not necessary!</small>
                                	<hr>
                                	<div class="row">
                                		<div class="col-md-5">
                                			<small class="text-muted"><i class="fa fa-comments"></i> Contact Email* </small>
                                			<input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') ? old('contact_email') : ( $job_id != 0 ? $job->contact_email : '' ) }}" placeholder="eg: hello@intern-nexus.com">
                                			<hr>		
                                		</div>
                                		<div class="col-md-5">
                                			<small class="text-muted"><i class="fa fa-comments"></i> Contact Phone* </small>
                                			<input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') ? old('contact_phone') : ( $job_id != 0 ? $job->contact_phone : '' ) }}" placeholder="eg: 9876543210">
                                			<hr>		
                                		</div>
                                		<div class="col-md-4">
                                			<small class="text-muted"><i class="fa fa-comments"></i> Messenger Link* </small>
                                			<input type="text" name="messenger_link" class="form-control" value="{{ old('messenger_link') ? old('messenger_link') : ( $job_id != 0 ? $job->messenger_link : '' ) }}" placeholder="eg: https://m.me/username">
                                			<hr>		
                                		</div>
                                		<div class="col-md-4">
                                			<small class="text-muted"><i class="fa fa-comments"></i> Viber Number </small>
                                			<input type="text" name="viber_number" class="form-control" value="{{ old('viber_number') ? old('viber_number') : ( $job_id != 0 ? $job->viber_number : '' ) }}" placeholder="eg: 9876543210">
                                			<hr>		
                                		</div>
                                		<div class="col-md-4">
                                			<small class="text-muted"><i class="fa fa-comments"></i> WhatsApp Number </small>
                                			<input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') ? old('whatsapp_number') : ( $job_id != 0 ? $job->whatsapp_number : '' ) }}" placeholder="eg: 9876543210">
                                			<hr>		
                                		</div>
                                	</div>
								</div>
                        	</div>
                        </div>
                        <div class="card-body pb-3 mb-3">
						
							<div class="row">
								<div class="col-md-12">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Job Description*
                		            </small>
                		            <textarea name="job_description" id="editor1" class="form-control ckeditor" required>{{ old('job_description') ? old('job_description') : @$job->job_description }}</textarea>
                        		</div>
                        		<hr>
							</div>
						</div>

						<div class="card-body pb-3 mb-3">
						
							<div class="row">
								<div class="col-md-12">
                        			<small class="text-muted">
                		                <i class="fa fa-building"></i> &nbsp; Job Benefits*
                		            </small>
                		            <textarea name="benefits" id="editor1" class="form-control ckeditor" required>{{ old('benefits') ? old('benefits') : @$job->benefits }}</textarea>
                        		</div>
                        		<hr>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
	                            <a href="{{ route('admin.employers.basic-info',['id' => $id]) }}"
	                            class="btn btn-outline-danger">CANCEL</a>

	                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
	                        </div>
	                    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
    <script>
    	$('#employmentType').multiselect({
	        maxHeight: 300
	    });


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
                	url : "{{ url('admin/employers/profile/'.$id.'/post-jobs/show-job-categories-skills') }}",
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
                            $('.skills_multiselect').multiselect();
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

@endsection