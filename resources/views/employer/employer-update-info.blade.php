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
		<h1>Update Information</h1>
	</div>
</section>
<!--INNER BANNER END--> 

<!--MAIN START-->

<div id="main">
	@if($employer_info)
	<section class="recent-row padd-tb job-detail">
		<div class="container">
			<div class="row">
				<div class="col-md-9 ">
					<div id="content-area">
						<div class="box">
							<div class="thumb">
								<a href="{{ route($employer_type.'.profile') }}">
									@if($employer_info->image != '')
									<img width="165" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" alt="no-image">
									@else
									<img class="img-thumbnail" width="165" src="{{ asset('images/user.jpg') }}" alt="img">
									@endif
								</a>
							</div>
							<div class="text-col">
								<h2>
									<a href="{{ route($employer_type.'.profile') }}">{{ $employer_info->organization_name }}</a>
								</h2>

								@if($employer_info->category_id != 0)
								<ul class="company-small">
									<li>
										<strong>Industry:</strong> 
										{{ @$employer_info->employer_category->title }}
									</li>
									<li>
										<strong>Type of Business Entity:</strong> 
										@php
										$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
										@endphp
										{{ @$ownershipArray[$employer_info->ownership_type] }}
									</li>


									@if($employer_info->organization_size != '')
									<li>
										<strong>No. of Employees:</strong>
										@php
										$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
										@endphp
										<p>{{ @$OrgSizeArray[$employer_info->organization_size] }}</p>
									</li>
									@endif
									<li><strong>Address :</strong> {{ $employer_info->address }} </li>
								</ul>

								<a href="{{ url('company/posted-jobs') }}" class="text"><em>(View All Jobs)</em></a>
								
								@else
								<div class="panel panel-info">
									<div class="panel-body text-center">
										<small style="color: red;">Please Update Your Profile</small>
									</div>
								</div>
								@endif
							</div>

						</div>
					</div>
				</div>
				@if($employer_info->category_id != 0)
				<div class="col-md-3 col-sm-3">
					<aside>
						<div class="sidebar">
							<div class="sidebar-jobs">
								<ul>
									
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.profile') }}"> Company Profile</a>
									</li>
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.posted-jobs') }}"> Posted Jobs</a>
									</li>
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.post_job') }}"> Post New Job</a>
									</li>
									<li>
										<a class="btn-style-1" href="{{ route($employer_type.'.edit-information') }}"> Edit Profile</a>
									</li>
								</ul>
							</div>
						</div>
					</aside>
				</div>
				@endif
			</div>
		</div>
	</section>
	@endif

	<!--RESUME FORM START-->
	<section class="resum-form padd-tb">
		<div class="container">
			<form method="POST" action="{{ route($employer_type.'.update-information') }}" enctype="multipart/form-data">
				@csrf
				<div class="panel panel-info">	
					<div class="panel-body">
					
						<div class="row">
							<div class="col-md-6">
								<label><i class="fa fa-user"></i> Organization Name: </label>
								<input type="text" name="organization_name" class="form-control" required value="{{ old('organization_name') ? old('organization_name') : (isset($employer_info->organization_name) ? $employer_info->organization_name : $employer->name ) }}" placeholder="eg: KTM RUSH PVT. LTD.">	
							</div>
							<div class="col-md-6">
								<label><i class="fa fa-envelope"></i> Company Email: </label>
								<input type="email" name="email" class="form-control" required value="{{ old('email') ? old('email') : (isset($employer_info->email) ? $employer_info->email : $employer->email ) }}" disabled readonly placeholder="eg: hello@example.com">
							</div>
							<div class="col-md-6">
								<label><i class="fa fa-map-marker"></i> Company Address: </label>
								<input type="text" name="address" class="form-control" required value="{{ old('address') ? old('address') : (isset($employer_info->address) ? $employer_info->address : $employer->current_address ) }}" placeholder="eg: Kathmandu, Nepal">	
							</div>
							<div class="col-md-6">
								<label><i class="fa fa-phone"></i> Contact Number: </label>
								<input type="text" name="phone" class="form-control" required value="{{ old('phone') ? old('phone') : (isset($employer_info->phone) ? $employer_info->phone : $employer->phone ) }}" placeholder="eg: +977 9876543210">	
							</div>
							<div class="col-md-6">
                    			<label>
            		                <i class="fa fa-building"></i> &nbsp; Industry*
            		            </label>
                    		    
                		    	@php
									$jobCategories = \App\JobCategory::where('display',1)->orderBy('order_item')->get();

                                @endphp
                		        <select class="ui dropdown" name="category_id" required>

                		        	<option selected disabled>Select Company Category</option>

                		        	@foreach($jobCategories as $jobCat)
                                    	<option {{ @$employer_info->category_id == $jobCat->id ? 'selected' : (old('category_id') == $jobCat->id ? 'selected' : '') }} value="{{ $jobCat->id }}">{{ $jobCat->title }}</option>
                                    @endforeach
                                    

                                </select>
                    		    
                    		</div>
                    		<div class="col-md-6">
                    			<label>
            		                <i class="fa fa-building"></i> &nbsp; Ownership
            		            </label>
                    		    
                		    	@php
                		    		$ownershipArray = array('1' => 'Government', '2' => 'Private', '3' => 'Public' , '4' => 'Non Profit');
                		    	@endphp
                		        <select class="ui dropdown" name="ownership_type">

                		        	<option value="" selected>--------------------</option>

                		        	@foreach($ownershipArray as $key => $look)
                                    <option {{ @$employer_info->ownership_type == $key ? 'selected' : (old('ownership_type') == $key ? 'selected' : '') }} value='{{ $key }}'>{{ $ownershipArray[$key] }}</option>
                                    @endforeach
                                    

                                </select>

                    		</div>

                    		<div class="col-md-6">
                    			<label>
            		                <i class="fa fa-building"></i> &nbsp; Size of Organization*
            		            </label>
                		    
                		    	@php
                		    		$OrgSizeArray = array('1' => '1 - 10 employees', '2' => '10 - 50 employees', '3' => '50 - 100 employees' , '4' => '100 - 200 employees', '5' => '200 - 500 employees', '6' => '500 - 1000 employees', '7' => '1000+ employees', '8' => 'Confidential');
                		    	@endphp
                		        <select class="ui dropdown" name="organization_size" >

                		        	<option value="" selected>--------------------</option>

                		        	@foreach($OrgSizeArray as $key => $look)
                                    <option {{ @$employer_info->organization_size == $key ? 'selected' : (old('organization_size') == $key ? 'selected' : '') }} value='{{ $key }}'>{{ $OrgSizeArray[$key] }}</option>
                                    @endforeach
                                    

                                </select>
                		    
                    		</div>
                    		<div class="col-md-6">
								<label><i class="fa fa-globe"></i> Website: </label>
								<input type="text" name="website" class="form-control" value="{{ old('website') ? old('website') : @$employer_info->website }}" placeholder="eg: https://www.internnexus.com">	
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label><i class="fa fa-twitter"></i> Twitter </label>
								<input type="text" name="twitter" class="form-control" value="{{ old('twitter') ? old('twitter') : @$employer_info->twitter }}" placeholder="eg: https://www.intern-nexus.com/twitter">	
							</div>
							<div class="col-md-6">
								<label><i class="fa fa-linkedin"></i> Linkedin </label>
								<input type="text" name="linkedin" class="form-control" value="{{ old('linkedin') ? old('linkedin') : @$employer_info->linkedin }}" placeholder="eg: https://www.intern-nexus.com/linkedin">	
							</div>
							<div class="col-md-6">
								<label><i class="fa fa-facebook-f"></i> Facebook </label>
								<input type="text" name="facebook" class="form-control" value="{{ old('facebook') ? old('facebook') : @$employer_info->facebook }}" placeholder="eg: https://www.intern-nexus.com/facebook">	
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
                    			<label>
            		                <i class="fa fa-building"></i> &nbsp; Profile Image*
            		            </label>
                                <div class="upload-box">
									<div class="hold">
										<a style="padding-left: 5px;" href="javascript:void(0)">Max file size 2MB(.jpg, .jpeg, .png)</a>
										<span class="btn-file"> Browse File
											<input type="file" name="image" accept=".jpg,.jpeg,.png" {{ (@$employer_info->image == '' ? 'required' : '' ) }} >
										</span>
									</div>
								</div>
                            </div>

                            <div class="col-md-6">
                            	<label>
            		                <i class="fa fa-building"></i> &nbsp; Current Image*
            		            </label>
                                <div class="input-group mb-3">
                                	@if(@$employer_info->image != '')
                                    <img width="100" src="{{ asset('storage/employers/company-info/thumbs/small_'.$employer_info->image) }}" class="img-thumbnail" alt="no-image">
                                    @else
                                    	<img class="img-thumbnail" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                                    @endif
                                </div>
                            </div>

							<div class="col-md-12">
                    			<label>
            		                <i class="fa fa-building"></i> &nbsp; About*
            		            </label>
            		            <textarea name="about" id="editor1" class="form-control ckeditor" required>{{ old('about') ? old('about') : @$employer_info->about }}</textarea>
                    		</div>
						</div>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<label>Edit Contact Person Information</label>
							</div>	
						</div>
						<hr>
						<div class="row">

							<div class="col-md-6">
								<label><i class="fa fa-user"></i> Name* </label>
								<input type="text" name="cp_name" class="form-control" required value="{{ old('cp_name') ? old('cp_name') : @$employer_info->cp_name }}" placeholder="eg: John Doe">	
							</div>

							<div class="col-md-6">
								<label><i class="fa fa-phone"></i> Mobile Number* </label>
								<input type="text" name="cp_contact" class="form-control" required value="{{ old('cp_contact') ? old('cp_contact') : @$employer_info->cp_contact }}" placeholder="eg: 9876543210" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')">
							</div>

							<div class="col-md-6">
								<label><i class="fa fa-user"></i> Designation* </label>
								<input type="text" name="cp_designation" class="form-control" required value="{{ old('cp_designation') ? old('cp_designation') : @$employer_info->cp_designation }}" placeholder="eg: Managing Director">	
							</div>

							<div class="col-md-6">
								<label><i class="fa fa-user"></i> Email </label>
								<input type="email" name="cp_email" class="form-control" value="{{ old('cp_email') ? old('cp_email') : @$employer_info->cp_email }}" placeholder="eg: hello@example.com">	
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="btn-col">

							<input type="submit" value="Update Information"/>
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
        		method : "POST",
            	url : "{{ URL::route($employer_type.'.show-job-categories-skills') }}",
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