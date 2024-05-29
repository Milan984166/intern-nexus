@extends('admin/layouts.header-sidebar')
@section('title', isset($newsevent) ? $newsevent->title : 'News And Event')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/table-dragger/table-dragger.min.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h1>News & Event Section</h1>
                    <small>Manage Your News & Events From Here</small>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News And Event</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a href="{{ route('news-and-events.show') }}" class="btn btn-primary"><i
                                class="fa fa-plus-circle"></i> Add News & Events</a>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-12">

                <div class="table-responsive">
                    <table id="only-bodytable"
                           class="table table-hover js-basic-example dataTable table-custom spacing5 mb-0 sindu_origin_table">
                        <thead class="text-center">
                        <th>Sort</th>
                        <th class="">Image</th>
                        <th class="">Title</th>
                        <th class="">Status</th>
                        <th class="">Content</th>
                        <th class="">Action</th>
                        </thead>
                        <tbody>
                        @foreach($newsevents as $newsevent)
                            <tr>
                                <td class="handle text-center"><i class="table-dragger-handle">{{$loop->index+1}}</i>
                                </td>
                                <td class="w60">
                                    <div class="avtar-pic w35 bg-red" data-toggle="tooltip" data-placement="top"
                                         title=""
                                         data-original-title="Avatar Name">
                                        <span><img src="{{asset('storage/news-and-events/'.$newsevent->slug.'/thumbs/small_'.$newsevent->image)}}"
                                                   class="img-thumbnail"></span>
                                    </div>
                                </td>
                                <td class="w60 text-center">
                                    {{$newsevent->title}}
                                </td>
                                @if($newsevent->status == 1)
                                    <td class="text-center"><span><a href=""><i
                                                        class="fa fa-toggle-on text-success fd"></i></a></span>
                                    </td>
                                @else
                                    <td class="text-center"><span><i
                                                    class="fa fa-toggle-off text-danger fd"></i></span>
                                    </td>
                                    @endif
                                    </form>
                                    <td class="w60 text-center">{!! Str::limit($newsevent->content, 80) !!}</td>

                                    <td style="text-align: center;">
                                        <a href="#" data-toggle="modal"
                                        data-target="#newsAndEventsView"
                                           onclick="viewNewsAndEvent('{{$newsevent->id}}','{{$newsevent->slug}}','{{$newsevent->title}}','{{$newsevent->image}}','{{$newsevent->status}}')"
                                           data-title="VIEW" 
                                           id="view{{$newsevent->id}}" 
                                           data-short-content='{{$newsevent->short_content}}'
                                           data-content='{{$newsevent->content}}'
                                           class="btn btn-outline-info btn-sm"><i
                                                    class="fa fa-eye"></i></a>
                                        <a href="{{route('news-and-events.edit',['id'=>base64_encode($newsevent->id)])}}"
                                           data-toggle="tooltip"
                                           data-title="EDIT" class="btn btn-outline-success btn-sm"><i
                                                    class="fa fa-edit"></i></a>

                                        <a href="#delete"
                                            data-toggle="modal"
                                            data-id="{{ $newsevent->id }}"
                                            id="delete{{ $newsevent->id }}"
                                            class="btn btn-sm btn-outline-danger center-block"
                                            onClick="delete_menu({{ $newsevent->id }} )"><i class="fa fa-trash  "></i></a>

                                    </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <ul class="pagination mt-2">
                    
                    {{$newsevents->links()}}
                </ul>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete News and Event</h5>
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
    <div class="modal fade " id="newsAndEventsView" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>Contents
                        <span id="viewDisplay">
                            </span>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page text-center pt-4 mb-4">
                    <div class="card ">
                        <div class="card-header">
                            <h5 id="NewsEventTitle"></h5>
                        </div>
                        <div class="card-body">
                            <img id="ViewImage" class="img-fluid"
                                 src="">
                            <hr>
                        </div>
                        <div class="row card-body">
                            <div class="col-md-12" id="">
                                <b>Short_Content:</b>
                                    <br><span id="short_content"></span>
                            </div>
                            <div class="col-md-12" id="">
                                <b>Content:</b>
                                    <br><span id="content"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <button style="text-align: right;" type="button" data-dismiss="modal"
                        class="btn btn-outline-danger">Close
                </button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript"
            src="{{ asset('backend/assets/vendor/table-dragger/table-dragger.min.js') }}"></script>
    <script>
        // tableDragger(document.querySelector("#only-bodytable"), { mode: "row", onlyBody: true });
        var el = document.getElementById('only-bodytable');
        var dragger = tableDragger(el, {
            mode: 'row',
            dragHandler: '.handle',
            onlyBody: true,
        });

        dragger.on('drop', function (el, mode) {
            console.log(el);
            console.log(mode);
        });

        function DisplayOption() {
            var x = document.getElementById("OptionControl");
            if (x.style.display === "none") {
                x.style.display = ("block");
            } else {
                x.style.display = ("none");
            }
        }

        function viewNewsAndEvent(id, slug, title, image, status) {
            console.log(title);
            var short_content = $("#view"+id).attr('data-short-content');
            var content = $("#view"+id).attr('data-content');
            // $("#newsAndEventsView").modal('show');
            $("#NewsEventTitle").html(title);
            $("#content").html(content);
            $('#short_content').html(short_content);
            if (status == 0) {
                $('#viewDisplay').html('<small class="badge badge-danger">Not Displayed</small>');
            } else {
                $('#viewDisplay').html('<small class="badge badge-success">Displayed</small>');
            }
            $('#ViewImage').attr('src', "{{ asset('storage/news-and-events/')}}/" + slug + "/thumbs/small_" + image);


        }
    </script>
    <script>
        function delete_menu(id) {
            var conn = './news-and-events/delete/' + id;
            $('#delete a').attr("href", conn);
        }
    </script>
@endsection

