<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/auth/second/css/style.css">
        <!--font awesome-->
    <link href="/auth/second/font/css/all.css" rel="stylesheet">
    <!-- Boostrap csss-->
    <link rel="stylesheet" href="/auth/second/css/bootstrap.min.css">
    <link rel="stylesheet" href="/auth/second/font/css/fontawesome.min.css">
    <title>{{ setting('site_name') }} | Login</title>
</head>
<body style="background-color: #252525;">

<!--Navigation-->

  <section class="form-section">
          <div class="row mb-md-5">
              <div class="col-md-4 labels col-12 mt-2" style="overflow: hidden;">
                  <div class="row">
                    <div class="col-12 excel">
                      <a class="" href="#">
                        <img class="logo" src="{{ setting('logo') }}" width="170" alt="">
                     </a>
                    </div>
                  </div>

                  @error('email')
                  <div style="margin-bottom: 5px" class="invalid-feedbac text-center " role="alert">
                      <strong style="color: red">{{ $message }}</strong>
                  </div>
                  @enderror

                      <form class="form-container" id="form" action="{{ route('login') }}" method="POST" >
                          @csrf
                      <div class="text-white mb-5 Welcome">
                          <p style="font-weight: 300; line-height: 1.3;">Log into your Account to continue</p>
                      </div>

                        <div class="form-group mb-5 text-white">
                          <label for="email">Email ID</label>
                          <input type="email" value="{{ old('email') }}" name="email" class="form-control shadow-none" id="email" style="outline: none;">
                        </div>

                        <div class="form-group mb-5 text-white">
                          <label>Password</label>
                          <input type="password" name="password" class="form-control shadow-none" id="password">
                          <i id="togglePassword" class="far fa-eye"></i>
                        </div>


                        <button type="submit" class="btn text-dark mb-5 mt-3 login" style="background-color: white;">Log In</button>
                        <p class="text-white link" style="font-size: 12px;"><a href="{{ route('password.request') }}">FORGOT YOUR PASSWORD</a></p>
                        <p class="text-white" style="font-size: 12px;">Don't have an Account? <a href="{{ route('register') }}" class="text-white link">REGISTER</a></p>
                    </form>
              </div>


                <div class="col-md-8 background">
                  <div class="overlay">

                  </div>
                </div>

  </section>
  <div class="navbar fixed-bottom navbar-dark copy p-1" style="background-color: #252525;">
    <div class="container">
      <div class="pl-3 d-none d-md-block">
        <a href="{{ setting('terms') }}"><small style="font-size: 14px; text-decoration: none;">Terms of Service</small></a>
    </div>
    <div class="pl-3 d-none d-md-block">
         <a href="{{ setting('privacy_policy') }}"><small style="font-size: 14px; text-decoration:none">Privacy Policy</small></a>
    </div>
         <ul class="navbar-nav ml-auto">
          <li class="nav-item text-white register">
              <small style="font-size: 14px">Copyright 2021 &copy; {{ setting('site_name') }}} | All Right Reserved</small>
          </li>
        </ul>
    </div>
</div>

<script>
  const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function (e) {
// toggle the type attribute
const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
password.setAttribute('type', type);
// toggle the eye / eye slash icon
this.classList.toggle('fa-eye');
});

</script>


<script src="/auth/second/js/jquery.min.js"></script>
   <!-- Popper JS -->
<script src="/auth/second/js/popper.min.js"></script>
   <!-- Latest compiled JavaScript -->
<script src="/auth/second/js/bootstrap.min.js"></script>
</body>
</html>
