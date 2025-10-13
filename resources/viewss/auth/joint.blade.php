<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/auth/first/css/style.css">
    <link rel="icon" href="images/excel.png">
    <!--font awesome-->
    <link href="{{ asset('auth/first/font/css/all.css') }}" rel="stylesheet">
    <!-- Boostrap csss-->
    <link rel="stylesheet" href="{{ asset('auth/first/css/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('auth/first/font/css/fontawesome.min.css') }}">
    <title>{{ setting('site_name') }} | Register</title>
</head>
<body style="background-color: #f2f2f2;">

<!--Navigation-->

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img class="logo" src="{{ setting('logo','asset/images/logo.png') }}" width="150" height="35"  alt="">
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item text-white register">
                <a href="{{ route('login') }}" class="btn btn-primary btn-md">Login</a>
            </li>
        </ul>
    </div>
</nav>

<section class="form-section">
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 text-center mb-3">
                <h2>Joint Account Sign Up</h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mx-auto">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                    <form class="form-container" method="POST" action="{{ route('register') }}" id="form">
                        @csrf
                    <div class="row">
                        <div class="col text-center mb-3">
                            <h5>Primary Account</h5>
                            <label for="firstName"><span style="color: brown;">*</span> First Name</label>
                            <input name="first_name" value="{{ old('last_name') }}"  type="text" class="form-control" placeholder="First Name">
                        </div>
                        <div class="col text-center mb-3">
                            <h5>Secondary Account</h5>
                            <label for="firstName"><span style="color: brown;">*</span> First Name</label>
                            <input name="j_first_name" value="{{ old('j_last_name') }}"  type="text" class="form-control" placeholder="First Name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-center mb-3">
                            <label for="lastName"><span style="color: brown;">*</span> Last Name</label>
                            <input name="last_name" value="{{ old('last_name') }}" type="text" class="form-control" placeholder="Last Name">
                        </div>
                        <div class="col text-center mb-3">
                            <label for="firstName"><span style="color: brown;">*</span> Last Name</label>
                            <input name="j_last_name" value="{{ old('j_last_name') }}"  type="text" class="form-control" placeholder="Last Name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-center mb-3">
                            <label for="lastName"><span style="color: brown;">*</span> Country</label>
                            <select id="inputCountry" name="country" class="form-control mb-2">
                                <option selected>Your Country</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Anguilia">Anguilla</option>
                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bahrain</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bonaire">Bonaire</option>
                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="British India Ocean Ter">British India Ocean Ter</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Canary Island">Canary Island</option>
                                <option value="Central African">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Channel Island">Channel Island</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote D'Ivoire">Cote D'ivoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Curaco">Curaco</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equitorial Guinea">Equitorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Ethopia">Ethopia</option>
                                <option value="Falkland">Falkland</option>
                                <option value="Canary Island">Canary Island</option>
                                <option value="Faroe Island">Faroe Island</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="Canary Island">French Polynesia</option>
                                <option value="French Southern Ter">French Southern Ter</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Great Britain">Great Britain</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Zarie">Zarie</option>
                                <option value="Zimbabwe">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                            <small>Please make sure this is your country of permanent residence</small>
                        </div>
                        <div class="col text-center mb-3">
                            <label for="lastName"><span style="color: brown;">*</span> Country</label>
                            <select id="inputCountry" name="j_country" class="form-control mb-2">
                                <option selected>Your Country</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Anguilia">Anguilla</option>
                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bahrain</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bonaire">Bonaire</option>
                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="British India Ocean Ter">British India Ocean Ter</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Canary Island">Canary Island</option>
                                <option value="Central African">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Channel Island">Channel Island</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote D'Ivoire">Cote D'ivoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Curaco">Curaco</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="East Timor">East Timor</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equitorial Guinea">Equitorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Ethopia">Ethopia</option>
                                <option value="Falkland">Falkland</option>
                                <option value="Canary Island">Canary Island</option>
                                <option value="Faroe Island">Faroe Island</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="Canary Island">French Polynesia</option>
                                <option value="French Southern Ter">French Southern Ter</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Great Britain">Great Britain</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Zarie">Zarie</option>
                                <option value="Zimbabwe">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                            </select>
                            <small>Please make sure this is your country of permanent residence</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-center mb-3">
                            <label for="phone_code"><span style="color: brown;">*</span> Phone code</label>
                            <input name="phone_code" value="{{ old('phone_code') }}"  type="tel" class="form-control" placeholder="Phone code" required>
                        </div>
                        <div class="col text-center mb-3">
                            <label for="phoneNumber"><span style="color: brown;">*</span> Phone Number</label>
                            <input name="phone" value="{{ old('phone') }}"  type="tel" class="form-control" placeholder="Phone Number">
                        </div>
                        <div class="col text-center mb-3">
                            <label for="phoneNumber"><span style="color: brown;">*</span> Phone Number</label>
                            <input  name="j_phone" value="{{ old('j_phone') }}"  type="tel" class="form-control" placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-center mb-3">
                            <label for="email"><span style="color: brown;">*</span> Email</label>
                            <input name="email" value="{{ old('email') }}"  type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col text-center mb-3">
                            <label for="email"><span style="color: brown;">*</span> Email</label>
                            <input name="j_email" value="{{ old('j_email') }}"  type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col text-center mb-3">
                            <label for="email"><span style="color: brown;">*</span> Account type</label>
                            <input type="email" class="form-control" placeholder="Joint Account" readonly>
                        </div>
                        <div class="col text-center mb-3">
                            <label for="email"><span style="color: brown;">*</span> Account type</label>
                            <input type="email" class="form-control" placeholder="Joint Account" readonly>
                        </div>
                    </div>


                    <div class="row py-3">
                        <div class="col text-center mb-3">
                            <h3 class="text-muted">Login Details</h3>
                        </div>
                    </div>


                    <div class="form-group mb-4 ">
                        <input name="email" value="{{ old('email') }}"  type="email" class="form-control shadow-none" id="email" placeholder="Enter Email">
                    </div>
                    <div class="form-group mb-4 ">
                        <input name="username" value="{{ old('username') }}"  type="text" class="form-control shadow-none" id="username" placeholder="Enter Username">
                    </div>

                    <div class="form-group mb-4 password1">
                        <input name="password" type="password" class="form-control shadow-none" id="password" placeholder="Enter Password"><img class="eye" id="togglePassword" src="/auth/first/images/eye-slash.svg">
                    </div>
                    <div class="form-group mb-4 ">
                        <input name="password_confirmation" type="password" class="form-control shadow-none"  placeholder="Confirm Password">
                    </div>

                        <div class="form-group mb-5">
                            <select name="cur" class="form-control">
                                @foreach(\App\Models\Currency::all() as $item)
                                    <option value="{{ $item->code }}">{{ $item->name  }} ({{ $item->sign }})</option>
                                @endforeach
                            </select>
                        </div>


                        <button type="submit" class="btn btn-block btn-primary mb-3 btnReg">Create Joint Account</button>
                    <p class="text-center"><small>Forgot Password? <a href="{{ route('login') }}">Login now</a></small></p>
                </form>
            </div>
        </div>
    </div>
