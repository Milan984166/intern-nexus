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
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.education',['id' => $id]) }}">Education</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Education</li>
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
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/education') }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
							<h5>Education</h5>
							<small>Fill the latest information first</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.job-seekers.update-education',['id' => $id]) }}">
					@csrf
					<input type="hidden" id="educationCount" value="<?=$educations->count()+1;?>">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12" id="educations_field">
								@if($educations->count() == 0)
			                    <div class="card" id="education1">
	                                <div class="card-body">
	                                	<div class="row">
	                                		<div class="col-md-6">
                                		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Degree* </small>
                                		        <input type="text" name="degree[]" class="form-control" required value="" placeholder="eg: Intermediate, Bachelors">
                                		        <hr>
	                                		    
	                                		</div>
	                                		<div class="col-md-6">
	                                		    
	                            		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Program*</small>

	                            		        <input type="text" name="program[]" class="form-control" required value="" placeholder="eg: MBS, BCA, BBA">
	                            		        <hr>
	                                		</div>
	                                		<div class="col-md-6">
	                                		    
	                    		                <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Board*</small>
	                    		            
	                    		        		<input type="text" name="board[]" class="form-control" required value="" placeholder="eg: TU, KU, PoU...">
	                    		        		<hr>
	                                		</div>
	                                		<div class="col-md-6">
	                            		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Name of Institute*</small>
	                            		            
	                            		        <input type="text" name="institute[]" class="form-control" required value="" placeholder="eg: KTM RUSH Academics..">

	                            		        <hr>
	                                		</div>
	                                		<div class="col-md-6">
	                                			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp;Currently Studying Here</small>
	                                		    <div class="input-group mb-3">
	                                		        <div class="input-group-prepend">
	                                		            <div class="input-group-text" style="background-color: #e1e8ed">
	                                		                <input type="checkbox" name="student_here[]" value="1" class="student_here_checkbox" id="1">
	                                		            </div>
	                                		        </div>
	                                		        <span class="form-control disabled">Currently Studying Here</span>
	                                		    </div>
	                                		    <hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-graduation-cap"></i> &nbsp; 
	                        		                <span id="YearGradStartId1">Graduation Year</span>
	                        		            </small>
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control" name="year[]">
	                                		        	<option value="">Year</option>
	                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
			                                            <option value="{{$year}}">{{$year}}</option>
			                                            @endfor
			                                        </select>
			                                        @php
			                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
			                                        @endphp
			                                        <select class="form-control" name="month[]">
	                                		        	<option value="">Month</option>

	                                		        	@foreach($months as $key => $month)
			                                            <option value="{{$key}}">{{$month}}</option>
			                                            @endforeach
			                                        </select>
	                                		    </div>
	                                		</div>

	                                		<div class="col-md-6" id="MarksSecuredIn1">
	                                			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</small>
	                                		    <div class="input-group mb-3">
	                                		        <select class="form-control marks_secured1" name="marks_unit[]" required>
			                                            <option value="1">Percentage</option>
			                                            <option value="2">CGPA</option>
			                                        </select>
			                                        <input type="text" class="form-control marks_secured1" name="marks[]" placeholder="Marks Secured" required>
	                                		    </div>
	                                		</div>

	                                	</div>
	                                </div>
			                    </div>
			                    @else
			                    	@foreach($educations as $i => $education)
			                    	<input type="hidden" name="education_id[]" value="{{ $education->id }}">
			                    	<div class="card" id="education{{ $i }}">
		                                <div class="card-body">
		                                	<div class="row">
		                                		@if($educations->count() > 1)
		                                		<div class="offset-md-10 col-md-2 text-right">
		                                			<a href="#deleteEducation"
		                                			   data-toggle="modal"
		                                			   data-id="{{ $education->id }}"
		                                			   id="delete_education{{ $education->id }}"
		                                			   class="btn btn-sm btn-danger delete"
		                                			   onclick="delete_education('{{ base64_encode($education->id) }}')">
		                                			   <i class=" fa fa-trash"></i>
		                                			</a>
		                                		</div>
		                                		@endif
		                                		<div class="col-md-6">
	                                		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Degree* </small>
	                                		        <input type="text" name="degree_db[]" class="form-control" required value="{{ $education->degree }}" placeholder="eg: Intermediate, Bachelors">
	                                		        <hr>
	                                		   
		                                		</div>
		                                		<div class="col-md-6">
		                                		    
		                            		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Program*</small>

		                            		        <input type="text" name="program_db[]" class="form-control" required value="{{ $education->program }}" placeholder="eg: MBS, BCA, BBA">
		                            		        <hr>
		                                		</div>
		                                		<div class="col-md-6">
		                                		    
		                    		                <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Education Board*</small>
		                    		            
		                    		        		<input type="text" name="board_db[]" class="form-control" required value="{{ $education->board }}" placeholder="eg: TU, KU, PoU...">
		                    		        		<hr>
		                                		</div>
		                                		<div class="col-md-6">
		                            		        <small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Name of Institute*</small>
		                            		            
		                            		        <input type="text" name="institute_db[]" class="form-control" required value="{{ $education->institute }}" placeholder="eg: KTM RUSH Academics..">

		                            		        <hr>
		                                		</div>
		                                		<div class="col-md-6">
		                                			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp;Currently Studying Here</small>
		                                		    <div class="input-group mb-3">
		                                		        <div class="input-group-prepend">
		                                		            <div class="input-group-text" style="background-color: #e1e8ed">
		                                		                <input type="checkbox" name="student_here_db[]" value="1" class="student_here_checkbox" id="{{$i}}" {{ $education->student_here == 1 ? 'checked' : ''}}>
		                                		            </div>
		                                		        </div>
		                                		        <span class="form-control disabled">Currently Studying Here?</span>
		                                		    </div>
		                                		    <hr>
		                                		</div>

		                                		<div class="col-md-6">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-graduation-cap"></i> &nbsp; 
		                        		                <span id="YearGradStartId{{$i}}">
		                        		                	{{ $education->student_here == 1 ? 'Started Year' : 'Graduation Year' }}
		                        		                </span>
		                        		            </small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control" name="year_db[]">
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}" {{ $education->year == $year ? 'selected' : '' }}>{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        @php
				                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
				                                        @endphp
				                                        <select class="form-control" name="month_db[]">
		                                		        	<option value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}" {{ $education->month == $key ? 'selected' : '' }}>{{$month}}</option>
				                                            @endforeach
				                                        </select>
		                                		    </div>
		                                		</div>

		                                		<div class="col-md-6" id="MarksSecuredIn{{$i}}">
		                                			<small class="text-muted"><i class="fa fa-graduation-cap"></i> &nbsp; Marks Secured In</small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control marks_secured{{$i}}" name="marks_unit_db[]" required>
				                                            <option value="1" {{ $education->marks_unit == 1 ? 'selected' : '' }}>Percentage</option>
				                                            <option value="2" {{ $education->marks_unit == 2 ? 'selected' : '' }}>CGPA</option>
				                                        </select>
				                                        <input type="text" class="form-control marks_secured{{$i}}" name="marks_db[]" value="{{ $education->marks }}" placeholder="Marks Secured" required>
		                                		    </div>
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
								<button type="button" class="btn btn-outline-primary" id="addEducationField">Add Another Education Field</button>
								<a href="{{ route('admin.job-seekers.education',['id' => $id]) }}"
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

