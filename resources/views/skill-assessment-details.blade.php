@extends('layouts.app')
@section('title', 'Skill Assessment')

@section('content')

<section id="inner-banner">

    <div class="container">

        <h1>{{ $skill_assessment->title }}</h1>
        <h3>{{ $skill_assessment->subtitle }}</h3>

    </div>

</section>
<section class="recent-row padd-tb">

    <div class="container">

        <div class="content-area">
            <div class="row">
                <div class="col-sm-12">
                    <img class="align-center text-center" src="{{ asset('storage/skill_assessments/'.$skill_assessment->slug.'/'.$skill_assessment->image) }}" alt="no-image">
                    <br><br>
                    {!! $skill_assessment->excerpt !!}
                    <br>
                    {!! $skill_assessment->content !!}

                    <a class="btn btn-warning" href="{{ asset('storage/skill_assessments/'.$skill_assessment->slug.'/'.$skill_assessment->image) }}" download="true">Download</a>
                    <a class="btn btn-primary" href="#" download="true">Upload</a>
                </div>
            </div>
        </div>

    </div>

</section>


<!--TESTIOMONIALS SECTION START-->

<section class="testimonials-section" style="padding-bottom:40px;">

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

                    <div class="text-box"> <em>One morning, when John Doe woke from troubled dreams, he found himself transformed in his Collaboratively administrate empowered markets via plug-and-play networks. Donec volutpat enim at interdum pretium. Vestibulum ante ipsum primis.</em>

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

        </div>

    </div>

</section>

<!--TESTIOMONIALS SECTION END--> 
@endsection

