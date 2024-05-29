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
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.work-experience',['id' => $id]) }}">Work Experience</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Work Experience</li>
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
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/job-preference') }}" class="btn btn-block btn-round text-left btn-outline-info">
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

					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/work-work-experience') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
							<h5>Work Experience</h5>
							<small>Edit | Update Work Experience Details</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.job-seekers.update-work-experience',['id' => $id]) }}">
					@csrf
					<input type="hidden" id="experienceCount" value="<?=$experiences->count()+1;?>">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12" id="experiences_field">
								@php
									$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

                                	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
                                @endphp
								@if($experiences->count() == 0)
			                    <div class="card" id="experience1">
	                                <div class="card-body">
	                                	<div class="row">
	                                		<div class="col-md-6">
	                                		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Organization name* </small>
	                                		        <input type="text" name="organization_name[]" class="form-control" required value="" placeholder="eg: Laxmi Bank Ltd.">
	                                		        <hr>
	                                		    
	                                		</div>
	                                		<div class="col-md-6">
	                                		    
	                            		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Location*</small>

	                            		        <input type="text" name="job_location[]" class="form-control" required value="" placeholder="eg: Kathmandu">
	                            		        <hr>
	                                		</div>
	                                		<div class="col-md-6">
	                                		    
	                    		                <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Title*</small>
	                    		            
	                    		        		<input type="text" name="job_title[]" class="form-control" required value="" placeholder="eg: Branch Manager...">
	                    		        		<hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
	                        		            </small>
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control" name="job_category_id[]" required>

	                                		        	<option selected disabled>Select Job Category</option>
	                                		        	@foreach($jobCategories as $jobCat)
			                                            <option value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
			                                            @endforeach
			                                        </select>
	                                		    </div>
	                                		    <hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted"><i class="fa fa-building"></i> &nbsp;Currently Working Here</small>
	                                		    <div class="input-group mb-3">
	                                		        <div class="input-group-prepend">
	                                		            <div class="input-group-text" style="background-color: #e1e8ed">
	                                		                <input type="checkbox" name="working_here[]" value="1" class="working_here_checkbox" id="1">
	                                		            </div>
	                                		        </div>
	                                		        <span class="form-control disabled">Currently Working Here</span>
	                                		    </div>
	                                		    <hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; Start Date*
	                        		            </small>
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control" name="start_year[]" required>
	                                		        	<option disabled selected>Year</option>
	                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
			                                            <option value="{{$year}}">{{$year}}</option>
			                                            @endfor
			                                        </select>
			                                        <select class="form-control" name="start_month[]" required>
	                                		        	<option disabled selected>Month</option>

	                                		        	@foreach($months as $key => $month)
			                                            <option value="{{$key}}">{{$month}}</option>
			                                            @endforeach
			                                        </select>
	                                		    </div>
	                                		    <hr>
	                                		</div>

	                                		<div class="col-md-6" id="EndDate1">
	                                			
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; End Date*
	                        		            </small>
	                                		    
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control end_date1" name="end_year[]" required>
	                                		        	<option disabled selected>Year</option>

	                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
			                                            	<option value="{{$year}}">{{$year}}</option>
			                                            @endfor
			                                        </select>

			                                        <select class="form-control end_date1" name="end_month[]" required>
	                                		        	<option disabled selected>Month</option>

	                                		        	@foreach($months as $key => $month)
			                                            	<option value="{{$key}}">{{$month}}</option>
			                                            @endforeach
			                                        </select>
	                                		    </div>
	                                		    <hr>
	                                		</div>

	                                		<div class="col-md-12">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
	                        		            </small>
	                        		            <textarea name="duties_responsibilities[]" class="form-control"></textarea>
	                        		            <hr>
	                                		</div>

	                                	</div>
	                                </div>
			                    </div>
			                    @else
			                    	@foreach($experiences as $i => $experience)
			                    	<input type="hidden" name="experience_id[]" value="{{ $experience->id }}">
			                    	<div class="card" id="experience{{ $i }}">
		                                <div class="card-body">
		                                	<div class="row">
		                                		@if($experiences->count() > 1)
		                                		<div class="offset-md-10 col-md-2 text-right">
		                                			<a href="#deleteExperience"
		                                			   data-toggle="modal"
		                                			   data-id="{{ $experience->id }}"
		                                			   id="delete_experience{{ $experience->id }}"
		                                			   class="btn btn-sm btn-danger delete"
		                                			   onclick="delete_experience('{{ base64_encode($experience->id) }}')">
		                                			   <i class=" fa fa-trash"></i>
		                                			</a>
		                                		</div>
		                                		@endif
		                                		<div class="col-md-6">
	                                		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Organization name*  </small>
	                                		        <input type="text" name="organization_name_db[]" class="form-control" required value="{{ $experience->organization_name }}" placeholder="eg: Laxmi Bank Ltd.">
	                                		        <hr>
		                                		    
		                                		</div>
		                                		<div class="col-md-6">
		                                		    
		                            		        <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Location*</small>

		                            		        <input type="text" name="job_location_db[]" class="form-control" required value="{{ $experience->job_location }}" placeholder="eg: Kathmandu">
		                            		        <hr>
		                                		</div>
		                                		<div class="col-md-6">
		                                		    
		                    		                <small class="text-muted"><i class="fa fa-building"></i> &nbsp; Job Title*</small>
		                    		            
		                    		        		<input type="text" name="job_title_db[]" class="form-control" required value="{{ $experience->job_title }}" placeholder="eg: Branch Manager...">
		                    		        		<hr>
		                                		</div>
		                                		<div class="col-md-6">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-building"></i> &nbsp; Job Category*
		                        		            </small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control" name="job_category_id_db[]" required>

		                                		        	<option selected disabled>Select Job Category</option>
		                                		        	@foreach($jobCategories as $jobCat)
				                                            <option {{ $experience->job_category_id == $jobCat->id ? 'selected' : '' }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
				                                            @endforeach
				                                        </select>
		                                		    </div>
		                                		    <hr>
		                                		</div>

		                                		<div class="col-md-6">
		                                			<small class="text-muted"><i class="fa fa-building"></i> &nbsp;Currently Working Here</small>
		                                		    <div class="input-group mb-3">
		                                		        <div class="input-group-prepend">
		                                		            <div class="input-group-text" style="background-color: #e1e8ed">
		                                		                <input type="checkbox" name="working_here_db[]" value="1" class="working_here_checkbox" id="{{$i}}" {{ $experience->working_here == 1 ? 'checked' : ''}}>
		                                		            </div>
		                                		        </div>
		                                		        <span class="form-control disabled">Currently Working Here?</span>
		                                		    </div>
		                                		    <hr>
		                                		</div>

		                                		<div class="col-md-6">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-building"></i> &nbsp; Start Date*
		                        		            </small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control" name="start_year_db[]" required>
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}" {{ $experience->start_year == $year ? 'selected' : '' }}>{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        <select class="form-control" name="start_month_db[]" required>
		                                		        	<option value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}" {{ $experience->start_month == $key ? 'selected' : '' }}>{{$month}}</option>
				                                            @endforeach
				                                        </select>
		                                		    </div>
		                                		    <hr>
		                                		</div>

		                                		<div class="col-md-6" id="EndDate{{$i}}">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-building"></i> &nbsp; End Date*
		                        		            </small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control end_date{{$i}}" name="end_year_db[]" required="">
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}" {{ $experience->end_year == $year ? 'selected' : '' }}>{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        <select class="form-control end_date{{$i}}" name="end_month_db[]" required="">
		                                		        	<option value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}" {{ $experience->end_month == $key ? 'selected' : '' }}>{{$month}}</option>
				                                            @endforeach
				                                        </select>
		                                		    </div>
		                                		    <hr>
		                                		</div>

		                                		<div class="col-md-12">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-building"></i> &nbsp; Duties and Responsibilities*
		                        		            </small>
		                        		            <textarea name="duties_responsibilities_db[]" class="form-control">{{ $experience->duties_responsibilities }}</textarea>
		                        		            <hr>
		                                		</div>

		                                	</div>
		                                </div>
				                    </div>	
			                    	@endforeach
			                    @endif
			                </div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button type="button" class="btn btn-outline-primary" id="addExperienceField">Add Another Work Experience Field</button>
								<a href="{{ route('admin.job-seekers.work-experience',['id' => $id]) }}"
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