</section>


<div class="container warning-section">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="warning">
                <small class="text-muted">
                    <p>All trading involves risk. Only risk capital you're prepared to lose.
                    </p>
            </div>
            <button class="btn btn-md warning-risk text-bold" style="background-color: #fff;"><strong>RISK TAKING</strong></button>
        </div>
    </div>
</div>

<div class="mb-5">
    <hr>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col"><img src="auth/first/images/bit.png" width="60px" alt=""></div>
        <div class="col mt-3"><img src="/auth/first/images/skrill.998b3788.svg" width="60px" alt=""></div>
        <div class="col mt-3"><img src="/auth/first/images/visa.6aeb1ee6.svg" width="58px" alt=""></div>
        <div class="col mt-2"><img src="/auth/first/images/ethereum-eth-logo.png" width="68px" alt=""></div>
        <div class="col mt-4"><img src="/auth/first/images/perfect_money.4f95d945.svg" alt=""></div>
        <div class="col mt-3"><img src="/auth/first/images/tether-usdt-logo.png" width="60px" alt=""></div>
        <div class="col mt-4"><img src="/auth/first/images/wire-transfer.a723274a.png" width="65px" alt=""></div>
        <div class="col mt-4"><img src="/auth/first/images/download.png" width="65px" alt=""></div>
        <div class="col mt-3"><img src="/auth/first/images/download (1).png" width="65px" alt=""></div>
        <div class="col mt-4"><img src="/auth/first/images/mastercard.6d7ba986.svg" alt=""></div>
    </div>
</div>

<hr>



<div class="container warning-section">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="warning">
                <p class="text-muted">
                    The final products offered by the company includes Contracts for Difference (Blackstone Futures) and other complex financial products. Trading Blackstone Futures carries a high level of risk since leverage can work both to your advantrage and disadvantage. As a result, Blackstone Futures may not be suitable for all investors because it is possible to all your invested capital. You should never invest money that you cannot afford to lose. Before trading in the complex financial product offered, please ensure to understand the risk involved</p>
            </div>
            <button class="btn btn-md warning-btn text-bold" style="background-color: #fff;"><strong>RISK WARNING</strong></button>
        </div>
    </div>
</div>

<div class="container mb-5 mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6 mx-auto">
            <p class="text-muted">You are granted limited non exclusive, non-transferrable right to use the IP provided on this website for personal and non-commercial purposes in relation to the services offered on the Website only</p>
        </div>
    </div>

</div>

<div class="navbar fixed-bottom navbar-dark copy p-1" style="background-color: #343a40;">
    <div class="container">
        <div class="pl-3">
            <a href="{{ setting('terms') }}"><small style="font-size: 14px">Terms of Service</small></a>
        </div>
        <div class="pl-3">
            <a href="{{ setting('privacy_policy') }}"><small style="font-size: 14px">Privacy Policy</small></a>
        </div>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item text-white register">
                <small style="font-size: 14px">Copyright 2021 &copy; {{ setting('site_name') }} | All Right Reserved</small>
            </li>
        </ul>
    </div>
</div>





{{--<footer class="p-2 copy">--}}
{{--    <hr>--}}
{{--    <div class="text-center text-dark mt-3">--}}
{{--        <p>&copy; Copyright {{ date('Y') }} &copy; {{ setting('site_name') }} | All Right Reserved</p>--}}
{{--    </div>--}}
{{--</footer>--}}





<script src="{{ asset('auth/first/js/jquery.min.js') }}"></script>
<!-- Popper JS -->
<script src=" {{ asset('auth/first/js/popper.min.js') }} "></script>
<!-- Latest compiled JavaScript -->
<script src=" {{ asset('auth/first/js/bootstrap.min.js') }}"></script>


<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('bi-eye');
    });
</script>
</body>
</html>
