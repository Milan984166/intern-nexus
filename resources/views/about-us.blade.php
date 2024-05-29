@extends('layouts.app')
@section('title', 'About Us')

@section('content')

<section id="inner-banner">

    <div class="container">

        <h1>About Work Nepali</h1>

    </div>

</section>
<section class="recent-row padd-tb">

    <div class="container">

        <div class="content-area">
            <div class="row">
                <div class="col-sm-6">
                    <img src="{{ asset('frontend/images/files/human-5343120_1920.jpg') }}" alt="" class="img-responsive">
                </div>
                <div class="col-sm-6">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae magni libero architecto illum atque voluptatibus ducimus modi dignissimos sed, facilis, deserunt omnis optio distinctio natus nemo, voluptate nisi. Reprehenderit molestiae suscipit rem nulla veritatis, dignissimos corrupti quibusdam vel nostrum laboriosam laudantium commodi totam blanditiis placeat? Laudantium eveniet, nihil, eligendi corrupti ullam libero, consequatur voluptate inventore provident natus veritatis odit harum.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita distinctio vel earum voluptatem suscipit. Suscipit, praesentium error molestiae nesciunt commodi odio voluptatem ipsum voluptates fugit sit cupiditate laboriosam similique quas recusandae at est vitae nisi, quisquam perspiciatis quia voluptas quo, minima corporis? Odio, natus. Sit molestias porro odit iure placeat consectetur atque necessitatibus dignissimos doloribus fugiat dolore quaerat numquam nemo delectus quod, ipsam quam, assumenda ducimus! Similique ratione quas minima quasi alias eligendi temporibus laboriosam! Ut eligendi enim pariatur quaerat. Voluptate dicta impedit aliquam, minima nostrum dolorem reprehenderit pariatur est!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                <header>
                    <h1>Welcome to Intern Nexus</h1>
                    <p>Where we're redefining the future of career exploration and skill development. Our commitment to innovation is reflected in three groundbreaking features that elevate your career journey:</p>
                </header>

                <section>
                    <h2>Virtual Reality (VR) Job Previews</h2>
                    <p>Embark on a revolutionary career exploration experience with our VR Job Previews. Step into the shoes of professionals in your desired field, providing an immersive "day in the life" encounter. By leveraging cutting-edge VR technology, Intern Nexus allows you to make informed career choices by experiencing the nuances of various roles firsthand. Explore your future career path like never before.</p>
                </section>

                <section>
                    <h2>Gamified Skill Development</h2>
                    <p>Learning meets fun with Intern Nexus's Gamified Skill Development. We've transformed skill enhancement into an engaging adventure, complete with achievements, badges, and challenges. Motivate yourself to reach new heights in your professional journey through interactive and enjoyable skill-building experiences. Elevate your skills with a sense of accomplishment that goes beyond traditional learning methods.</p>
                </section>

                <section>
                    <h2>Predictive Analytics for Job Market Trends</h2>
                    <p>Stay ahead of the curve with our Predictive Analytics for Job Market Trends. Intern Nexus empowers you with insights into future job market trends, helping you make strategic decisions about your career. Our platform utilizes advanced analytics to forecast industry demands, ensuring that you are well-prepared for the evolving job landscape. Make informed choices based on data-driven predictions and position yourself for success.</p>
                </section>

                <footer>
                    <p>At Intern Nexus, we believe in providing you with not just a platform but a transformative experience. Embrace the future of career development with us, where innovation meets your aspirations. Explore, learn, and grow with Intern Nexus — your gateway to a dynamic and successful professional journey.</p>
                </footer>
                </div>
            </div>
        </div>

    </div>

</section>
<section class="call-action-section">

    <div class="container">

        <div class="text-box">

            <h2>Get Started with WorkNepali</h2>

            <p>Your quality of hire increases when you treat everyone fairly and equally. Having multiple recruiters

            working on your hiring is beneficial.</p>

        </div>

        <a href="#" class="btn-get">Go To My Profile</a> 
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

