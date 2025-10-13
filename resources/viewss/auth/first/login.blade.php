<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/auth/first/css/style.css">
     <link rel="icon" href="/auth/first/images/excel.png">
        <!--font awesome-->
    <link href="/auth/first/font/css/all.css" rel="stylesheet">
    <!-- Boostrap csss-->
    <link rel="stylesheet" href="/auth/first/css/bootstrap.min.css">
    <link rel="stylesheet" href="/auth/first/font/css/fontawesome.min.css">
    <title>{{ setting('site_name') }}</title>

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
    </style>

</head>
<body style="background-color: #f2f2f2;">

<!--Navigation-->

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ setting('site_info_url') }}">
            <img class="logo" src="{{ setting('logo','asset/images/logo.png') }}" width="100%" height="40"  alt="">
        </a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item text-black-50 mr-4 mt-2">
                <img height="30" src="{{ asset('img/flag.png') }}" /> En
            </li>
          <li class="nav-item text-white register">
              <a href="{{ route('register') }}" class="btn btn-primary btn-md">Sign Up</a>
          </li>
        </ul>
    </div>
  </nav>
    <section class="form-section">
        <div class="container mb-5">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2>Login</h2>
                </div>
            </div>
            <div class="row mb-3">

                <div class="col-md-6 mx-auto">


                    @error('email')
                    <div style="margin-bottom: 5px" class="invalid-feedbac text-center " role="alert">
                        <strong style="color: red">{{ $message }}</strong>
                    </div>
                    @enderror

                    <form class="form-container" id="form" action="{{ route('login') }}" method="POST" >
                        @csrf

                        <div class="form-group mt-3 mb-4">
                            <input name="email" value="{{ old('email') }}" type="email" class="form-control shadow-none" id="email" placeholder="Email">
                        </div>

                        <div class="form-group d-flex mb-4 align-items-cent">
                            <input required name="password" type="password" class="form-control shadow-none" id="password" placeholder="Your Password">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon1"> <i  id="togglePassword" class="fas fa-eye-slash"></i></span>
                            </div>
                        </div>


                        <a style="font-weight: 300; font-size: 0.9em" href="{{ route('password.request') }}" class="text-danger"><p class="text-center">Forgot password?</p></a>
                        <p class="text-muted text-center">Don't have an account? <a style="font-weight: 300; font-size: 0.9em" href="{{ route('register') }}" class="text-danger">Register</a></p>
                        <button type="submit" class="btn btn-block btn-primary mb-3 btnReg">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


@include('auth.first.footer')




<script src="/auth/first/js/jquery.min.js"></script>
   <!-- Popper JS -->
<script src="/auth/first/js/popper.min.js"></script>
   <!-- Latest compiled JavaScript -->
<script src="/auth/first/js/bootstrap.min.js"></script>

<script>

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        const eye = password.getAttribute('type') === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        password.setAttribute('type', type);
        this.setAttribute('class', eye)
    });


</script>
</body>
</html>
