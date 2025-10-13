<!doctype html>
<html lang="en">
<head>
    <title>{{ setting('site_name') }} | Sign Up page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('auth/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('auth/css/extra.css')}}">
    <style>
        .home-bg {
            background: url(/img/bg_11.jpg) center center no-repeat fixed;
            -webkit-background-size: cover;
            background-size: cover;
        }
        .text-wrap:after {
            background-color: #121833;
            opacity: .99;
        }
        .vertical-stepper h3 {
            color: #f8f9fa;
        }
    </style>
</head>
<body class="home-bg">
<section class="ftco-section">
    <div class="container">
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-6 text-center mb-5">--}}
{{--                <h2 class="heading-section">Account Registration</h2>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="wrap d-md-flex">

                    <div class="text-wrap p-4 p-lg-5 d-flex img d-flex align-items-ends" style="background-image: url(/auth/images/bg.jpg);">

                        <div class="vertical-stepper">
                            <div class="justify-content-center text-center mt-4 mb-4">
                                <h3><span id="test_click">Account</span> Registration</h3>
                                You are just 4 simple steps away from placing your first trade
                            </div>
                            <div class="vertical-stepper__step">
                                <div class="vertical-stepper__step-bullet-wrapper"><div class="vertical-stepper__step-bullet vertical-stepper__step-bullet--selected">1</div></div>
                                <div class="vertical-stepper__step-content">
                                    <h3>Create your login</h3>
                                    <span>Enter your email and choose a password to get started</span>
                                </div>
                            </div>
                            <div class="vertical-stepper__step">
                                <div class="vertical-stepper__step-bullet-wrapper"><div class="vertical-stepper__step-bullet">2</div></div>
                                <div class="vertical-stepper__step-content">
                                    <h3>Complete your application</h3>
                                    <span>Complete our fast and secure application form</span>
                                </div>
                            </div>
                            <div class="vertical-stepper__step">
                                <div class="vertical-stepper__step-bullet-wrapper"><div class="vertical-stepper__step-bullet">3</div></div>
                                <div class="vertical-stepper__step-content">
                                    <h3>Confirm your ID</h3>
                                    <span>Verify your identity online or upload your ID documents if required</span>
                                </div>
                            </div>
                            <div class="vertical-stepper__step">
                                <div class="vertical-stepper__step-bullet-wrapper"><div class="vertical-stepper__step-bullet">4</div></div>
                                <div class="vertical-stepper__step-content vertical-stepper__step-content--last">
                                    <h3>Fund and trade</h3>
                                    <span>Once approved, fund your account using our flexible, low-cost funding methods and start trading</span>
                                </div>
                            </div>
                        </div>

{{--                        <div class="text w-100">--}}
{{--                            <h2 class="mb-4">Account Registration</h2>--}}
{{--                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>--}}
{{--                        </div>--}}
                    </div>
                    <div class="login-wrap p-4 p-md-5">
{{--                        <h3 class="mb-3">Create an account</h3>--}}
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <form method="POST" action="{{ route('register') }}" class="signup-form">
                            @csrf
                            <p>You were reffered by: <strong>{{ $userData }}.</strong></p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="first_name">First Name</label>
                                        <input name="first_name" id="first_name" type="text" class="form-control" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="first_name">Last Name</label>
                                        <input name="last_name" id="last_name" type="text" class="form-control" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="email">Email Address</label>
                                        <input name="email" id="email" type="email" class="form-control" placeholder="johndoe@email.com">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="userId">Username
                                        <br/>
                                            [no special char or space]
                                        </label>
                                        <input pattern="[a-zA-Z0-9]*" autocomplete="false"
                                        name="username" id="userId" type="text" class="form-control" placeholder="johndoe" >
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="password_confirmation" >Country</label>
                                        <select onchange="endpoint('{{ route('get-country-info','') }}',this.value)" name="country" id="country" class="form-control" >
                                            <option value=" " selected>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->nicename ?? '' }}"  id="{{ $country->phonecode }}" >{{ $country->nicename ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                    <label class="label" for="phone">Phone Code.</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text" style="background-color:white;">
                                                <image id="flag-icon" src="{{ asset('./flags/dd.png') }}" width="32" height="22"/>
                                            </span>
                                            </div>
                                          
                                            <input id="phone_code" type="text" name='phone_code' class="input-md form-control" placeholder="code" readonly>
                                        </div>
                                        <!-- <label class="label" for="phone">Country Code.</label>
                                        <select name="phone_code" class="form-control">
                                            <option id="phone_code" value=""></option>
                                        </select> -->
                                    </div>
                                </div>
                            
                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="phone">Phone no.</label>
                                        <input id="phone" name="phone" type="text" class="form-control" placeholder="">
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="password">Password</label>
                                        <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                             <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="password_confirmation">Confirm Password</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group d-flex align-items-center">
                                        <label class="label" for="password_confirmation">Currency</label>
                                        <select name="cur" class="form-control">
                                            @foreach($curs as $item)
                                                <option value="{{ $item->code }}">{{ $item->name  }} ({{ $item->sign }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 my-4">
                                    <div class="form-group">
                                        <div class="w-100">
                                            <label class="checkbox-wrap checkbox-primary">I agree all statements in terms of service
                                                <input required type="checkbox" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary submit p-3">Submit</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <a href="{{ route('joint.reg') }}" type="submit" class="btn btn-success p-3">Open Joint Account</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="social-wrap">
                            <p class="or">
                                <span>or</span>
                            </p>
                            <p class="mb-3 text-center">Already have an account ? <a href="{{ route('login') }}" class="card-link">Login</a></p>

                            <p class="social-media d-flex justify-content-center">

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('auth/js/jquery.min.js') }}"></script>
<script src=" {{asset('auth/js/popper.js') }} "></script>
<script src="{{ asset('auth/js/bootstrap.min.js') }} "></script>
<script src="{{ asset('auth/js/main.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js" integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script defer>


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
