
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $setting->sitetitle }} | @yield('title') </title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    <link rel="icon" href="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" type="image/png"/>
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('/backend/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/animate-css/vivify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/dropify/css/dropify.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/c3/c3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}"><!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('backend/assets/vendor/table-dragger/table-dragger.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/summernote/dist/summernote.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/html/assets/css/site.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/sweetalert/sweetalert.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/nestable/jquery-nestable.css') }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}"/>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
</head>
<body class="theme-cyan font-montserrat light_version mini_sidebar" id="sideBar">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
        <div class="bar4"></div>
        <div class="bar5"></div>
    </div>
</div>

<!-- Theme Setting -->
<div class="themesetting">
    <a href="javascript:void(0);" class="theme_btn"><i class="fa fa-gears"></i></a>
    <div class="card theme_color">
        <div class="header">
            <h2>Theme Color</h2>
        </div>
        <ul class="choose-skin list-unstyled mb-0">
            <li data-theme="green">
                <div class="green"></div>
            </li>
            <li data-theme="orange">
                <div class="orange"></div>
            </li>
            <li data-theme="blush">
                <div class="blush"></div>
            </li>
            <li data-theme="cyan" class="active">
                <div class="cyan"></div>
            </li>
            <li data-theme="indigo">
                <div class="indigo"></div>
            </li>
            <li data-theme="red">
                <div class="red"></div>
            </li>
        </ul>
    </div>
    <div class="card setting_switch">
        <div class="header">
            <h2>Settings</h2>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                Light Mode
                <div class="float-right">
                    <label class="switch">
                        <input type="checkbox" class="lv-btn">
                        <span class="slider round"></span>
                    </label>
                </div>
            </li>

            <li class="list-group-item">
                Mini Sidebar
                <div class="float-right">
                    <label class="switch">
                        <input type="checkbox" class="mini-sidebar-btn" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </li>
        </ul>
    </div>

</div>

<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<div id="wrapper">
    <nav class="navbar top-navbar">
        <div class="container-fluid">

            <div class="navbar-left">
                <div class="navbar-btn">
                    <a href="index.html">
                        <img src="{{ asset('backend/assets/images/icon.svg') }}" alt="Oculux Logo" class="img-fluid logo">
                    </a>
                    <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                </div>
                <ul class="nav navbar-nav">
                    <li>
                        <a href="javascript:void(0);" id="toggleSideBar" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="navbar-right">
                <div id="navbar-menu">
                    <ul class="nav navbar-nav text-muted">
                        <li title="Visit Site">
                            <a href="{{ url('/') }}" class="icon-menu" target="_blank"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Visit Site">
                                <i class="icon-screen-desktop text-blue"></i> 
                            </a>
                        </li>|
                        <li title="Log Out">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="icon-menu"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Log Out">
                                <i class="icon-power text-red"></i> 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress-bar" id="myBar"></div>
        </div>
    </nav>
    <div id="left-sidebar" class="sidebar mini_sidebar_on">
        <div class="navbar-brand">
            <a href="{{ url('admin') }}">
                <img src="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}"  alt="Logo" class="img-fluid logo">
                <span>{{ $setting->sitetitle }}</span>
            </a>
            <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right">
                <i class="lnr lnr-menu icon-close"></i>
            </button>
        </div>
        <div class="sidebar-scroll">
            <div class="user-account">
                
                <div class="dropdown">
                    <span>Welcome,</span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong></a>
                    <ul class="dropdown-menu dropdown-menu-right account vivify flipInY">
                        <li><a href="#"><i class="icon-lock"></i>Change Password</a></li>
                        <li><a href="{{ url('admin/setting') }}"><i class="icon-settings"></i>Settings</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-power"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
            <nav id="left-sidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    <li class="header">Main</li>
                    <li class="{{ Request::segment(2) == '' ? 'active' : '' }}">
                        <a href="{{ url('/admin') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Dashboard">
                            <i class="icon-speedometer"></i><span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="{{ Request::segment(2) == 'setting' ? 'active' : '' }}">
                        <a href="{{ url('/admin/setting') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Site Settings">
                            <i class="icon-settings"></i><span>Site Settings</span>
                        </a>
                    </li>
                    
                    <li class="{{ Request::segment(2) == 'users' ? 'active' : '' }}">
                        <a href="{{ url('/admin/users') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="All Users">
                            <i class="icon-users"></i><span>All Users</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'job-seekers' ? 'active' : '' }}">
                        <a href="{{ url('/admin/job-seekers') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Job Seekers">
                            <i class="icon-users"></i><span>Job Seekers</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'employers' ? 'active' : '' }}">
                        <a href="{{ url('/admin/employers') }}" data-toggle="tooltip" data-placement="right" title="" data-original-title="Employers">
                            <i class="icon-users"></i><span>Employers</span>
                        </a>
                    </li>
                    
                    <li class="{{ Request::segment(2) == 'sliders' ? 'active' : '' }}">
                        <a href="{{ url('/admin/sliders') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Sliders">
                            <i class="icon-layers"></i><span>Sliders</span>
                        </a>
                    </li>
                    
                    <li class="{{ Request::segment(2) == 'pages' ? 'active' : '' }}">
                        <a href="{{ url('/admin/pages') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Pages">
                            <i class="fa fa-file-text-o"></i><span>Pages</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'job-categories' ? 'active' : '' }}">
                        <a href="{{ url('/admin/job-categories') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Job Categories">
                            <i class="fa fa-anchor"></i><span>Job Categories</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'locations' ? 'active' : '' }}">
                        <a href="{{ url('/admin/locations') }}" data-toggle="tooltip" class="header-side-li" data-placement="right" title="" data-original-title="Locations">
                            <i class="icon-pointer"></i><span>Locations</span>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
    </div>
    <div id="main-content">
        @yield('content')
    </div>



