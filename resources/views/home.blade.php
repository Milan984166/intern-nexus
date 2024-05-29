@extends('layouts.app')
@section('title','Home')

@section('content')
<!--BANNER START-->
<div class="banner-outer">

    <div id="banner" class="element" style="background-image: url('{{ asset('frontend/images/slide1.jpg') }}');">
        <!-- <img src="images/slide1.jpg" alt="banner"> -->
    </div>

    <div class="caption">
        <div class="holder">

            <h1>Empowering Futures Beyond Internships!</h1>

            <form action="{{ route('job_search') }}" method="GET" autocomplete="off">
                <div class="container">
                    <div class="row">

                        <div class="col-md-6 col-sm-6">
                            <div class="auto-complete-div">
                                <input type="text" name="location"  placeholder="Enter Location" id="inputLocations">
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-5">
                            <div class="auto-complete-div">
                                <input type="text" name="category"  placeholder="Enter Category" id="inputCategories">
                            </div>
                        </div>

                        <div class="col-md-1 col-sm-1">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </div>

                    </div>
                </div>
            </form>

            <!-- <div class="banner-menu">

                <ul>
                    <li><a href="#">San Francisco</a></li>
                    <li><a href="#">Palo Alto</a></li>
                    <li><a href="#">Mountain View</a></li>
                    <li><a href="#">Sacramento</a></li>
                    <li><a href="#">New York</a></li>
                    <li><a href="#">United Kindom</a></li>
                    <li><a href="#">Asia Pacific</a></li>
                </ul>

            </div> -->

            <div class="btn-row"> 
                

                <a href="{{ route('register-jobseeker') }}"><i class="fa fa-user"></i>I’m a Intern</a> 
               
                <a href="{{ route('register-employer') }}"><i class="fa fa-building-o"></i>I’m an Employer</a> 
            </div>
        </div>

    </div>

    <div class="browse-job-section">

        <div class="container">

            <div class="holder"> 
                <a href="{{ route('jobs') }}" class="btn-brows">Browse Jobs</a>
                <strong class="title">
                    Finds Jobs in 
                    @foreach($top_locations as $loc)
                    <a href="{{ route('location_jobs',['slug' => $loc->slug]) }}" style="color: white;">{{ $loc->title }}</a> | 
                    @endforeach
                </strong>
            </div>

        </div>

    </div>

</div>

<!--BANNER END--> 



<!--MAIN START-->

