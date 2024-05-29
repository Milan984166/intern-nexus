@extends('admin/layouts.header-sidebar')
@section('title', isset($product) ? $product->title : 'Products')
@section('content')
<script>
    $(document).ready(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');

            $.ajax({
                method: "POST",
                url: "{{ URL::route('order_products')}}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    list_order: list.nestable('serialize'),
                    parentid: "{{ isset($page)?$page->id:0 }}",
                    table: "pages"
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
                            html: '<b>Products</b> Sorted Successfully',
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
                    <small>
                        <b>{{ $item->title }}</b>&nbsp;
                        @if($item->display == 1)
                        <span class="badge badge-success mr-0 ml-0" style="font-size: 7px;">Displayed</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Displayed</span>
                        @endif

                        @if($item->featured == 1)
                        <span class="badge badge-info mr-0 ml-0" style="font-size: 7px;">Featured</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Featured</span>
                        @endif

                        @if($item->stock == 1)
                        <span class="badge badge-warning mr-0 ml-0" style="font-size: 7px;">Available</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Out of Stock</span>
                        @endif

                    </small>
                    <span class="content-right">
                        <a href="#viewModal"
                        class="btn btn-sm btn-outline-success" data-toggle="modal"
                        data-id="{{ $item->id }} "
                        id="view{{ $item->id }}"
                        data-short_content = '{{ addslashes($item->short_content) }}'
                        onclick="view('{{ $item->id }}','{{ $item->title }}','{{ $item->slug }}', '{{ $item->display }}','{{ $item->image }}','{{ $item->featured }}','{{ $item->stock }}')"
                        title="View"><i class="fa fa-eye"></i></a>
                        <a href="{{ url('admin/products/edit/'.base64_encode($item->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>
                        <a href="#delete"
                        data-toggle="modal"
                        data-id="{{ $item->id }}"
                        id="delete{{ $item->id }}"
                        class="btn btn-sm btn-outline-danger center-block"
                        onClick="delete_menu('{{ base64_encode($item->id) }}')"><i class="fa fa-trash  "></i></a>
                    </span>
                </div>

                <?php if (array_key_exists("children", $item)): ?>
                    <?php displayList($item->children); ?>
                <?php endif; ?>
            </li>
            <?php
        endforeach; ?>
    </ol>
    <?php
}

function displayCategories($list, $selectedCategories){
    foreach ($list as $item){
        if ($item->parent_id != 0) {
            $parent = DB::table('categories')->where('id',$item->parent_id)->first();
            $item->title = $parent->title.' → '.$item->title;
        }
        ?>
        <option 
            <?=$item->child == 1 ? 'disabled' : '' ?> 
            @php 
                if ($selectedCategories != 0 && in_array($item->id, $selectedCategories)) {
                    echo "selected";
                }
            @endphp 
            value="{{$item->id}}">
                {{ $item->title}}
        </option>
        <?php if (array_key_exists("children", $item)){
            displayCategories( $item->children, $selectedCategories);
        }
    }
}
?>

<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}"><i class="icon-speedometer"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products') }}"><i class="icon-layers"></i> Products</a></li>
                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? $product->title : 'Products' }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin') }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">

            <ul class="nav nav-tabs">
                @if($id == 0)
                <li class="nav-item">
                    <a class="nav-link show  active" data-toggle="tab" href="#Pages">{{ isset($product) ? $product->title : 'Products' }}</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPage">{{ $id == 0 ? 'Add Product' : 'Update Product' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                @if($id == 0)
                <div class="tab-pane show active" id="Pages">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">All {{ isset($product) ? $product->title : 'Products' }}</h6>
                        </div>
                        <div class="body mt-0">
                            <div class="dd nestable-with-handle" id="nestable">
                                <?php isset($products) ? displayList($products) : '' ?>
                            </div>
                        </div>
                    </div>

                </div>
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">{{ isset($product) ? $product->title : 'Add Products' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form method="post" action="{{ $id == 0 ? route('admin.products.create') : route('admin.products.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="productId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                            </div>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Product Title Here" required value="{{ $id != 0 ? $product->title : old('title') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $display =  $id != 0 ? $product->display : old('display')  ?>
                                                    <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Display" disabled>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $featured =  $id != 0 ? $product->featured : old('featured')  ?>
                                                    <input type="checkbox" name="featured" value="1" <?=$featured == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Featured" disabled>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $stock =  $id != 0 ? $product->stock : old('stock')  ?>
                                                    <input type="checkbox" name="stock" value="1" <?=$stock == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Check if Available" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <select name="categories[]" class="form-control select2" multiple="multiple" data-placeholder="Select Product Categories" style="width: 100%;" required>
                                                @php
                                                    $selectedCategories = ($id != 0 ? json_decode($product->categories) : old('categories'));

                                                @endphp
                                                {{ displayCategories($categories, $selectedCategories) }}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-barcode"></i> &nbsp;SKU</span>
                                            </div>
                                            <input type="text" name="sku" class="form-control" placeholder="Enter Product SKU # Here" required value="{{ $id != 0 ? $product->sku : old('sku') }}">
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" style="background: #d2d6de;">
                                            <p class="title mb-0">Enter <b>Price</b> & <b>Barrels</b> for the product</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Original Price</span>
                                                        </div>
                                                        <input type="text" name="originalPrice" class="form-control" placeholder="Product's Original Price" required value="{{ $id != 0 ? $product->originalPrice : old('originalPrice') }}">
                                                    </div>
                                                </div>  

                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Discounted Price</span>
                                                        </div>
                                                        <input type="text" name="discountedPrice" class="form-control" placeholder="Product's Discounted Price" value="{{ $id != 0 ? $product->discountedPrice : old('discountedPrice') }}">
                                                    </div>
                                                </div>  
                                            </div>    
                                            <div class="row" id="youtube_field">
                                                <?php
                                                    if ($id != 0) {
                                                        $barrels = json_decode($product->barrels);
                                                        ?>
                                                        <input type="hidden" id="barrelCount" value="<?=count($barrels)+1;?>">
                                                        <?php 
                                                            for ($i=0; $i < count($barrels); $i++){
                                                                ?>
                                                                <div class="col-md-2" style="padding-bottom: 5px;" id="row{{$i+1}}">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="barrels[]" placeholder="BBL" class="form-control name_list" value="{{ $barrels[$i] }}" required/>
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" style="background-color: #d2d6de;">
                                                                                BBL
                                                                            </span>
                                                                            <button type="button" name="remove" id="{{$i+1}}" class="btn btn-danger btn_remove">
                                                                                <i class=" fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        ?>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        
                                                        <div class="col-md-2" style="padding-bottom: 5px;">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="barrels[]" placeholder="BBL" class="form-control name_list" required/>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" style="background-color: #d2d6de;">
                                                                        BBL
                                                                    </span>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <button type="button" name="addBarrel" id="addBarrel" class="btn btn-success">Add More</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" style="background: #d2d6de;">
                                            <p class="title mb-0">
                                                <strong>Add Images</strong>
                                                <small class="pull-right">
                                                    <i>Best Image Size :  1200px X 1200px </i>
                                                </small>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <i class="fa fa-image"></i> &nbsp; Featured Image
                                                        </div>
                                                        <div class="alert alert-success border-success">    
                                                            <input type="file" name="image" class="dropify bg-primary form-control" <?=$id == 0 ? 'required' : '' ?>>      
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <i class="fa fa-image"></i> &nbsp; Gallery Images
                                                        </div>
                                                        <div class="alert alert-info border-info">    
                                                            <input type="file" class="dropify bg-info form-control" name="other_images[]" multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-4"></div> -->
                                                @if($id != 0)
                                                <div class="col-md-4">
                                                    
                                                    <div class="alert alert-success">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-image"></i> 
                                                                    &nbsp;Current <br> Featured <br>Image
                                                                </span>
                                                            </div>
                                                            <img class="img-thumbnail" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}">
                                                        </div>
                                                    </div>
                                                
                                                </div>

                                                <div class="col-md-8">
                                                
                                                    <div class="alert alert-info">
                                                        <div class="input-group">
                                                            
                                                            <?php
                                                                $images = Storage::files('public/products/'.$product->slug.'/gallery/');
                                                            ?>
                                                            @for ($i = 0; $i < count($images); $i++)
                                                                @if(strpos($images[$i], $product->image) != true)
                                                                
                                                                    <a href="#delete_image" data-toggle="modal"
                                                                       data-photo=""
                                                                       onclick="delete_image('<?= basename($images[$i]); ?>')"
                                                                       id="" title="Delete Image">
                                                                        <i style="position: absolute; top: -9px;padding: 4px; color: #fff;border-radius: 50%; opacity: 1;" class="btn-danger close fa fa-trash"></i>
                                                                    </a>
                                                                    <img class="img-thumbnail" src="{{ asset('storage/').str_replace('public/products/'.$product->slug.'/gallery/','/products/'.$product->slug.'/gallery/thumbs/thumb_',$images[$i])}}" alt="no-image" style="max-width: 100px; margin-right: 10px;">
                                                                
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" style="background: #d2d6de;">
                                            <p class="title mb-0">
                                                <strong>Add Necessary Content about product</strong>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-file-text-o"></i> 
                                                                &nbsp;Short Content
                                                            </span>
                                                        </div>
                                                        <textarea class="ckeditor" name="short_content">{{ $id != 0 ? $product->short_content : old('short_content') }}</textarea>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-file-text"></i> 
                                                                &nbsp;Long Content
                                                            </span>
                                                        </div>
                                                        <textarea class="ckeditor" name="long_content">{{ $id != 0 ? $product->long_content : old('long_content') }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        @if ($id != 0)
                                        <a href="{{ route('admin.products') }}"
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
        <div class="col-md-12">

        </div>

    </div>

    <div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>View Product
                        <span id="viewDisplay"></span>
                        <span id="viewFeatured"></span>
                        <span id="viewStockStatus"></span>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pricing_page text-center pt-4 mb-4">
                        <div class="card ">
                            <div class="card-header">
                                <h5 id="PageTitle"></h5>
                                <small class="text-muted" id="viewContent"></small>
                            </div>
                            <div class="card-body">
                                <img id="viewImage" class="img-fluid"
                                src="https://via.placeholder.com/1584x1058?text=Sample + Image + For + Product">
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

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product</h5>
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

    <div class="modal fade modal-danger" id="delete_image">
        <div class="modal-dialog " role="document">
                <div class="modal-content bg-warning">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Delete Gallery Image</h5>
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

</div>


@endsection
@section('script')
<script>
    $(document).ready(function(){
        var i=1;
        if ($("#productId").val() != '') {
            i = $('#barrelCount').val();
        }

        $('#addBarrel').click(function(){  
            i++;

            $.ajax({
                url : "{{url('admin/products/addBarrel/')}}/"+i,
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        $('#youtube_field').append($response.responseText);
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

    function view(id, title, slug, status, image, featured, stock) {
        short_content = $("#view"+id).attr('data-short_content');
        $('#viewId').val(id);
        $('#PageTitle').html(title);
        $('#viewContent').html(short_content);

        if (status == 0) {
            $('#viewDisplay').html('<small class="badge badge-danger">Not Displayed</small>');
        } else {
            $('#viewDisplay').html('<small class="badge badge-success">Displayed</small>');
        }

        if (featured == 0) {
            $('#viewFeatured').html('<small class="badge badge-warning">Not Featured</small>');
        } else {
            $('#viewFeatured').html('<small class="badge badge-info">Featured</small>');
        }

        if (status == 0) {
            $('#viewStockStatus').html('<small class="badge badge-danger">Out of Stock</small>');
        } else {
            $('#viewStockStatus').html('<small class="badge badge-warning">Available</small>');
        }

        $('#viewImage').attr('src', "{{ asset('storage/products/')}}/"+slug+"/thumbs/thumb_"+ image);
    }

    function delete_menu(id) {
        var conn = './products/delete/' + id;
        $('#delete a').attr("href", conn);
    }

    $(function () {
        $(".select2").select2({
            allowClear: true
        });
    });

</script>

@if($id != 0)
<script>
    function delete_image(image) {

        var conn = '../delete-image/<?=$product->slug;?>/' + image;
        $('#delete_image a').attr("href", conn);
    }

</script>
@endif

@endsection
