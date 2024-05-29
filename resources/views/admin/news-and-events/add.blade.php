@extends('admin/layouts.header-sidebar')
@section('title', isset($newsevent) ? $newsevent->title : 'News And Events')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/summernote/dist/summernote.css') }}"/>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h1>News & Events</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">News & Events</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                    <a href="{{ route('news-and-events.list') }}" class="btn btn-sm btn-primary" title=""><i
                                class="fa fa-list"></i> Show All News & Events</a>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">
                @php
                    $url = route('news-and-events.add');
                if (isset($newsevent)){
                    $url = route('news-and-events.update',['id'=>base64_encode($newsevent->id)]);
                }
                @endphp
                <form method="POST" action="{{ $url }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Title</span>
                                        </div>
                                        <input type="text" class="form-control" name="title"
                                               placeholder="Title" value="{{isset($newsevent)? $newsevent->title:''}}"
                                               aria-label="Title"
                                               aria-describedby="basic-addon1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Image</span>
                                        </div>
                                        <input type="file" class="form-control" name="image">
                                        <label class="badge badge-warning pt-2">Image Size Must be 770px X 400px</label>
                                    </div>
                                    <div style="float: right">
                                        @php($image = isset($newsevent) && $newsevent->image ? asset('storage/news-and-events/'.$newsevent->slug.'/thumbs/small_'.$newsevent->image): '')
                                        <img src="{{$image}}" alt="" class="image-responsive"
                                             style="width: 50px; height: 50px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <input type="checkbox" name="status" {{isset($newsevent) && $newsevent->status == 1 ? 'checked' : ''}} ></span>
                                        </div>
                                        <input type="text" class="form-control" value="Display Status" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                    class="fa fa-file-text-o fa-lg"></i> &nbsp;Excerpt</span>
                                            <textarea class="ckeditor"
                                                      name="short_content">{!! isset($newsevent) ? $newsevent->short_content:'' !!}</textarea>

                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                    class="fa fa-file-text-o fa-lg"></i> &nbsp;Content</span>
                                            <textarea class="ckeditor"
                                                      name="description">{!! isset($newsevent) ? $newsevent->content:'' !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('news-and-events.list') }}" class="btn btn-danger">Cancel</a>
                                <span class="float-right">
                        <button type="submit" name="submit" class="btn btn-success" value="save">Save</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
