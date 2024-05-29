@extends('admin/layouts.header-sidebar')
@section('title', isset($job_category) ? $job_category->title : 'Job Categories')
@section('content')
    <script>
        $(document).ready(function () {
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');

                $.ajax({
                    method: "POST",
                    url: "{{ URL::route('order_job_categories')}}",
                    data: {
                        '_token': $('input[name=_token]').val(),
                        list_order: list.nestable('serialize'),
                        parent_id: $('#parentId').val(),
                        table: "job_categories"
                    },
                    success: function (response) {
                        console.log("success");
                        console.log("response " + response);
                        var obj = jQuery.parseJSON(response);
                        if (obj.status == 'success') {
                            swal({
                                title: 'Success!',
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-success",
                                html: '<b>Content</b> Sorted Successfully',
                                timer: 1000,
                                type: "success"
                            }).catch(swal.noop);
                        }
                        ;

                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    sweetAlert('Failure', 'Something Went Wrong!', 'error');
                });
            };

            $('#nestable').nestable({
                group: 1,
                maxDepth: 1,
            }).on('change', updateOutput);
        });
    </script>

    <?php
    function displayList($list)
    {
    ?>
    <ol class="dd-list">
        <?php

        foreach ($list as $item):
        ?>
        <li class="dd-item dd3-item" data-id="{{ $item->id }} ">
            <div class="dd-handle dd3-handle"></div>
            <div class="dd3-content">
                <b>{{ $item->title }}</b>&nbsp;|&nbsp; 
                <small>
                    <i>
                        @if($item->display == 1)
                        <span class="badge badge-success mr-0 ml-0" style="font-size: 7px;">Displayed</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Displayed</span>
                        @endif

                    </i>
                </small>
                <span class="content-right">

                    <a href="{{ url('admin/job-categories/edit/'.base64_encode($item->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>

                    <a href="#delete"
                       data-toggle="modal"
                       data-id="{{ $item->id }}"
                       id="delete{{ $item->id }}"
                       title="Delete" 
                       class="btn btn-sm btn-outline-danger center-block"
                       onClick="delete_menu('{{ base64_encode($item->id) }}')"><i class="fa fa-trash  "></i>
                   </a>
                </span>
            </div>

        </li>
        <?php
        endforeach; ?>
    </ol>
    <?php
    }
    ?>

    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">

                    <h2>Job Categories</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard')  }}"><i class="fa fa-tachometer"></i> Dashboard</a>
                            </li>
                            
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.job_categories') }}"><i class="fa fa-anchor"></i> Job Categories</a>
                            </li>
                            
                            @if($id != 0)
                            <li class="breadcrumb-item active" aria-current="page">{{ isset($job_category) ? $job_category->title : 'Job Categories' }}</li>
                            @endif
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                    @if(base64_decode(request()->route()->parameter('parent_id')) == '')
                        <a href="{{ $id != 0 ? route('admin.job_categories') : route('dashboard') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                            <i class="fa fa-angle-double-left"></i> Go Back
                        </a>
                    @else
                        <a href="{{ route('admin.job_categories') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                            <i class="fa fa-angle-double-left"></i> Go Back
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    @if($id == 0)
                    <li class="nav-item"><a class="nav-link show  active" data-toggle="tab" href="#JobCategories">{{ isset($job_category) ? $job_category->title : 'Job Categories' }}</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addJobCategory">
                            {{ $id == 0 ? 'Add Job Category' : 'Update Job Category '}}
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-0">
                    @if($id == 0)
                    <div class="tab-pane show active" id="JobCategories">
                        <div class="card">
                            <div class="header card-header">
                                <h6 class="title mb-0">All {{ isset($job_category) ? $job_category->title : 'Job Categories' }}</h6>
                            </div>
                            <div class="body mt-0">
                                <div class="dd nestable-with-handle" id="nestable">
                                    <?php isset($job_categories) ? displayList($job_categories) : '' ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif
                    <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addJobCategory">
                        <div class="card">
                            <div class="header card-header">
                                <h6 class="title mb-0">{{ isset($job_category) ? $job_category->title : 'Add Job Categories' }}</h6>
                            </div>
                            <div class="body mt-2">
                                <form method="post" action="{{ $id == 0 ? route('admin.job_categories.create') : route('admin.job_categories.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="jobCategoryId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                    <input type="hidden" id="parentId" name="parent_id" value="{{ isset($job_category) ? $job_category->id : 0 }}">
                                    @if($id == 0)
                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                    @else
                                    <input type="hidden" name="url" value="{{ url()->previous() }}">
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                                </div>
                                                <input type="text" name="title" class="form-control"  required value="{{ $id != 0 ? $job_category->title : '' }}" placeholder="eg: Enter Job Category Title Here..">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <?php $display =  $id != 0 ? $job_category->display : 0  ?>
                                                        <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                                <input type="button " class="form-control bg-indigo text-muted" value="Display" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default"><i class="fa fa-image"></i> &nbsp;Images</span>
                                                </div>
                                                <input type="file" name="image" class="bg-primary text-white form-control">

                                            </div>
                                        </div>

                                        @if($id != 0 && $job_category->image != '')
                                            <img class="img-thumbnail" width="68" src="{{ asset('storage/job-categories/thumbs/thumb_'.$job_category->image) }}">
                                        @endif

                                        <div class="card">
                                            <div class="card-header" style="background: #d2d6de;">
                                                <b class="title mb-0">Related Skills with this Job Category</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" id="skills_field">
                                                    <?php
                                                        if ($id != 0) {
                                                            $skills = $job_category->skill;
                                                            ?>
                                                            <input type="hidden" id="skillCount" value="<?=count($skills)+1;?>">
                                                            <?php 
                                                                foreach ($skills as $i => $skill) {
                                                                    ?>
                                                                    <input type="hidden" name="skill_id[]" value="{{ $skill->id }}">
                                                                    <div class="col-md-3" id="row{{$i+1}}">
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" name="skill_db[]" placeholder="Skills Related to Job Category" class="form-control name_list" value="{{ $skill->title }}" required/>
                                                                            @if($skills->count() > 1)
                                                                            <div class="input-group-prepend">
                                                                                <a href="#deleteSkill"
                                                                                   data-toggle="modal"
                                                                                   data-id="{{ $skill->id }}"
                                                                                   id="delete_team{{ $skill->id }}"
                                                                                   class="btn btn-sm btn-danger delete"
                                                                                   onclick="delete_skill('{{ base64_encode($skill->id) }}')">
                                                                                   <i class=" fa fa-trash"></i>
                                                                                </a>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            ?>
                                                            <?php
                                                        }
                                                        // else{
                                                            ?>
                                                            <!-- <div class="col-md-4" >
                                                                <div class="input-group mb-3">
                                                                    <input type="text" name="skills[]" placeholder="Skills Related to Job Category" class="form-control name_list" required/>
                                                                </div>
                                                            </div> -->

                                                           <!--  <?php
                                                        // }
                                                    ?> -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button type="button" name="addSkills" id="addSkills" class="btn btn-success">Add More</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            @if ($id != 0)
                                            <a href="{{ route('admin.job_categories') }}"
                                            class="btn btn-outline-danger">CANCEL</a>

                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                            @else
                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="clearfix"></div>
            

        </div>

        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content bg-danger">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Delete Job Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-white">
                        <p>Are you Sure...!!</p>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                        <a href="" class="btn btn-round btn-primary">Delete</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade modal-primary" id="deleteSkill">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Skill of this Job Category?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure...!!</p>
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
            if ($("#jobCategoryId").val() != '') {
                i = $('#skillCount').val();
            }

            $('#addSkills').click(function(){  
                i++;

                $.ajax({
                    url : "{{url('admin/job-categories/addSkills/')}}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#skills_field').append($response.responseText);
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
                $('#row'+button_id).remove();  
            });  

        });


        function delete_menu(id) {
            var conn = '{{ url("admin/job-categories/delete/")}}/' + id;
            $('#delete a').attr("href", conn);
        }

        function delete_skill(id) {
            var conn = '{{ url("admin/job-categories/skills/delete/")}}/' + id;
            $('#deleteSkill a').attr("href", conn);
        }

    </script>

@endsection
