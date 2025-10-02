
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="aut/css/style.css">
    <!--font awesome-->
    <link href="font/css/all.css" rel="stylesheet">
    <!-- Boostrap csss-->
    <link rel="stylesheet" href="aut/css/bootstrap.min.css">
    <link rel="stylesheet" href="font/css/fontawesome.min.css">
    <title>{{ setting('site_name') }} | Registeration</title>
</head>
<body class="d-flex flex-column justify-content-between" style="">

<!--Navigation-->

<nav class="navbar login-navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a class="navbar-brand pt-3" href="/">
                <img class="logo img-fluid" src="{{ setting('logo','asset/images/logo.png') }}"  width="100%" height="100"  alt="">
            </a>
{{--            <div class="mt-3">--}}
{{--                <a class="btn btn-primary text-white" href="{{ url('register') }}">Create Account</a>--}}
{{--            </div>--}}
        </div>
    </div>
</nav>

<section class="form-section">
    <div class="container">
        <div class="row mb-3 justify-content-center">
            <div class="col-lg-4 col-md-6 col-12">
                {{--                @if ($errors->any())--}}
                {{--                    <ul class="alert alert-danger">--}}
                {{--                        @foreach ($errors->all() as $error)--}}
                {{--                            <li>{{ $error }}</li>--}}
                {{--                        @endforeach--}}
                {{--                    </ul>--}}
                {{--                @endif--}}


                <form action="{{ route('register') }}" style="background: #0a00008a" method="POST" class="form-container mx-auto pt-5" id="form">
                    @csrf
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif



                    <div class="form-group ">
                        <input type="text" value="{{ old('first_name') }}" required name="first_name" class="form-control" id="first-name" placeholder="Your First Name">
                    </div>
                    <div class="form-group ">
                        <input type="text" value="{{ old('last_name') }}" required name="last_name" class="form-control"  placeholder="Your Last Name">
                    </div>


                    <div class="form-group ">
                        <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="first-name" placeholder="Email">
                    </div>

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
                                        <img id="flag-icon" src="{{ asset('./flags/dd.png') }}"  style="height: 30px; padding: 3px"/>
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


                    {{--                    <div class="col-md-12">--}}
                    {{--                    </div>--}}


                    <div class="form-group">
                        <input name="password" id="password" type="password" class="form-control" placeholder="password">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="text-right">
                        <input type="checkbox" id="toggle-password">
                        <label for="toggle-password" style="color: #fff;">Show Password</label>
                    </div>


                    <div class="form-group d-flex align-items-cent">
                        <input required name="password_confirmation" type="password" class="form-control shadow-none" id="cPassword" placeholder="Confirm Password">
                    </div>

                    <div class="form-group">
                        <p class="label text-white" for="password_confirmation">Currency</p>
                        <select name="cur" class="form-control">
                            @foreach($curs as $item)
                                <option value="{{ $item->code }}">{{ $item->name  }} ({{ $item->sign }})</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="text-white mb-2">
                        Already Have an account <a href="{{ route('login') }}">Login</a>
                    </div>

                    <button type="submit" class="btn btn-block text-white mb-3 btn-primary">Submit</button>
                    <div class="">
                        <p class="data" style="color: rgba(255, 255, 255, 0.5);"><i class="fas fa-lock text-white"></i>By clicking submit confirms you agree to our terms of service</p>
                    </div>
                </form>
            </div>
        </div>
    <!-- <div class="last ml-5">
            <p class="text-white">New to {{ setting('site_name') }} ? <a href="/register">Register</a></p>
        </div> -->

    </div>
</section>

<footer class="auth-footer justify-content-between px-4 py-3">
    <div>
        <p class="mb-0">Copyright © {{ date('Y') }} {{ setting('site_name') }}. All rights reserved.</p>
    </div>
    <div>
        <a href="{{ setting('privacy_policy') }}">Privacy Policy</a>
        <span>|</span>
        <a href="{{ setting('terms') }}">Terms of Service</a>
    </div>
</footer>


<script src="aut/js/jquery.min.js"></script>
<!-- Popper JS -->
<script src="aut/js/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="aut/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js" integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    const password = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");

    togglePassword.addEventListener("click", toggleClicked);
    function toggleClicked() {
        if (this.checked) {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
</script>

<script defer>

    // $(function() {
    //     $('.togglePassword').click(function (e) {
    //         alert('asdflkjasdf');
    //         // var type = this.closest('input-group').find('input[type="password"]').val();
    //         // alert(type);
    //     });
    // })

    function phone_code(phone_code,cs_name)
    {
        document.getElementById('phone_code').value = "+"+phone_code;
        document.getElementById('phone_code').innerHTML = "+"+phone_code;
        document.getElementById('flag-icon').src =  cs_name;

        alert('Testig clicking.');

    }

    $('#test_click').click(function(){
        alert('Testig clicking.');
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

    // $('#country').on('change', function() {
    //     var selected = this.value;
    //     alert(this.value)
    // });

    function selectC()
    {
        $('#phone_code').val(" ");
        $('#phone_code').html(" ");
    }

    function clsAlphaNoOnly (e) {  // Accept only alpha numerics, no special characters
        var regex = new RegExp("^[a-zA-Z0-9 ]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    }
    function clsAlphaNoOnly2 () {  // Accept only alpha numerics, no special characters
        return clsAlphaNoOnly (this.event); // window.event
    }


</script>
</body>
</html>

