@extends('layouts.app')
@section('title',Auth::user()->name)
@push('custom-css')
<link rel="stylesheet" href="{{ asset('frontend/css/semantic.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/editor.css') }}">
@endpush
@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">

	<div class="container">

		<h1>Resume</h1>

	</div>

</section>

<!--INNER BANNER END--> 



<!--MAIN START-->

<div id="main"> 

	<section class="tab-wraps">
		<div class="container">
			<div class="row">
				
				<div class="col-sm-9">
					<div class="tab-body">
						<!-- Tab panes -->
						<div class="tab-content">
							{!! $resume_output !!}
							<!-- end tab content -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<div class="modal fade modal-info" id="deleteModal">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete <span id="deleteTitle"></span>?</h5>
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

<div class="modal fade modal-info" id="deleteWatchListJob">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove from WatchList?</h5>
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
<!-- <script src="{{ asset('frontend/js/form.js') }}"></script> -->

<!--SCROLL FOR SIDEBAR NAVIGATION--> 
<script src="{{ asset('frontend/js/semantic.js') }}"></script>
<script src="{{ asset('frontend/js/editor.js') }}"></script>

<script src="{{ asset('frontend/js/custom.js') }}"></script>

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

<script>
	$('#multi-select').dropdown();

$('.multi-select').dropdown();
</script>

<script>
        $(document).ready(function(){
            var i=1;
			i = $('#educationCount').val();

            $('#addEducationField').click(function(){  
                i++;

                $.ajax({
                	url : "{{ url('jobseeker/education/add-educations') }}/"+i,
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

            $(document).on('click', '.btn_remove_education', function(){  
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
        	var conn = "{{ url('jobseeker/educations/delete') }}/"+id;
        	$('#deleteTitle').html('Education');
            $('#deleteModal a').attr("href", conn);
        }

        // ==================================================================

        $(document).ready(function(){
            var j=1;
			j = $('#experienceCount').val();

            $('#addExperienceField').click(function(){  
                j++;

                $.ajax({
                	url : "{{ url('jobseeker/experience/add-experiences') }}/"+j,
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

            $(document).on('click', '.btn_remove_experience', function(){  
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
        	var conn = "{{ url('jobseeker/experiences/delete') }}/"+id;
        	$('#deleteTitle').html('Work Experience');
            $('#deleteModal a').attr("href", conn);
        }
        
        // ======================================================================

        $(document).ready(function(){
            var k=1;
			k = $('#trainingCount').val();

            $('#addTrainingField').click(function(){  
                k++;

                $.ajax({
                	url : "{{ url('jobseeker/training/add-trainings') }}/"+k,
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

            $(document).on('click', '.btn_remove_training', function(){  
                var button_id = $(this).attr("id");   
                $('#training'+button_id).remove();  
            });  

        });


        function delete_training(id) {
        	var conn = "{{ url('jobseeker/trainings/delete') }}/"+id;
        	$('#deleteTitle').html('Training');
            $('#deleteModal a').attr("href", conn);
        }
        /*=========================================================================================*/

        $(document).ready(function(){
            var m=1;
			m = $('#categorySkillCount').val();

            $('#addCategorySkills').click(function(){  
                m++;

                $.ajax({
                	url : "{{ url('jobseeker/job-preference/add-job-preferences') }}/"+m,
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

            $(document).on('click', '.btn_remove_preference', function(){  
                var button_id = $(this).attr("id");   
                $('#preference'+button_id).remove();  
            });

            function call_ajax_function(select_id, job_category_id, cat_skill_id){
            	$.ajax({
            		url : "{{ url('jobseeker/job-preference/show-job-categories-skills') }}",
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
                            $('.skills_multiselect').dropdown();
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
        	var conn = "{{ url('jobseeker/job-preferences/delete') }}/"+id;
        	$('#deleteTitle').html('Job Preference');
            $('#deleteModal a').attr("href", conn);
        }

        $('.skills_multiselect').dropdown();

        function delete_watchlist_job(id) {
        	var conn = "{{ url('jobseeker/watchlist-jobs/delete') }}/"+id;
            $('#deleteWatchListJob a').attr("href", conn);
        }

    </script>
@endpush