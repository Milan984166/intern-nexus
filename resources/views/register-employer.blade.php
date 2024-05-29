@extends('layouts.app')
@section('title', 'Register As Employer')

@section('content')
<!--INNER BANNER START-->

<section id="inner-banner">

    <div class="container">
        <h1>Register As Employer</h1>
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
                @if($flag == 1)
                    <p>Dear User,</p>
                    <p>You are logged in as <strong>Intern</strong>.</p>
                    <p>Please <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log off</a> to register as Employer.</p>
                @else
                    <form action="{{ url('/register') }}" method="POST" autocomplete="off">

                        @csrf
                        <div class="input-box">
                            <i class="fa fa-pencil"></i>
                            <input name="name" type="text" placeholder="Full Name" required value="{{ old('name') }}">
                            @error('name')
                            <strong style="color: red;">{{ $message }}</strong>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fa fa-envelope-o"></i>
                            <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}">
                            @error('email')
                            <strong style="color: red;">{{ $message }}</strong>
                            @enderror
                        </div>

                        <!-- <div class="input-box">
                            <i class="fa fa-user"></i>
                            <input type="text" placeholder="Username">
                        </div> -->

                        <div class="input-box">
                            <i class="fa fa-lock" id="PasswordIcon"></i>
                            <input type="password" name="password" id="password2"  onkeyup="checkPassword()" placeholder="Password" required>
                            @error('password')
                            <strong style="color: red;">{{ $message }}</strong>
                            @enderror
                        </div>

                        <div class="input-box">
                            <i class="fa fa-lock" id="ConfirmPasswordIcon"></i>
                            <input type="password" name="password_confirmation" id="cpassword2"  onkeyup="checkPassword()" placeholder="Confirm Password" required>
                        </div>

                        <div class="input-box">
                            <i class="fa fa-phone"></i>
                            <input name="phone" type="text" placeholder="Phone" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')" title="With Country Code : +977 9876543210, Without Country Code : 9876543210" value="{{ old('phone') }}" required>
                        </div>
                        <input type="hidden" name="role" value="4" required>
                        <!-- <div class="input-box">
                            <i class="fa fa-building"></i>
                            <select name="role" class="full-width" required>
                                <option disabled selected>Register Yourself As</option>
                                <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>A Sole Trader</option>
                                <option value="4" {{ old('role') == 4 ? 'selected' : '' }}>A Company / Organization</option>
                            </select>
                        </div>                     -->
                        <div class="check-box">

                            <input name="terms_and_conditions" id="AcceptTerms" value="1" type="checkbox" style="margin-top:4px;">
                            <label for="AcceptTerms">
                                <strong >I’ve read <a href="javascript:void(0)">Terms & Conditions</a></strong> 
                            </label>
                        </div>
                        <input id="registerPassword" type="submit" value="Sign up">
                        <em>Already a Member? <a href="{{ route('front_login') }}">LOG IN NOW</a></em>

                    </form>
                @endif
            </div>

        </div>

    </section>

    <!--SIGNUP SECTION END--> 



</div>

<!--MAIN END--> 
@endsection

@push('post-scripts')
<script type="text/javascript">

    $("#registerPassword").prop("disabled", true).css({'background-color':'#1b8af394','cursor': 'no-drop'});

    var flag = 0;
    function checkPassword(){
        
        if (check_password_terms() == 1) {
            // flag = 1;
            $("#registerPassword").prop("disabled", false).css({'background-color':'#1b8af3','cursor': 'pointer'});
        }else{
            // flag = 0;
            $("#registerPassword").prop("disabled", true).css({'background-color':'#1b8af394','cursor': 'no-drop'});
        }
    }


    function check_password_terms(){
        if ($('#password2').val() != '' && $('#cpassword2').val() != '') {

            if ($("#password2").val() == $("#cpassword2").val()) {

                $('#PasswordIcon').removeClass('fa-lock ').addClass('fa-unlock').css('color','green');
                $('#ConfirmPasswordIcon').removeClass('fa-lock ').addClass('fa-unlock').css('color','green');
                flag = 1;
                // $("#registerPassword").prop("disabled", false).css({'background-color':'#1b8af3','cursor': 'pointer'});
            }else{

                $('#PasswordIcon').removeClass('fa-unlock ').addClass('fa-lock').css('color','#da344d');
                $('#ConfirmPasswordIcon').removeClass('fa-unlock ').addClass('fa-lock').css('color','#da344d');
                flag = 0
                // $("#registerPassword").prop("disabled", true).css({'background-color':'#1b8af394','cursor': 'no-drop'});
            }
        }else{

            flag = 0;
            // $("#registerPassword").prop("disabled", true).css({'background-color':'#1b8af394','cursor': 'no-drop'});

            $('#PasswordIcon').removeClass('fa-lock fa-unlock').addClass('fa-lock').css('color','#1b8af3');
            $('#ConfirmPasswordIcon').removeClass('fa-lock fa-unlock').addClass('fa-lock').css('color','#1b8af3');
        }

        if (flag == 1 && $("#AcceptTerms").is(":checked")) {
            return 1;
        }else{
            return 0;
        }
    }

    $("#AcceptTerms").click(function(){
        checkPassword();
    }); 
</script>

@endpush