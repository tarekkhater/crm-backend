<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crypto Asset </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/back/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">
    <link rel="stylesheet" href="/back/css/style.css">
</head>

<body>

<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>

<div id="main-wrapper">

    <div class="authincation section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    <div class="mini-logo text-center my-5">
                        <a href="{{ route('home') }}"><img src="/img/core-img/logo.png" alt=""></a>
                    </div>
                    <div class="auth-form card">
                        <div class="card-header justify-content-center">
                            <h4 class="card-title">Sign up your account</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('register') }}" class="signup_validate">
                                @csrf

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" required value="{{ old('username') }}" class="form-control" placeholder="" name="username">
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input required value="{{ old('first_name') }}" type="text" class="form-control" placeholder="" name="first_name">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" required value="{{ old('last_name') }}" class="form-control" placeholder="" name="last_name">
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" required value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder=""
                                           name="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="phone" required value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder=""
                                           name="phone">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" required class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                           name="password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" required class="form-control" placeholder=""
                                           name="password_confirmation">

                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-block">Sign up</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Already have an account? <a class="text-primary" href="{{ route('login') }}">Sign in</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<script src="/back/js/global.js"></script>

<script src="/back/vendor/waves/waves.min.js"></script>

<script src="/back/vendor/validator/jquery.validate.js"></script>
<script src="/back/vendor/validator/validator-init.js"></script>
<script src="/back/js/scripts.js"></script>
</body>

</html>
