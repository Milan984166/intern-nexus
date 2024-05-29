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
                        <li class="breadcrumb-item"><a href="{{ route('admin.job-seekers.training',['id' => $id]) }}">Training</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Training</li>
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
						<i class="fa fa-tasks"></i>
						Education
					</a>
					
					<a href="{{ url('admin/job-seekers/profile/'.base64_encode($jobseeker->id).'/training') }}" class="btn btn-block btn-round text-left btn-primary disabled">
						<i class="fa fa-graduation-cap"></i>
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
							<h5>Training</h5>
							<small>Edit | Update Training information</small>
						</div>                                
					</div>
				</div>
				<form method="post" action="{{ route('admin.job-seekers.update-training',['id' => $id]) }}">
					@csrf
					<input type="hidden" id="trainingCount" value="<?=$trainings->count()+1;?>">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12" id="trainings_field">
								@if($trainings->count() == 0)
			                    <div class="card" id="training1">
	                                <div class="card-body">
	                                	<div class="row">
	                                		<div class="col-md-6">
	                                		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of the Training* </small>
	                                		        <input type="text" name="name[]" class="form-control" required value="" placeholder="eg: Training of Branch Management">
	                                		        <hr>
	                                		    
	                                		</div>
	                                		<div class="col-md-6">
	                            		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of Institution*</small>
	                            		            
	                            		        <input type="text" name="institution[]" class="form-control" required value="" placeholder="eg: Creators Institute of Business and Technology">
	                            		        <hr>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Duration*</small>
	                                		    <div class="input-group mb-3">
	                                		    	<input type="number" min="0" class="form-control" name="duration[]" required>

	                                		        <select class="form-control" name="duration_unit[]" required>
			                                            <option value="1">Day</option>
			                                            <option value="2">Week</option>
			                                            <option value="3">Month</option>
			                                            <option value="4">Year</option>
			                                        </select>
	                                		    </div>
	                                		</div>

	                                		<div class="col-md-6">
	                                			<small class="text-muted">
	                        		                <i class="fa fa-tasks"></i> &nbsp; Completion Year*
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

	                                	</div>
	                                </div>
			                    </div>
			                    @else
			                    	@foreach($trainings as $i => $training)
			                    	<input type="hidden" name="training_id[]" value="{{ $training->id }}">
			                    	<div class="card" id="training{{ $i }}">
		                                <div class="card-body">
		                                	<div class="row">
		                                		@if($trainings->count() > 1)
		                                		<div class="offset-md-10 col-md-2 text-right">
		                                			<a href="#deleteTraining"
		                                			   data-toggle="modal"
		                                			   data-id="{{ $training->id }}"
		                                			   id="delete_training{{ $training->id }}"
		                                			   class="btn btn-sm btn-danger delete"
		                                			   onclick="delete_training('{{ base64_encode($training->id) }}')">
		                                			   <i class=" fa fa-trash"></i>
		                                			</a>
		                                		</div>
		                                		@endif
		                                		<div class="col-md-6">
		                                		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of the Training* </small>
		                                		        <input type="text" name="name_db[]" class="form-control" required value="{{ $training->name }}" placeholder="eg: Training of Branch Management">
		                                		        <hr>
		                                		    
		                                		</div>
		                                		<div class="col-md-6">
		                            		        <small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Name of Institution*</small>
		                            		            
		                            		        <input type="text" name="institution_db[]" class="form-control" required value="{{ $training->institution }}" placeholder="eg: Creators Institute of Business and Technology">

		                            		        <hr>
		                                		</div>

		                                		<div class="col-md-6">
		                                			<small class="text-muted"><i class="fa fa-tasks"></i> &nbsp; Duration*</small>
		                                		    <div class="input-group mb-3">
		                                		    	<input type="number" min="0" class="form-control" name="duration_db[]" value="{{ $training->duration }}" required>

		                                		        <select class="form-control" name="duration_unit_db[]" required>
				                                            <option value="1" {{ $training->duration_unit == 1 ? 'selected' : '' }}>Day</option>

				                                            <option value="2" {{ $training->duration_unit == 2 ? 'selected' : '' }}>Week</option>

				                                            <option value="3" {{ $training->duration_unit == 3 ? 'selected' : '' }}>Month</option>

				                                            <option value="4" {{ $training->duration_unit == 4 ? 'selected' : '' }}>Year</option>

				                                        </select>
		                                		    </div>
		                                		</div>

		                                		<div class="col-md-6">
		                                			<small class="text-muted">
		                        		                <i class="fa fa-tasks"></i> &nbsp;Completion Year*
		                        		            </small>
		                                		    <div class="input-group mb-3">
		                                		        <select class="form-control" name="year_db[]">
		                                		        	<option value="">Year</option>
		                                		        	@for($year = date('Y'); $year >= date('Y')-50; $year--)
				                                            <option value="{{$year}}" {{ $training->year == $year ? 'selected' : '' }}>{{$year}}</option>
				                                            @endfor
				                                        </select>
				                                        @php
				                                        	$months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December', ]
				                                        @endphp
				                                        <select class="form-control" name="month_db[]">
		                                		        	<option disabled value="">Month</option>

		                                		        	@foreach($months as $key => $month)
				                                            <option value="{{$key}}" {{ $training->month == $key ? 'selected' : '' }}>{{$month}}</option>
				                                            @endforeach
				                                        </select>
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
								<button type="button" class="btn btn-outline-primary" id="addTrainingField">Add Another Training Field</button>
								<a href="{{ route('admin.job-seekers.training',['id' => $id]) }}"
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

<div class="modal fade modal-info" id="deleteTraining">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Training?</h5>
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
			i = $('#trainingCount').val();

            $('#addTrainingField').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('admin/job-seekers/profile/'.$id.'/training/add-trainings') }}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#trainings_field').append($response.responseText);
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
                $('#training'+button_id).remove();  
            });  

        });


        function delete_training(id) {
        	var conn = "{{ url('admin/job-seekers/profile/'.$id.'/trainings/delete') }}/"+id;

            $('#deleteTraining a').attr("href", conn);
        }

    </script>

@endsection