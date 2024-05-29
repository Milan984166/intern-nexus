@extends('admin/layouts.header-sidebar')
@section('title', $jobseeker->name)
@section('content')
<div class="container-fluid">
	<div class="block-header">
		<div class="row clearfix">
			<div class="col-md-6 col-sm-12">
				<h2>{{ $jobseeker->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers') }}">Job Seekers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.profile',['id' => $id]) }}">{{ $jobseeker->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.job-preference',['id' => $id]) }}">Job Preference</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Job Preference</li>
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
						<div class="mr-3">
							@if($jobseeker->image != '')
                            	<img src="{{ asset('storage/jobseekers/thumbs/small_'.$jobseeker->image) }}" class="rounded" alt="no-image">
                            @else
                            	<img class="rounded" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                            @endif

	                    </div>
						<div class="details">
							<h5 class="mb-0">{{ $jobseeker->name }}</h5>
							<span class="text-light">
								@if ($jobseeker->gender == 1) 
								    Male
								@elseif ($jobseeker->gender == 2)
								    Female
								@elseif ($jobseeker->gender == 3) 
								    Others
								@else
								    To be Updated
								@endif
							</span>
						</div>                                
					</div>
				</div>
				<div class="card-body">
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id)) }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-user"></i>
						Profile Overview
					</a>

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/basic-info') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-info-circle"></i>
						Basic Information
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/job-preference') }}" class="btn btn-block btn-round text-left btn-primary">
						<i class="fa fa-briefcase"></i>
						Job Preference
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/education') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-graduation-cap"></i>
						Education
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/training') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-tasks"></i>
						Training
					</a>

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/work-experience') }}" class="btn btn-block btn-round text-left btn-outline-info">
						<i class="fa fa-building"></i>
						Work Experience
					</a>
				</div>
			</div>                    
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-header">
					<div class="d-flex">
						<div class="details">
							<h5>Job Preference</h5>
							<small>Edit | Update Job Preference Details</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.job-seekers.update-job-preference',['id' => $id]) }}">
					@csrf
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
		                    	<div class="card">
	                                <div class="card-body">
	                                	<div class="row">
	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; Looking For*
	                        		            </small>
	                                		    <div class="input-group mb-3">
	                                		    	@php
	                                		    		$lookingForArray = array('1' => 'Top Level', '2' => 'Senior Level', '3' => 'Mid Level' , '4' => 'Entry Level');
	                                		    	@endphp
	                                		        <select class="form-control" name="looking_for" required>

	                                		        	<option selected disabled>Select Job Level</option>

	                                		        	@foreach($lookingForArray as $key => $look)
			                                            <option {{ @$preference->looking_for == $key ? 'selected' : '' }} value='{{ $key }}'>{{ $lookingForArray[$key] }}</option>
			                                            @endforeach
			                                            

			                                        </select>
	                                		    </div>
	                                		    <hr>
	                                		</div>
	                                		<div class="col-md-6 multiselect_div">
                                		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Employment Type*  </small>
                                		        @if(isset($preference->employment_type))
	                                		        @php
	                                		        	$dbEmploymentTypes = json_decode($preference->employment_type);
	                                		        @endphp
	                                		    @else
	                                		    	@php
	                                		    		$dbEmploymentTypes = array();
	                                		    	@endphp
                                		        @endif
                                		        @php
                                		        	$employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer')
                                		        @endphp
                                		        <select id="employmentType" class="multiselect multiselect-custom" multiple="multiple" name="employment_type[]" required>

		                                            @foreach($employmentTypeArray as $key => $emptype)
		                                            <option value="{{$key}}" {{ in_array($key, $dbEmploymentTypes)  ? 'selected' : '' }}>{{ $employmentTypeArray[$key] }}</option>

		                                            @endforeach
		                                        </select>

                                		        <hr>
	                                		    
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Expected Salary*</small>
	                                		    <div class="input-group mb-3">
	                                		    	<input type="text" class="form-control" name="expected_salary" value="{{ @$preference->expected_salary }}" placeholder="eg: 25000" required>
	                                		    	@php
	                                		    		$salPeriod = array('1' => 'Daily', '2' => 'Weekly', '3' => 'Monthly' , '4' => 'Yearly');
	                                		    	@endphp
	                                		        <select class="form-control" name="expected_salary_period" required>
	                                		        	@foreach($lookingForArray as $key => $look)

			                                            <option {{ @$preference->expected_salary_period == $key ? 'selected' : '' }} value='{{ $key }}'>{{ $salPeriod[$key] }}</option>

			                                            @endforeach
			                                        </select>
	                                		    </div>
	                                		    <hr>
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
			                                            	<option {{ $loc->id == @$preference->location_id ? 'selected' : '' }} value="{{ $loc->id }}">{{ $loc->title }}</option>
			                                            @endforeach
			                                            

			                                        </select>
	                                		    </div>
	                                		    <hr>
	                                		</div>
	                                		@php
												$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

			                                @endphp
	                                		<div class="col-md-12">
	                                			<div class="card-body" id="category_skills_field">	
	                                				@if(isset($preference->category_skills))
	                                				<input type="hidden" id="categorySkillCount" value="{{$preference->category_skills->count()}}">
	                                				@foreach($preference->category_skills as $key => $catSkill)
	                                				<input type="hidden" name="category_skill_id[{{ $key+1 }}]" value="{{ $catSkill->id }}">
	                                				<div class="card-body pb-0">
		                                				<div class="row">
		                                					<div class="col-md-5">
		                                						<small class="text-muted">
					                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
					                        		            </small>
					                                		    <div class="input-group mb-3">
					                                		        <select class="form-control select_job_category" name="job_category_id_db[{{$key+1}}]" required id="{{$key+1}}">

					                                		        	<option selected disabled>Select Job Category</option>

					                                		        	@foreach($jobCategories as $jobCat)
							                                            	<option data-cat-skill-id="{{$catSkill->id }}"  {{ $jobCat->id == $catSkill->job_category_id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
							                                            @endforeach
							                                        </select>
					                                		    </div>

		                                					</div>

		                                					<div class="col-md-5">
		                                						<small class="text-muted">
					                        		                <i class="fa fa-building"></i> &nbsp; Skills*
					                        		            </small>
					                                		    <div class="mb-3 multiselect_div" id="skillSelect{{ $key+1 }}">
					                                		        <!-- select skills field -->
					                                		    </div>
					                                		    
		                                					</div>
		                                					@if($preference->category_skills->count() > 1)
					                                		<div class="col-md-2 text-right">
					                                			<a href="#deletePreference"
					                                			   data-toggle="modal"
					                                			   data-id="{{ $catSkill->id }}"
					                                			   id="delete_preference{{ $catSkill->id }}"
					                                			   class="btn btn-sm btn-danger delete"
					                                			   onclick="delete_preference('{{ base64_encode($catSkill->id) }}')">
					                                			   <i class=" fa fa-trash"></i>
					                                			</a>
					                                		</div>	
					                                		@endif
		                                				</div>
	                                				</div>
	                                				@endforeach
	                                				@else
	                                				<input type="hidden" id="categorySkillCount" value="0">
	                                				<div class="card-body pb-0">
		                                				<div class="row">
		                                					<div class="col-md-5">
		                                						<small class="text-muted">
					                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
					                        		            </small>
					                                		    <div class="input-group mb-3">
					                                		        <select class="form-control select_job_category" name="job_category_id[1]" required id="1">

					                                		        	<option selected disabled>Select Job Category</option>

					                                		        	@foreach($jobCategories as $jobCat)
							                                            	<option {{ @$preference->job_category_id == $jobCat->id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
							                                            @endforeach
							                                        </select>
					                                		    </div>

		                                					</div>

		                                					<div class="col-md-5">
		                                						<small class="text-muted">
					                        		                <i class="fa fa-building"></i> &nbsp; Skills*
					                        		            </small>
					                                		    <div class="mb-3 multiselect_div" id="skillSelect1">
					                                		        <select class="form-control" required>

					                                		        	<option selected disabled>Select Job Category First</option>
							                                        </select>
					                                		    </div>
					                                		    
		                                					</div>
		                                				</div>
	                                				</div>
	                                				@endif

	                                			</div>
	                                			<div class="card-footer text-center">
	                                				<button type="button" class="btn btn-outline-primary" id="addCategorySkills">Add Another Job Preference Field</button>
	                                			</div>
	                                		</div>

	                                		<div class="col-md-12">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; Career Objectives*
	                        		            </small>
	                        		            <textarea name="career_objective" id="editor1" class="form-control ckeditor">{{ @$preference->career_objective }}</textarea>
	                        		            <hr>
	                                		</div>

	                                	</div>
	                                </div>
			                    </div>	
			                </div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<a href="{{ route('admin.job-seekers.job-preference',['id' => $id]) }}"
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

<div class="modal fade modal-info" id="deletePreference">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Job Preference?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <p>Are you Sure...??</p>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                <a href="" class="btn btn-round btn-danger">Delete</a>
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
            var i=1;
			i = $('#categorySkillCount').val();

            $('#addCategorySkills').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('admin/job-seekers/profile/'.$id.'/job-preference/add-job-preferences') }}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#category_skills_field').append($response.responseText);
                            show_job_categories_skills();
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove', function(){  
                var button_id = $(this).attr("id");   
                $('#preference'+button_id).remove();  
            });

            function call_ajax_function(select_id, job_category_id, cat_skill_id){
            	$.ajax({
                	url : "{{ url('admin/job-seekers/profile/'.$id.'/job-preference/show-job-categories-skills') }}/",
                	type : "POST",
                	data : {
                		'_token': '{{ csrf_token() }}',
                		select_id: select_id,
                		job_category_id: job_category_id,
            			cat_skill_id: cat_skill_id
                	},
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#skillSelect'+select_id).html($response.responseText);
                            $('.skills_multiselect').multiselect();
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            }

            show_job_categories_skills();

            function show_job_categories_skills(){
            	$('.select_job_category').change(function(){  
	                var select_id = $(this).attr("id");   
	                var job_category_id = $(this).val();
	                var cat_skill_id = $('#categorySkillCount').val() == 0 ? '' : $(this).find(':selected').data('cat-skill-id');
	                

	                // console.log(job_category_id);

	                call_ajax_function(select_id, job_category_id, cat_skill_id)

	            });  
            }

            $(".select_job_category").each(function(){

            	var select_id = $(this).attr("id");
            	var job_category_id = $(this).val();
            	var cat_skill_id = $(this).find(':selected').data('cat-skill-id');

            	// console.log(cat_skill_id);
            	// return;

            	call_ajax_function(select_id, job_category_id, cat_skill_id)

            })
        });


        function delete_preference(id) {
        	var conn = "{{ url('admin/job-seekers/profile/'.$id.'/job-preferences/delete') }}/"+id;

            $('#deletePreference a').attr("href", conn);
        }

        $('.skills_multiselect').multiselect();

    </script>

@endsection