<div id="main"> 

    <!--POPULAR JOB CATEGORIES START-->

    <section class="popular-categories">
        <div class="container">
            <div class="clearfix">
                <h2>Popular Jobs Categories</h2>
                <a href="{{ route('categories') }}" class="btn-style-1">Explore All Categories</a> 
            </div>

            <div class="row">
                @foreach($job_categories as $key => $job_category)
                <a href="{{ url('category/'.$job_category->slug) }}">
                <div class="col-md-3 col-sm-6">
                    <div class="box"> 

                        <img src="{{ asset('storage/job-categories/thumbs/thumb_'.$job_category->image) }}" alt="{{ $job_category->slug }}">
                        <h4><a href="{{ url('category/'.$job_category->slug) }}">{{ $job_category->title }}</a></h4>
                        <strong>{{ $job_category->jobs->count() }} Jobs</strong>
                        <!-- <p>Available in Design &amp; Multimedia</p> -->
                    </div>
                </div>
                </a>
                @if(($key+1)%4 == 0)
                <div class="clearfix"></div>
                @endif
                @endforeach
            </div>
        </div>
    </section>

    <!--POPULAR JOB CATEGORIES END--> 

    <section class="how-it-works" style="background-color:#fff;">
        <div class="container-fluid">
            <div class="clearfix">
                <h2 class="text-center">HOW IT WORKS</h2>
               <ul class="process">
                  <li class="process__item">
                    <span class="process__number">1</span>
                    <span class="process__title">Register  Yourself</span>
                    <span class="process__subtitle">Register an account with us as an employer or as a Intern</span>
                  </li>
                
                  <li class="process__item">
                    <span class="process__number">2</span>
                    <span class="process__title">Log In</span>
                    <span class="process__subtitle">Log Into Your Account Using the web or App</span>
                  </li>
                
                  <li class="process__item">
                    <span class="process__number">3</span>
                    <span class="process__title">Profile Update</span>
                    <span class="process__subtitle">Complete Your Profile Information to be verified/</span>
                  </li>
                
                  <li class="process__item">
                    <span class="process__number">4</span>
                    <span class="process__title">Post / Apply</span>
                    <span class="process__subtitle">Post Jobs or Apply for jobs avialable via web or the app .</span>
                  </li>
                </ul>
            </div>
        </div>
    </section>



    <!--RECENT JOB SECTION START-->

    <section class="recent-row padd-tb">

        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-8">
                    <div id="content-area">
                        <h2>Recent Hot Jobs</h2>
                        <ul id="myList">
                            @foreach($recent_hot_jobs as $job)
                            <li>
                                <div class="box">
                                    <div class="thumb">
                                        <a href="{{ url('job/'.$job->slug) }}">
                                            @if($job->user->employer_info->image != '')
                                            <img width="65" class="img-thumbnail" src="{{ asset('storage/employers/company-info/thumbs/small_'.$job->user->employer_info->image) }}" alt="img">
                                            @else
                                            <img width="65" class="img-thumbnail" width="165" src="{{ asset('images/user.jpg') }}" alt="img">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="text-col">
                                        <div class="hold">
                                            <h4>
                                                <a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
                                            </h4>
                                            <p><a href="{{ route('company_jobs',['slug' => $job->user->employer_info->slug]) }}">{{ $job->user->employer_info->organization_name }}</a></p>
                                            
                                            <a href="{{ route('location_jobs',['slug' => $job->location->slug]) }}" class="text">
                                                <i class="fa fa-map-marker"></i> {{ $job->location->title }}
                                            </a>

                                            <a href="{{ route('category_jobs',['slug' => $job->job_category->slug]) }}" class="text">
                                                <i class="fa fa-globe"></i>
                                                {{ $job->job_category->title }}
                                            </a>

                                            @php
                                                $employmentTypeArray = array('1' => 'Full Time', '2' => 'Part Time', '3' => 'Contract', '4' => 'Freelance', '5' => 'Internship', '6' => 'Traineeship', '7' => 'Volunteer');
                                            @endphp
                                            <a href="javascript:void(0)" class="text">
                                                <i class="fa fa-clock-o"></i>
                                                {{ $employmentTypeArray[$job->employment_type] }}
                                            </a>

                                            <a href="javascript:void(0)" class="text">
                                                <i class="fa fa-eye"></i>
                                                {{ $job->views}} Views
                                            </a>

                                            <a href="javascript:void(0)" class="text">
                                                <i class="fa fa-calendar"></i> {{ date('jS F, Y', strtotime($job->deadline)) }} 

                                                @php
                                                    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$job->deadline);

                                                    $from = \Carbon\Carbon::now();
                                                    $diff_in_days = $from->diff($to,false);
                                                @endphp

                                                <!-- <i style="color:{{ $diff_in_days->format('%R') == '-' ? 'red' : 'green'}};">({{ $diff_in_days->format('%R') == '-' ? ' Expired' : $diff_in_days->format('%d days, %h hours').' Remaining' }} )</i> -->
                                            </a>
                                        </div>
                                    </div>
                                    <strong class="price">
                                        <i class="fa fa-money"></i>
                                        @if($job->salary_type == 1)
                                            ${{ $job->min_salary }} - ${{ $job->max_salary }}
                                        @elseif($job->salary_type == 2)
                                            ${{ $job->min_salary }}
                                        @elseif($job->salary_type == 3)
                                            Negotiable
                                        @endif
                                    </strong>

                                    <a href="{{ url('job/'.$job->slug) }}" class="btn-1 btn-color-1 ripple">View Details</a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <div id="loadMore"> 
                            <a href="javascript:void(0)" class="load-more"><i class="fa fa-user"></i>Load More Jobs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <h2>Featured Jobs</h2>
                    @if($featured_jobs->count() > 0)
                    <div class="sidebar-jobs">

                        <ul>

                            @foreach($featured_jobs as $job)
                            <li>
                                <a href="{{ url('job/'.$job->slug) }}">{{ $job->job_title }}</a>
                                <span><i class="fa fa-map-marker"></i>{{ $job->location->title }} </span>
                                <span><i class="fa fa-calendar"></i>{{ date('jS F, Y',strtotime($job->deadline)) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--RECENT JOB SECTION END-->

    <!--PRICE TABLE STYLE 1 START-->

    <section class="price-table">

        <div class="container">

            <h2 class="text-white">We Also Have An App</h2>

            <div class="row">
                <div class="col-sm-6">
                    <h4 class="text-white">Get The InternNexus App & Link You Accounts</h4>
                    <br>
                    <p class="text-white">
                        Hop on over to the playstore and download Intern Nexus app.
                        Log into your account from your mobile device and all yout profile information will be trasferred into you mobile device.
                        <br>
                        <p class="text-white"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem, nobis officiis placeat neque deleniti at doloribus odit culpa error pariatur nisi perspiciatis dolore illum, maiores consequuntur molestiae optio, necessitatibus aut eos voluptatibus! Optio laborum, et quas alias doloremque minus, molestiae quisquam unde accusantium omnis cum.</p>
                    </p>
                    <br>
                    <a href="#">
                        <img src="{{ asset('frontend/images/256x256.png') }}" alt="" class="img-fluid">
                    </a>
                </div>
                <div class="col-sm-6">
                    <img src="{{ asset('frontend/images/app.jpg') }}" class="img-fluid" alt="">
                </div>
            </div>

        </div>

    </section>

    <!--PRICE TABLE STYLE 1 END--> 

    <!--CALL TO ACTION SECTION START-->

    <section class="call-action-section">

        <div class="container">

            <div class="text-box">

                <h2>Better Results with Standardized Hiring Process</h2>

                <p>Your quality of hire increases when you treat everyone fairly and equally. Having multiple recruiters

                working on your hiring is beneficial.</p>

            </div>

            <a href="#" class="btn-get">Get Registered &amp; Try Now</a>
        </div>

    </section>

    <!--CALL TO ACTION SECTION END--> 


    <!--TESTIOMONIALS SECTION START-->

    <section class="testimonials-section">

        <div class="container">
            <div id="testimonials-slider" class="owl-carousel owl-theme">
                <div class="item">
                    <div class="holder">

                        <div class="thumb"><img src="{{ asset('frontend/images/testo-img.png') }}" alt="img"></div>
                        <div class="text-box"> 
                            <em>One morning, when John Doe woke from troubled dreams, he found himself transformed in his Collaboratively administrate empowered markets via plug-and-play networks. Donec volutpat enim at interdum pretium. Vestibulum ante ipsum primis.</em>

                            <ul class="testimonials-rating">

                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star-o"></i></a></li>
                            </ul>

                            <strong class="name">John Doe</strong> <span class="post">Database Manager</span> 
                        </div>

                    </div>
                </div>

                <div class="item">
                    <div class="holder">

                        <div class="thumb"><img src="{{ asset('frontend/images/testo-img.png') }}" alt="img"></div>
                        <div class="text-box"> 
                            <em>One morning, when Jimmy Arl woke from troubled dreams, he found himself transformed in his Collaboratively administrate empowered markets via plug-and-play networks. Donec volutpat enim at interdum pretium. Vestibulum ante ipsum primis.</em>

                            <ul class="testimonials-rating">

                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                                <li><a href="#"><i class="fa fa-star-o"></i></a></li>
                                <li><a href="#"><i class="fa fa-star-o"></i></a></li>
                            </ul>

                            <strong class="name">Jimmy Arl</strong> <span class="post">CEO</span> 
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!--TESTIOMONIALS SECTION END--> 

</div>

<!--MAIN END--> 
@endsection