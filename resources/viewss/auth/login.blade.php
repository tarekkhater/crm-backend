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
    <title>{{ setting('site_name') }}</title>
</head>
<body class="d-flex flex-column justify-content-between" style="height: 100vh;">

<!--Navigation-->

<nav class="navbar login-navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a class="navbar-brand pt-3" href="/">
                <img class="logo img-fluid" src="{{ setting('logo','asset/images/logo.png') }}"  width="100%" height="100"  alt="">
            </a>
            <div class="mt-3">
                <a class="btn btn-primary text-white" href="{{ url('register') }}">Create Account</a>
            </div>
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


                <form action="{{ route('login') }}" style="background: #0a00008a" method="POST" class="form-container mx-auto pt-5" id="form">
                    @csrf
                    @error('email')
                    <div style="margin-bottom: 5px" class="invalid-feedbac " role="alert">
                        <strong style="color: red">{{ $message }}</strong>
                    </div>
                    @enderror



                    <div class="form-group mb-5">
                        <input type="email" name="email" class="form-control" id="first-name" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <input name="password" id="password" type="password" class="form-control" placeholder="password">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="text-right">
                        <input type="checkbox" id="toggle-password">
                        <label for="toggle-password" style="color: #fff;">Show Password</label>
                    </div>

                    <div class="mb-5">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-block text-white mb-3 btn-primary">Log In</button>
                    <div class="">
                        <p class="data" style="color: rgba(255, 255, 255, 0.5);"><i class="fas fa-lock text-white"></i> We take protection of your data seriously</p>
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
</body>
</html>
