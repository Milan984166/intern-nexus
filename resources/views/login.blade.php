@extends('layouts.app')
@section('title', 'Login To Your Account')

@section('content')
<section id="inner-banner">

    <div class="container">
        <h1>Login To Your Account</h1>
    </div>

</section>

<!--INNER BANNER END--> 

<!--MAIN START-->
<div id="main"> 

    <!--SIGNUP SECTION START-->
    <section class="signup-section">

        <div class="container">
            <div class="holder">
                <div class="thumb"><img src="{{ asset('storage/setting/favicon/'.$setting->favicon) }}" alt="img"></div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-box"> <i class="fa fa-user"></i>
                        <input name="email" type="email" placeholder="Email" required>
                    </div>

                    <div class="input-box"> <i class="fa fa-unlock"></i>
                        <input name="password" type="password" placeholder="Password" required>
                    </div>

                    <div class="check-box">

                        <input id="id3" type="checkbox" />
                        <strong>Remember Me</strong> 
                    </div>
                    <input type="submit" value="Log In">

                    <div class="login-social">
                        <a href="javascript:void(0)" class="login">Forgot your Password</a>
                        <em>You Don’t have an Account? <a href="{{ route('register-jobseeker') }}">SIGN UP NOW</a></em> 
                    </div>

                </form>
            </div>
        </div>
    </section>

    <!--SIGNUP SECTION END--> 
</div>

<!--MAIN END--> 
@endsection