<div class="modal fade modal-info" id="deleteExperience">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Work Experience?</h5>
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
        $(document).ready(function(){
            var i=1;
			i = $('#experienceCount').val();

            $('#addExperienceField').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('admin/job-seekers/profile/'.$id.'/work-experience/add-work-experiences') }}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#experiences_field').append($response.responseText);
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
                $('#experience'+button_id).remove();  
            });  

        });

        $(".working_here_checkbox").each(function(){
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);

        	if($(this).is(':checked')){
        		$("#EndDate"+checkbox_id).slideUp();
        		$(".end_date"+checkbox_id).attr('required',false);
        	}else{
        		$("#EndDate"+checkbox_id).slideDown();
        		$(".end_date"+checkbox_id).attr('required',true);
        	}
        })

        $(document).on('click', '.working_here_checkbox', function(){

        	var checkbox_id = $(this).attr("id");

        	if($(this).is(':checked')){
        		$("#EndDate"+checkbox_id).slideUp();
        		$(".end_date"+checkbox_id).attr('required',false);
        	}else{
        		$("#EndDate"+checkbox_id).slideDown();
        		$(".end_date"+checkbox_id).attr('required',true);
        	}
        });

        function delete_experience(id) {
        	var conn = "{{ url('admin/job-seekers/profile/'.$id.'/work-experiences/delete') }}/"+id;

            $('#deleteExperience a').attr("href", conn);
        }

    </script>

@endsection