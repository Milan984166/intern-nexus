@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<section id="inner-banner">
    <div class="container">
        <h1>Categories</h1>
    </div>

</section>

<!--INNER BANNER END--> 

<section class="popular-job-caregries popular-categories candidates-listing popular-companies">
    <div class="holder">
        <div class="container">
            <h4>Popular Categories</h4>
            <div id="popular-companies-slider" class="owl-carousel owl-theme">
                @foreach($popular_job_categories as $key => $job_category)
                <div class="item">
                    <a href="{{ url('category/'.$job_category->slug) }}">
                        <div class="box"> 
                            <img src="{{ asset('storage/job-categories/thumbs/thumb_'.$job_category->image) }}" alt="{{ $job_category->slug }}">
                            <h4><a href="{{ url('category/'.$job_category->slug) }}">{{ $job_category->title }}</a></h4>
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
                <div class="col-md-12 col-sm-8">
                    <div class="row">
                        @foreach($categories as $key => $categoryAlpha)
                            <div class="col-md-4">
                                
                                <div class="alphabets-row"> 
                                    @foreach($categoryAlpha as $category)
                                    <a href="{{ route('category_jobs',['slug' => $category->slug]) }}">{{ $category->title }} ({{ $category->jobs->count() }})</a> 
                                    @endforeach
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- <div class="resumes-content">
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
                                @foreach($categories as $key => $categoryAlpha)
                                <li class="loop-entry" id="{{$key}}">
                                    <div class="col"> 
                                        <span>{{ $key }}</span>
                                        <div class="list-col"> 
                                            @foreach($categoryAlpha as $category)
                                            <a href="{{ route('category_jobs',['slug' => $category->slug]) }}">{{ $category->title }} ({{ $category->jobs->count() }})</a> 
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div> -->
                </div>
                <!-- <div class="col-md-3 col-sm-4">
                    <h2>Jobs By Companies</h2>
                    <aside>
                        <div class="sidebar">
                            <div class="sidebar-jobs">
                                <ul>
                                    @foreach($companies as $company)
                                    <li> 
                                        <a href="{{ route('company_jobs',['slug' => $company->employer_info->slug]) }}">
                                            {{ $company->employer_info->organization_name }} ({{ $company->jobs->count() }})
                                        </a> 
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div> -->
            </div>
        </div>
    </section>
    <!--RECENT JOB SECTION END--> 
</div>
<!--MAIN END--> 
@endsection