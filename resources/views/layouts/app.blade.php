<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $setting->sitetitle }} | @yield('title')</title>

    <!--CUSTOM CSS-->
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet" type="text/css">

    <!--BOOTSTRAP CSS-->
    <link href="{{ asset('frontend/css/bootstrap.css') }}" rel="stylesheet" type="text/css">

    <!--DATEPICKER  -->
    <link rel="stylesheet" href="{{ asset('frontend/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">

    <!--COLOR CSS-->
    <link href="{{ asset('frontend/css/color.css') }}" rel="stylesheet" type="text/css">

    <!--RESPONSIVE CSS-->
    <link href="{{ asset('frontend/css/responsive.css') }}" rel="stylesheet" type="text/css">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('frontend/plugins/toastr/toastr.min.css')}}">

    <!-- Extra Css -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet"  type="text/css">

    <!--OWL CAROUSEL CSS-->
    <link href="{{ asset('frontend/css/owl.carousel.css') }}" rel="stylesheet" type="text/css">

    <!--FONTAWESOME CSS-->
    <link href="{{ asset('frontend/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!--SCROLL FOR SIDEBAR NAVIGATION-->
    <link href="{{ asset('frontend/css/jquery.mCustomScrollbar.css') }}" rel="stylesheet" type="text/css">

    <!--FAVICON ICON-->
    <link rel="icon" href="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" type="image/x-icon">

    <!--GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,500,700,900' rel='stylesheet' type='text/css'>
    @stack('custom-css')
</head>