<div class="modal fade modal-info" id="deleteEducation">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Education?</h5>
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
			i = $('#educationCount').val();

            $('#addEducationField').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('admin/job-seekers/profile/'.$id.'/education/add-educations') }}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#educations_field').append($response.responseText);
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
                $('#education'+button_id).remove();  
            });  

        });

        $(".student_here_checkbox").each(function(){
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);

        	if($(this).is(':checked')){
        		$("#MarksSecuredIn"+checkbox_id).slideUp();
        		$("#YearGradStartId"+checkbox_id).html('Started Year');
        		$(".marks_secured"+checkbox_id).attr('required',false);
        	}else{
        		$("#MarksSecuredIn"+checkbox_id).slideDown();
        		$("#YearGradStartId"+checkbox_id).html('Graduation Year');
        		$(".marks_secured"+checkbox_id).attr('required',true);
        	}
        });

        $(document).on('click', '.student_here_checkbox', function(){
        	
        	var checkbox_id = $(this).attr("id");
        	console.log(checkbox_id);
        	if($(this).is(':checked')){
        		$("#MarksSecuredIn"+checkbox_id).slideUp();
        		$("#YearGradStartId"+checkbox_id).html('Started Year');
        		$(".marks_secured"+checkbox_id).attr('required',false);
        	}else{
        		$("#MarksSecuredIn"+checkbox_id).slideDown();
        		$("#YearGradStartId"+checkbox_id).html('Graduation Year');
        		$(".marks_secured"+checkbox_id).attr('required',true);
        	}
        });

        function delete_education(id) {
        	var conn = "{{ url('admin/job-seekers/profile/'.$id.'/educations/delete') }}/"+id;

            $('#deleteEducation a').attr("href", conn);
        }

    </script>

@endsection