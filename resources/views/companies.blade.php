@extends('layouts.app')
@section('title', 'Companies')

@section('content')
<section id="inner-banner">
    <div class="container">
        <h1>Companies</h1>
    </div>

</section>

<!--INNER BANNER END--> 

<section class="popular-job-caregries popular-categories candidates-listing popular-companies">
    <div class="holder">
        <div class="container">
            <h4>Popular Companies</h4>
            <div id="popular-companies-slider" class="owl-carousel owl-theme">
                @foreach($popular_companies as $key => $company)
                <div class="item">
                    <a href="{{ route('company_jobs',['slug' => $company->slug]) }}">
                        <div class="box"> 
                            @if($company->image != '')
                            <img width="90" src="{{ asset('storage/employers/company-info/thumbs/small_'.$company->image) }}" alt="no-image">
                            @else
                            <img class="img-thumbnail" width="90" src="{{ asset('images/user.jpg') }}" alt="img">
                            @endif
                            <h4><a href="{{ route('company_jobs',['slug' => $company->slug]) }}">{{ $company->organization_name }}</a></h4>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</section>

<!--POPULAR JOB CATEGPRIES END--> 



<!--SEARCH BAR SECTION START-->

<section class="candidates-search-bar">
    <div class="container">
        <form action="{{ route('job_search') }}" method="GET" autocomplete="off">
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
        </form>
    </div>

</section>

<!--SEARCH BAR SECTION END--> 



<!--MAIN START-->

<div id="main"> 
    <!--RECENT JOB SECTION START-->
    <section class="resumes-section padd-tb">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-8">
                    <div class="resumes-content">
                        <div class="alphabets"> 
                            <a href="#A">A</a>
                            <a href="#B">B</a>
                            <a href="#C">C</a>
                            <a href="#D">D</a>
                            <a href="#E">E</a>
                            <a href="#F">F</a>
                            <a href="#G">G</a>
                            <a href="#H">H</a>
                            <a href="#I">I</a>
                            <a href="#J">J</a>
                            <a href="#K">K</a>
                            <a href="#L">L</a>
                            <a href="#M">M</a>
                            <a href="#N">N</a>
                            <a href="#O">O</a>
                            <a href="#P">P</a>
                            <a href="#Q">Q</a>
                            <a href="#R">R</a>
                            <a href="#S">S</a>
                            <a href="#T">T</a>
                            <a href="#U">U</a>
                            <a href="#V">V</a>
                            <a href="#W">W</a>
                            <a href="#X">X</a>
                            <a href="#Y">Y</a>
                            <a href="#Z">Z</a> 
                        </div>
                        <div class="alphabets-row">
                            <ul id="blog-masonrywrap">
                                @foreach($companies as $key => $companyAlpha)
                                <li class="loop-entry" id="{{$key}}">
                                    <div class="col"> 
                                        <span>{{ $key }}</span>
                                        <div class="list-col"> 
                                            @foreach($companyAlpha as $company)
                                            <a href="{{ route('company_jobs',['slug' => $company->slug]) }}">{{ $company->organization_name }} ({{ $company->user->jobs->count() }})</a> 
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4">
                    <h2>Jobs By Categories</h2>
                    <aside>
                        <div class="sidebar">
                            <div class="sidebar-jobs">
                                <ul>
                                    @foreach($job_categories as $key => $job_category)
                                    <li>
                                        <a href="{{ url('category/'.$job_category->slug) }}">{{ $job_category->title }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!--RECENT JOB SECTION END--> 
</div>
<!--MAIN END--> 
@endsection