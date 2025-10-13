<!doctype html>
<html lang="en">

<head>
    <title>Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="home-bg">
    <section>
        <div class="container">
            <div class="p-4 p-md-5">
                <div id="message"></div>
                <form method="POST" action="" id="signup-form" onsubmit="return false;">
                    <div class="form-group row">
                        {{-- <label for="first_name" class="col-sm-2 col-form-label">First Name</label> --}}
                        <div class="col-sm-12">
                            <input name="first_name" id="first_name" type="text" class="form-control"
                                placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label for="first_name" class="col-sm-2 col-form-label">Last Name</label> --}}
                        <div class="col-sm-12">
                            <input name="last_name" id="last_name" type="text" class="form-control"
                                placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label class="col-sm-2 col-form-label" for="email">Email Address</label> --}}
                        <div class="col-sm-12">
                            <input name="email" id="email" type="email" class="form-control"
                                placeholder="johndoe@email.com">
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label class="col-sm-2 col-form-label" for="email">Country</label> --}}
                        <div class="col-sm-12">
                            <select onchange="fetchCountryInfo(this.value)" name="country" id="country"
                                class="form-control">
                                <option value="" selected>Select Country</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="ip_address" id="ip">
                    
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <div style="width: 65px; display: inline-block" class="input-group-prepend">
                                    <input id="phone_code" name="phone_code" value="" type="text" class="form-control"
                                        placeholder="code" readonly>
                                </div>
                                    <input id="phone" name="phone" type="text" class="form-control"
                                    placeholder="Number">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{-- <label class="col-sm-2 col-form-label" for="password">Password</label> --}}
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input id="password" name="password" type="text" class="form-control"
                                    placeholder="Password">
                                <div class="input-group-prepend">
                                    {{-- <span class="input-group-text">
                                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                    </span> --}}
                                    <span class="btn btn-secondary" id="password_generator" style="cursor: pointer">
                                        Generate
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm Password</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input id="password_confirmation" name="repeatPassword" type="password" class="form-control" placeholder="Confirm Password">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" id="togglePasswordConfirmation" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        {{-- <label class="col-sm-2 col-form-label" for="password_confirmation">Currency</label> --}}
                        <div class="col-sm-12">
                            <select name="cur" class="form-control" id="currency">
                                <option value="">Select Currency</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label class="col-sm-2 col-form-label" for="agreement"></label> --}}
                        <div class="col-sm-12">
                            <label class="checkbox-wrap checkbox-primary">
                                <input required type="checkbox" checked>&nbsp;
                                I agree all statements in terms of service
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-secondary py-2 px-3" onclick="signup()"
                                id="signup-btn">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('plugin/js/signup.js') }}"></script>
</body>

</html>
