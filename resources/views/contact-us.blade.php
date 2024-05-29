@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<section id="inner-banner">

    <div class="container">

        <h1>Contact Us</h1>

    </div>
</section>

<section class="contact-section">

    <div class="map-box">

        

        <div class="contact-form padd-tb">

            <div class="container">

                <div class="row">

                    <div class="col-md-8 col-sm-8">

                        <h2>Get in Touch</h2>

                        <form action="{{ route('contact-mail') }}" method="post">
                            @csrf
                            <div class="row">

                                <div class="col-md-4">
                                    <input name="name" type="text" placeholder="Name" required value="{{ old('name') }}">
                                </div>

                                <div class="col-md-4">
                                    <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}">
                                </div>

                                <div class="col-md-4">
                                    <input name="subject" type="text" placeholder="Subject" required value="{{ old('subject') }}">
                                </div>

                                <div class="col-md-12">
                                    <textarea name="message" required cols="10" rows="10" placeholder="Message">{{ old('message') }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <input name="comments" type="submit" value="Submit">
                                </div>

                            </div>

                        </form>
                    </div>

                    <div class="col-md-4 col-sm-4">

                        <div class="address-box">

                            <address>

                                <ul>

                                    <li> 
                                        <i class="fa fa-phone"></i> 
                                        <strong>
                                            <a href='tel:{{ preg_replace("/[^0-9,+]/", "", $setting->phone)}}'>{{ $setting->phone }} </a>
                                        </strong> 
                                        <strong>
                                            <a href='tel:{{ preg_replace("/[^0-9,+]/", "", $setting->mobile)}}'>{{ $setting->mobile }} </a>
                                        </strong> 
                                    </li>

                                    <li> 
                                        <i class="fa fa-envelope-o"></i>
                                        <a href="mailto:{{ $setting->siteemail }}">{{ $setting->siteemail }}</a> 
                                    </li>

                                    
                                    <li> 
                                        <i class="fa fa-barcode"></i>
                                        <a href="javascript:void(0);">ABN: {{ $setting->abn }}</a> 
                                    </li>

                                    <li>
                                        <a style="padding-top: 0px;" href="{{ $setting->facebookurl }}" target="_blank"><i class="fa fa-facebook"></i></a>
                                        <a style="padding-top: 0px;" href="{{ $setting->twitterurl }}" target="_blank"><i class="fa fa-twitter"></i></a>
                                        <a style="padding-top: 0px;" href="{{ $setting->instagramurl }}" target="_blank"><i class="fa fa-instagram"></i></a>
                                        <a style="padding-top: 0px;" href="{{ $setting->youtubeurl }}" target="_blank"><i class="fa fa-youtube"></i></a>
                                    </li>

                                </ul>

                            </address>
            
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
<section class="testimonials-section" style="padding:0px !important; margin:0px !important; height: 400px;">

        <div class="container" style="padding:0px !important; margin:0px !important; width: 100% !important; height: 100% !important;">
            {!! $setting->googlemapurl !!}
        </div>
    </section>
@endsection