</div>
<!-- Javascript -->
<!-- Scripts -->

<script src="{{ asset('backend/html/assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/c3.bundle.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/sweetalert/sweetalert2.js') }}"></script><!-- SweetAlert Plugin Js -->
<!-- <script src="{{ asset('backend/assets/vendor/table-dragger/table-dragger.min.js') }}"></script> -->
<!-- Multi Select Plugin Js -->
<script src="{{ asset('backend/assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/dropify/js/dropify.js') }}"></script>
<script src="{{ asset('backend/html/assets/js/pages/forms/dropify.js') }}"></script>
<script src="{{ asset('backend/html/assets/js/pages/tables/jquery-datatable.js') }}"></script>

{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
<script src="{{ asset('backend/html/assets/js/index.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/summernote/dist/summernote.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/nestable/jquery.nestable.js') }}"></script><!-- Jquery Nestable -->
<script src="{{ asset('backend/html/assets/js/pages/ui/sortable-nestable.js') }}"></script>

@if (session('status'))
    <script>
        $(document).ready(function() {
            swal({
                title: 'Success!',
                buttonsStyling: false,
                confirmButtonClass: "btn btn-success",
                text: '{{ session('status') }}',
                timer: 2000,
                type: "success"
            }).catch(swal.noop);
        });
    </script>
@elseif (session('error'))
    <script>
        $(document).ready(function() {
            swal({
                title: 'Error!',
                buttonsStyling: false,
                confirmButtonClass: "btn btn-danger",
                text: '{{ session('error') }}',
                timer: 2500,
                type: "error"
            }).catch(swal.noop);
        });
    </script>

@elseif (session("parent_status"))
    <script>
        $(document).ready(function() {
            swal({
                title: '{{ session("parent_status")["primary"] }}',
                buttonsStyling: false,
                confirmButtonClass: "btn btn-danger",
                text: '{{ session("parent_status")["secondary"] }}',
                timer: 2500,
                type: "error"
            }).catch(swal.noop);
        });
    </script>
@endif
@if ($errors->any())
    @foreach ($errors->all() as $key=>$error)
        <div data-notify="container"  class="col-11 col-md-4 alert alert-danger alert-with-icon animated fadeInDown cart-alert-message vivify " role="alert" data-notify-position="bottom-right" style="display: inline-block; margin: 15px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1031; bottom: <?= $key * 70; ?>px; right: 20px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
            <i data-notify="icon" class="fa fa-bell"></i>
            <span data-notify="title"></span>
            <span data-notify="message">
                    Sorry!! <br> {{ $error }}
                </span>
            <a href="#" target="_blank" data-notify="url"></a>
        </div>
    @endforeach
@endif
<script>
    var $alert = $('.cart-alert-message');
    $alert.hide();

    var i = 0;
    setInterval(function () {
        $($alert[i]).show();
        $($alert[i]).addClass('flipInX');
        i++;
    }, 500);

    // $(".cart-alert-message").fadeTo((($alert.length)+1)*1000, 0.1).slideUp('slow');
    setTimeout(function() {
        $('.cart-alert-message').addClass('fadeOutRight');
    }, $alert.length*($alert.length == 1 ? 5000 : 2000));
</script>
<script>
    $(".summernote").summernote({
        disableResizeEditor: true,
        height: 300,
        width:'90%',
        callbacks: {
            onImageUpload: function(files) {
                for(let i=0; i < files.length; i++) {
                    $.upload(files[i]);
                }
            }
        },
    });

    $.upload = function (file) {
        let out = new FormData();
        out.append('file', file, file.name);

        $.ajax({
            method: 'POST',
            url: '{{ route('admin.image.upload') }}',
            contentType: false,
            cache: false,
            processData: false,
            data: out,
            success: function (img) {
                $('.summernote').summernote('insertImage', img);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus + " " + errorThrown);
            }
        });
    };


    // Only Body
    // tableDragger(document.querySelector("#only-bodytable"), { mode: "row", onlyBody: true });

    $("#toggleSideBar").click(function(){
        if ($("#left-sidebar").hasClass("mini_sidebar_on")) {
            $("#left-sidebar").removeClass("mini_sidebar_on");
            $("#sideBar").removeClass("mini_sidebar");
        }else{
            $("#left-sidebar").addClass("mini_sidebar_on");
            $("#sideBar").addClass("mini_sidebar");
        }
    });

</script>
@yield('script')

</body>
</html>