<body class="theme-style-1">


    <!--WRAPPER START-->
    <div id="wrapper"> 

        <!--HEADER START-->
        <header id="header"> 

            <!--BURGER NAVIGATION SECTION START-->
            <div class="cp-burger-nav"> 

                <!--BURGER NAV BUTTON-->
                <div id="cp_side-menu-btn" class="cp_side-menu">
                    <a href="javascript:void(0)" class=""><img src="{{ asset('frontend/images/menu-icon.png') }}" alt="img"></a>
                </div>

                <!--BURGER NAV BUTTON--> 

                <!--SIDEBAR MENU START-->
                <div id="cp_side-menu"> 
                    <a href="#" id="cp-close-btn" class="crose"><i class="fa fa-close"></i></a>
                    <div class="cp-top-bar">
                        <h4>
                            For any Queries: 
                            <a href='tel:{{ preg_replace("/[^0-9,+]/", "", $setting->phone)}}'>
                                {{ $setting->phone }}
                            </a>
                        </h4>
                        <div class="login-section"> 
                            @if(Auth::check())
                                @if(Auth::user()->role == 2)
                                <a class="btn-login" href="{{ url('jobseeker/profile') }}">My Profile</a>
                                @elseif(Auth::user()->role == 3)
                                <a class="btn-login" href="{{ url('soletrader/profile') }}">My Profile</a>
                                @elseif(Auth::user()->role == 4)
                                <a class="btn-login" href="{{ url('company/profile') }}">My Profile</a>
                                <a class="btn-login" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                @endif
                            @else
                            <a href="{{ route('front_login') }}" class="btn-login">Log in</a> 
                            <!-- <a href="signup.html" class="btn-login">Signup</a>  -->
                            @endif
                        </div>
                    </div>

                    <strong class="logo-2">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" height="90px" alt="logo">
                        </a>
                    </strong>

                    <div class="content mCustomScrollbar">

                        <div id="content-1" class="content">
                            <div class="cp_side-navigation">
                                
                                <nav>

                                    <ul class="navbar-nav">
                                        <li class="active"><a href="{{ url('/') }}">Home</a></li>
                                        <li><a href="{{ route('jobs') }}">Jobs</a></li>
                                        <li><a href="{{ route('companies') }}">Companies</a></li>
                                        <li><a href="{{ route('categories') }}">Categories</a></li>
                                        <li><a href="{{ url('/about-us') }}">About</a> </li>

                                        <li><a href="{{ url('/contact-us') }}">Contact</a> </li>

                                    </ul>

                                </nav>

                            </div>

                        </div>

                    </div>

                    <div class="cp-sidebar-social">

                        <ul>
                            <li><a href="{{ $setting->facebookurl }}" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
                            <li><a href="{{ $setting->linkedinurl }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="{{ $setting->twitterurl }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="{{ $setting->youtubeurl }}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                            
                            
                            
                        </ul>
                    </div>

                    <strong class="copy">InternNexus &copy; 2023, All Rights Reserved</strong> 

                    <strong style="color:red; font-weight: 700;">This website/app is for a class assignment and not for commercial purposes</strong>
                </div>

                <!--SIDEBAR MENU END--> 
            </div>

            <!--BURGER NAVIGATION SECTION END-->

            <div class="container"> 

                <!--NAVIGATION START-->

                <div class="navigation-col">

                    <nav class="navbar navbar-inverse">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

                            <strong class="logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}" style="height: 90px !important;" alt="logo">
                                </a>
                            </strong> 
                        </div>

                        <div id="navbar" class="collapse navbar-collapse">

                            <ul class=" nav navbar-nav" id="nav">

                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ route('jobs') }}">Jobs</a></li>
                                <li><a href="{{ route('companies') }}">Companies</a></li>
                                <li><a href="{{ route('categories') }}">Categories</a></li>
                                <li><a href="{{ url('/about-us') }}">About</a></li>
                                <li><a href="{{ url('/contact-us') }}">Contact</a></li>

                            </ul>

                        </div>

                    </nav>

                </div>

                <!--NAVIGATION END--> 
            </div>



            <!--USER OPTION COLUMN START-->

            <div class="user-option-col">
                @if(Auth::check())
                <div class="thumb">
                    <div class="dropdown">

                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            @if(Auth::user()->role == 1)
                                @if(Auth::user()->image != '')
                                    <img class="img-thumbnail" width="44" src="{{ asset('storage/jobseekers/thumbs/small_'.Auth::user()->image) }}" alt="img">
                                @else
                                    <img class="img-thumbnail" width="44" src="{{ asset('images/user.jpg') }}" alt="img">
                                @endif
                            @elseif(Auth::user()->role == 2)
                                @if(Auth::user()->image != '')
                                    <img class="img-thumbnail" width="44" src="{{ asset('storage/jobseekers/thumbs/small_'.Auth::user()->image) }}" alt="img">
                                @else
                                    <img class="img-thumbnail" width="44" src="{{ asset('images/user.jpg') }}" alt="img">
                                @endif
                            @elseif(in_array(Auth::user()->role, [3,4]))
                                @if(isset(Auth::user()->employer_info->image) && Auth::user()->employer_info->image != '')
                                    <img class="img-thumbnail" width="44" src="{{ asset('storage/employers/company-info/thumbs/small_'.Auth::user()->employer_info->image) }}" alt="img">
                                @else
                                    <img class="img-thumbnail" width="44" src="{{ asset('images/user.jpg') }}" alt="img">
                                @endif
                            @endif
                            
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            @if(Auth::user()->role == 2)
                            <li><a href="{{ url('jobseeker/profile') }}">Manage Account</a></li>
                            @elseif(Auth::user()->role == 3)
                            <li><a href="{{ url('soletrader/profile') }}">Manage Account</a></li>
                            <li><a href="{{ route('soletrader.edit-information') }}">Edit Profile</a></li>
                            @elseif(Auth::user()->role == 4)
                            <li><a href="{{ url('company/profile') }}">Manage Account</a></li>
                            <li><a href="{{ route('company.edit-information') }}">Edit Profile</a></li>
                            @endif
                            <li><a href="#changePassword"
                                data-toggle="modal"
                                id="chngPass{{ Auth::user()->id }}"
                                data-id="<?= Auth::user()->id; ?>"
                                data-user="{{ Auth::user()->name }}"
                                onclick="editPassword('{{ Auth::user()->id }}')">Change Password</a></li>
                            
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log off</a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
                @else
                <div class="dropdown-box">
                    <div class="dropdown">

                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <img src="{{ asset('frontend/images/option-icon.png') }}" alt="img"> </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">

                            <li> 
                                <a href="{{ route('front_login') }}" class="login-round"><i class="fa fa-sign-in"></i></a> 
                                <a href="{{ route('front_login') }}" class="btn-login">Log in with InternNexus</a>
                            </li>

                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <!--USER OPTION COLUMN END--> 
        </header>
        <!--HEADER END--> 

        @yield('content')

        <!--FOOTER START-->
        <footer id="footer">
            @php
                $footer_categories = \App\JobCategory::where('display',1)->orderBy('order_item')->get()->split(3);
            @endphp
            @foreach($footer_categories as $key => $categoryAlpha)
                <div class="box">
                    <h4>{{ $key == 0 ? 'Jobs By Category' : '' }}</h4>
                    <ul> 
                        @foreach($categoryAlpha as $category)
                        <li><a href="{{ route('category_jobs',['slug' => $category->slug]) }}">{{ $category->title }} ({{ $category->jobs->count() }})</a> </li>
                        @endforeach
                        
                    </ul>
                </div>
            @endforeach

            


            <div class="box">

                <h4>Jobs By Location</h4>
                @php
                    $footer_locations = \App\Location::where('display', 1)->get();
                @endphp
                <ul>
                    @foreach($footer_locations as $key => $location)
                    <li><a href="{{ route('location_jobs', [ 'slug' => $location->slug]) }}">{{ $location->title }}</a></li>
                    @endforeach
                </ul>

            </div>

            <form action="#">

                <h4>Subscribe for Latest Jobs Alerts</h4>
                <input type="text" placeholder="Name" required>
                <input type="text" placeholder="Email" required>
                <input type="submit" value="Subscribe Alerts">

            </form>

            <div class="container">

                <div class="bottom-row"> 
                    <strong class="copyrights">
                        InternNexus &copy; 2023, All Rights Reserved <br><br>
                        <strong style="color:red; font-weight: 700;">This website/app is for a class assignment and not for commercial purposes</strong>
                    </strong>
                    
                    <div class="footer-social">

                        <ul>

                            <li><a href="{{ $setting->facebookurl }}" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
                            <li><a href="{{ $setting->linkedinurl }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="{{ $setting->twitterurl }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="{{ $setting->youtubeurl }}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                        </ul>

                    </div>

                </div>

            </div>

        </footer>

        <!--FOOTER END--> 

    </div>
    @if(Auth::check())
    <div class="modal fade" id="changePassword">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #F39C12">
                    <h6>Change Password</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                </div>
                <form method="POST" action="{{ route('change-password') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id" value="{{ Auth::user()->id }}">
                        <div class="row resum-form">

                            <div class="col-md-12 col-sm-12">
                                <label><i class="fa fa-lock"></i> New Password *</label>
                                <input name="password" type="password" placeholder="*********" required style="height: 45px;">
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label><i class="fa fa-lock"></i> Re-Type Password *</label>
                                <input name="password_confirmation" type="password" placeholder="*********" required style="height: 45px;">
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label><i class="fa fa-lock"></i> Old Password *</label>
                                <input name="oldpassword" type="password" placeholder="*********" required style="height: 45px;">
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 170px; background-color: #e1e8ed">
                                            <i class="fa fa-lock"></i> &nbsp;New Password
                                        </span>
                                    </div>
                                    <input type="password" name="password" placeholder="*******" class="form-control" required>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 170px; background-color: #e1e8ed">
                                            <i class="fa fa-lock"></i> &nbsp;Re-Type Password
                                        </span>
                                    </div>
                                    <input type="password" name="password_confirmation" placeholder="*******" class="form-control" required>
                                </div>
                            </div> -->

                            <!-- <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="min-width: 170px; background-color: #e1e8ed">
                                            <i class="fa fa-lock"></i> &nbsp;Old Password
                                        </span>
                                    </div>
                                    <input type="password" name="oldpassword" placeholder="*******" class="form-control" required>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #F39C12">
                        <button type="button" class="btn btn-outline-danger text-left" data-dismiss="modal">Close</button>
                        <button type="submit" id="updateSend" class="btn btn-primary" name="updatePassword"><i
                                class="fa fa-plus"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-users -->
            <!-- /.modal-users -->
        </div>
        <!-- /.modal-dialog -->

    </div>
    @endif

    <!--WRAPPER END--> 

    <!--jQuery START--> 

    <!--JQUERY MIN JS--> 
    <script src="{{ asset('frontend/js/jquery-1.11.3.min.js') }}"></script> 

    <!--BOOTSTRAP JS--> 
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script> 

    <!--OWL CAROUSEL JS--> 
    <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script> 

    <!--BANNER ZOOM OUT IN--> 
    <script src="{{ asset('frontend/js/jquery.velocity.min.js') }}"></script> 

    <script src="{{ asset('frontend/js/jquery.kenburnsy.js') }}"></script> 

    <!-- Toastr -->
    <script src="{{ asset('frontend/plugins/toastr/toastr.js') }}"></script>

    <!-- CKEDITOR -->
    <script src="{{ asset('frontend/plugins/ckeditor/ckeditor.js') }}"></script>

    <!-- DATEPICKER -->
    <script src="{{ asset('frontend/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!--SCROLL FOR SIDEBAR NAVIGATION--> 
    <script src="{{ asset('frontend/js/jquery.mCustomScrollbar.concat.min.js') }}"></script> 
    @stack('post-scripts')

    <!--CUSTOM JS--> 
    <script src="{{ asset('frontend/js/custom.js') }}"></script>

    <script type="text/javascript">
       toastr.options.timeOut = "4000";
       toastr.options.closeButton = true;
       toastr.options.positionClass = 'toast-top-right';
    </script>
    @if (session('status'))
       <script>
           toastr['success']('{{ session('status') }}', 'Success!');
       </script>
    @elseif (session('success_status'))
        <script>
           toastr['success']('{{ session('success_status') }}', 'Success!');
       </script>
    @elseif (session('error'))

       <script>
           toastr['error']('{{ session('error') }}','Sorry!');
       </script>

    @elseif (session('log_status'))
       <script>
           toastr['error']('{{ session('log_status') }}','');
       </script>

    @elseif (session('log_success'))
       <script>
           toastr['success']('{{ session('log_success') }}','Logged In..');
       </script>

    @elseif (session("parent_status"))
       <script>
           toastr['error']('{{ session("parent_status")["secondary"] }}', '{{ session("parent_status")["primary"] }}');
       </script>
       
    @endif

    @if ($errors->any())
       @foreach ($errors->all() as $key=>$error)
           <script>
               toastr['error']('{{ $error }}','');
           </script>
       @endforeach
    @endif

    @php
        $search_locations = collect(\App\Location::select('title')->where('display',1)->get())->pluck('title')->flatten(1)->all();
        $search_categories = collect(\App\JobCategory::select('title')->where('display',1)->get())->pluck('title')->flatten(1)->all();
        $search_locations = json_encode($search_locations);
        $search_categories = json_encode($search_categories);
    @endphp
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
        var locations = <?php echo $search_locations ?>;
        var categories = <?php echo $search_categories ?>;
        
        function autocomplete(inp, arr) {
              /*the autocomplete function takes two arguments,
              the text field element and an array of possible autocompleted values:*/
              var currentFocus;
              /*execute a function when someone writes in the text field:*/
              inp.addEventListener("input", function(e) {
                  var a, b, i, val = this.value;
                  /*close any already open lists of autocompleted values*/
                  closeAllLists();
                  if (!val) { return false;}
                  currentFocus = -1;
                  /*create a DIV element that will contain the items (values):*/
                  a = document.createElement("DIV");
                  a.setAttribute("id", this.id + "autocomplete-list");
                  a.setAttribute("class", "autocomplete-items");
                  /*append the DIV element as a child of the autocomplete container:*/
                  this.parentNode.appendChild(a);
                  /*for each item in the array...*/
                  for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                      /*create a DIV element for each matching element:*/
                      b = document.createElement("DIV");
                      /*make the matching letters bold:*/
                      b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                      b.innerHTML += arr[i].substr(val.length);
                      /*insert a input field that will hold the current array item's value:*/
                      b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                      /*execute a function when someone clicks on the item value (DIV element):*/
                          b.addEventListener("click", function(e) {
                          /*insert the value for the autocomplete text field:*/
                          inp.value = this.getElementsByTagName("input")[0].value;
                          /*close the list of autocompleted values,
                          (or any other open lists of autocompleted values:*/
                          closeAllLists();
                      });
                      a.appendChild(b);
                    }
                  }
              });
              /*execute a function presses a key on the keyboard:*/
              inp.addEventListener("keydown", function(e) {
                  var x = document.getElementById(this.id + "autocomplete-list");
                  if (x) x = x.getElementsByTagName("div");
                  if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                  } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                  } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                      /*and simulate a click on the "active" item:*/
                      if (x) x[currentFocus].click();
                    }
                  }
              });
              function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
              }
              function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                  x[i].classList.remove("autocomplete-active");
                }
              }
              function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                  if (elmnt != x[i] && elmnt != inp) {
                  x[i].parentNode.removeChild(x[i]);
                }
              }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
        }
        
        autocomplete(document.getElementById("inputLocations"), locations);
        autocomplete(document.getElementById("inputCategories"), categories);
    </script>

    <div id="fb-customer-chat" class="fb-customerchat"></div>

    <script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "165920903272955");
    chatbox.setAttribute("attribution", "biz_inbox");
    // Specify your Facebook App ID here
    chatbox.setAttribute("app_id", "1856653928101928");
    </script>

</body>

</html>

