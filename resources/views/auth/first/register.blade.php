<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/auth/first/css/style.css">
    <link rel="icon" href="images/excel.png">
    <!--font awesome-->
    <link href="/auth/first/font/css/all.css" rel="stylesheet">
    <!-- Boostrap csss-->
    <link rel="stylesheet" href="/auth/first/css/bootstrap.min.css">
    <link rel="stylesheet" href="/auth/first/font/css/fontawesome.min.css">
    <title>{{ setting('site_name') }} | Register</title>

    <link rel="shortcut icon" href="{{ setting('favicon', '/asset/images/logosm.png') }}" />


    <style>
        .btn-primary:not(:disabled):not(.disabled):active {
            background-color: {{ setting('primary_color_hover','#447007') }}!important;
            border-color: {{ setting('primary_color_hover','#447007') }}!important;
        }
        .btn-primary {
            background-color: {{ setting('primary_color','#6aac0e') }}!important;
            border-color: {{ setting('primary_color','#6aac0e') }}!important;
        }
        .btn-primary:hover {
            color: #fff;
            background-color: {{ setting('primary_color_hover','#447007') }}!important;
            border-color: {{ setting('primary_color_hover','#447007') }}!important;
        }

        .btn-outline-primary {
            color:  {{ setting('primary_color','#6aac0e') }}!important;;
            border-color:  {{ setting('primary_color','#6aac0e') }}!important;;
        }
        .btn-outline-primary:hover {
            color:  #fff!important;
            border-color:  {{ setting('primary_color_hover','#447007') }}!important;
            background-color: {{ setting('primary_color_hover','#447007') }}!important;
        }
    </style>

</head>
<body style="background-color: #f2f2f2;">

<!--Navigation-->

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ setting('site_info_url') }}">
            <img class="logo" src="{{ setting('logo','asset/images/logo.png') }}" width="100%" height="60"  alt="">
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item text-white register">
                <a href="{{ route('login') }}" class="btn btn-primary btn-md">Login</a>
            </li>
            @if(setting('joint_account') == 1)
            <li class="nav-item text-white register ml-4">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-md">Open Joint Account</a>
            </li>
                @endif
        </ul>
    </div>
</nav>


<section class="form-section">
        <div class="container mb-5">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2>Sign Up</h2>
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
{{--                        <div class="form-group mt-3 mb-4">--}}
{{--                            <input required type="text" name="username" value="{{ old('username') }}" class="form-control shadow-none" id="text" placeholder="Your username">--}}
{{--                        </div>--}}

                        <div class="form-group mt-3 mb-4">
                            <input required type="text" name="first_name" value="{{ old('first_name') }}" class="form-control shadow-none" id="text" placeholder="Your First Name">
                        </div>

                        <div class="form-group mt-3 mb-4">
                            <input required type="text"  name="last_name" value="{{ old('last_name') }}" class="form-control shadow-none" id="text" placeholder="Your Last Name">
                        </div>

                        <div class="form-group mt-3 mb-4">
                            <input required type="email" name="email" value="{{ old('email') }}"  class="form-control shadow-none" id="email" placeholder="Your Email Address">
                        </div>

                        {{-- <div class="form-group mb-5"> --}}


                        <div class="form-group d-flex align-items-center">
                            {{-- <label class="label" for="phone">Phone Code.</label> --}}
                            {{-- <div class="input-group"> --}}
                            <select required onchange="endpoint('{{ route('get-country-info','') }}',this.value)" name="country" id="country" class="form-control">
                                <option value=" " selected>Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->nicename ?? '' }}"  id="{{ $country->phonecode }}" >{{ $country->nicename ?? ''}}</option>
                                @endforeach
                            </select>

                            <div class="input-group-append">
                                    <span class="form-control" style="background-color:white;">
                                        <img id="flag-icon" src="{{ asset('./flags/dd.png') }}"  style="height: 100%; width: 100%; padding: 5px"/>
                                    </span>
                                </div>



                            {{--                                <input id="phone_code" type="text" name='phone_code' class="input-md form-control" placeholder="code" readonly>--}}
                            {{-- </div> --}}
                        </div>



                        <div class="form-group d-flex align-items-cent">
                            <div class="input-group-prepend">
                                <input id="phone_code" type="text" name='phone_code' class="input-md form-control" placeholder="code" readonly>
                            </div>
                            <input required id="phone" name="phone" type="text" value="{{ old('phone') }}" class="form-control" placeholder="Phone Number">
                        </div>


                        {{-- <div class="form-group mt-3 mb-4">

                            <input name="phone" value="{{ old('phone') }}" type="text" class="form-control shadow-none" id="tel" placeholder="Your Phone Number">
                        </div> --}}

                        <div class="form-group d-flex align-items-cent">
                            <input required name="password" type="password" class="form-control shadow-none" id="password" placeholder="Your Password">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon1"> <i  id="togglePassword" class="fas fa-eye-slash"></i></span>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-cent">
                            <input required name="password_confirmation" type="password" class="form-control shadow-none" id="cPassword" placeholder="Confirm Password">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon1"> <i id="toggleCPassword" class="fas fa-eye-slash"></i></span>
                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <select name="cur" class="form-control">
                                @foreach(\App\Models\Currency::all() as $item)
                                    <option value="{{ $item->code }}">{{ $item->name  }} ({{ $item->sign }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" required type="checkbox" value="" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">
                                I confirm that I am 18 years old or older and accept all the terms and conditions.
                            </label>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary mb-3 btnReg">Register</button>

                    </form>
                </div>
            </div>
        </div>
    </section>






@include('auth.first.footer')



{{--<footer class="p-2 copy">--}}
{{--    <hr>--}}
{{--    <div class="text-center text-dark mt-3">--}}
{{--        <p>&copy; Copyright {{ date('Y') }} &copy; {{ setting('site_name') }} | All Right Reserved</p>--}}
{{--    </div>--}}
{{--</footer>--}}





<script src="/auth/first/js/jquery.min.js"></script>
<!-- Popper JS -->
<script src="/auth/first/js/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="/auth/first/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js" integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    const toggleCPassword = document.querySelector('#toggleCPassword');
    const cPassword = document.querySelector('#cPassword');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        const eye = password.getAttribute('type') === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        password.setAttribute('type', type);
        this.setAttribute('class', eye)
    });

    toggleCPassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = cPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        const eye = cPassword.getAttribute('type') === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        cPassword.setAttribute('type', type);
        this.setAttribute('class', eye)
    });

    function endpoint(url,value){
        axios.get(url+'/'+value, {
            c_name : value
        })
        .then(function (response) {

            document.getElementById('phone_code').value = "+"+response.data.phonecode;
            document.getElementById('phone_code').innerHTML = "+"+response.data.phonecode;
            document.getElementById('flag-icon').src =  response.data.iso;

            console.log(response.data.iso)

        }).catch(function (error) {
            toastr.error('An error occuied, Please try again', 'Failed',{
                "positionClass" : "toast-top-right"
            })
        });
    }
</script>
</body>
</html>
