@extends('layouts.app')
@section('css')
    <style>
        .login-card {
            min-height: 80vh;
            /*background-image: url('https://selimdoyranli.com/cdn/material-form/img/bg.jpg');*/
            background-size: cover;
            -moz-background-size: cover;
            -ms-background-size: cover;
            -wenkit-background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12), 0 1px 6px 0 rgba(0, 0, 0, 0.12);
            z-index: 2;
            padding: 0;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-family: roboto!important;
        }
        .home-bg:after {
            background: linear-gradient(-135deg, rgb(63, 81, 181), rgb(233, 30, 99));
            /* Login Card Arkaplan Rengi */

            background: -webkit-linear-gradient(-135deg, rgb(63, 81, 181), rgb(233, 30, 99));
            /* Login Card Arkaplan Rengi */

            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            content: "";
            opacity: .9;
            z-index: 3;
        }
        .ftco-section{
            padding: 2em !important;
        }
        .shadow-sm{
            z-index: 999;
        }
        .sifre-hatirlat-form{
            z-index: 999 !important;
        }
        .home-bg > form {
            z-index: 4;
            position: relative;
            padding: 0px 25px;
            width: 100%;
        }
        .logo-kapsul {
            text-align: center;
            position: relative;
            opacity: 0.8;
        }
        .logo {
            height: auto;
            padding: 50px 0px;
        }
        /* form başlangıç stiller ------------------------------- */

        .group {
            position: relative;
            margin-bottom: 45px;
        }
        .group input {
            font-size: 18px;
            padding: 10px 10px 10px 10px;
            display: block;
            width: 100%;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            background: none;
            color: #eee;
        }
        .group input:focus {
            outline: none;
        }
        /* LABEL ======================================= */

        .group label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 18px;
            font-weight: normal;
            position: absolute;
            pointer-events: none;
            left: 5px;
            top: 5px;
            transition: 0.2s ease all;
            -moz-transition: 0.2s ease all;
            -webkit-transition: 0.2s ease all;
        }
        /* active durum */

        .group input:focus ~ label,
        input:valid ~ label {
            top: -20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        /* BOTTOM BARS ================================= */

        .bar {
            position: relative;
            display: block;
            width: 100%;
        }
        .bar:before,
        .bar:after {
            content: '';
            height: 2px;
            width: 0;
            bottom: 1px;
            position: absolute;
            background: rgba(255, 255, 255, 0.7);
            transition: 0.2s ease all;
            -moz-transition: 0.2s ease all;
            -webkit-transition: 0.2s ease all;
        }
        .bar:before {
            left: 50%;
        }
        .bar:after {
            right: 50%;
        }
        .group input:focus ~ .bar:before,
        .group input:focus ~ .bar:after {
            width: 50%;
        }
        /* HIGHLIGHTER ================================== */

        .highlight {
            position: absolute;
            height: 0%;
            width: 100px;
            top: 25%;
            left: 0;
            pointer-events: none;
            opacity: 0.5;
        }
        /* active durum */

        .group input:focus ~ .highlight {
            -webkit-animation: inputHighlighter 0.3s ease;
            -moz-animation: inputHighlighter 0.3s ease;
            animation: inputHighlighter 0.3s ease;
        }
        /* form animasyon ================ */

        @-webkit-keyframes inputHighlighter {
            from {
                background: rgba(255, 255, 255, 0.7);
            }
            to {
                width: 0;
                background: transparent;
            }
        }
        @-moz-keyframes inputHighlighter {
            from {
                background: rgba(255, 255, 255, 0.7);
            }
            to {
                width: 0;
                background: transparent;
            }
        }
        @keyframes inputHighlighter {
            from {
                background: rgba(255, 255, 255, 0.7);
            }
            to {
                width: 0;
                background: transparent;
            }
        }
        .input-ikon {
            font-size: 25px!important;
            position: relative;
        }
        .input-sifre-ikon {
            font-size: 22px!important;
            position: relative;
        }
        .span-input {
            margin-left: 10px;
            position: relative;
            top: -5px;
        }
        .giris-yap-buton,
        .kayit-ol-buton,
        .sifre-hatirlat-buton {
            background: linear-gradient(-135deg, rgb(63, 81, 181), rgb(233, 30, 99));
            background: -webkit-linear-gradient(-135deg, rgb(63, 81, 181), rgb(233, 30, 99));
            display: block;
            border: none;
            width: 100%;
            text-align: center;
            text-decoration: none;
            color: #eee;
            font-family: roboto;
            font-weight: 100;
            padding: 10px;
            border-radius: 3px;
            outline: none;
            opacity: 0.9;
        }
        .forgot-and-create {
            margin: 20px 0px;
        }
        .forgot-and-create button {
            color: #bbb;
            font-size: 12px;
            text-decoration: none;
            font-weight: 100;
            margin-right: 10px;
        }
        /* Geçiş Links Forgot and Create */

        .zaten-hesap-var-link {
            color: #FFF !important;
            font-size: 14px;
            padding: 20px 0px;
            text-decoration: none;
            /*display: block;*/
        }
    </style>

    @endsection
@section('content')
<div class="container">
<div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="post" id="sifre-hatirlat-form" style="z-index: 999; border-radius: 5px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.12), 0 1px 6px 0 rgba(0, 0, 0, 0.12);" class="col-lg-12">
@csrf
                <div class="col-lg-12 logo-kapsul">
                    <img width="100" class="logo" src="{{ setting('logo','asset/images/logo.png') }}" alt="Logo" />
                </div>


                <div style="clear:both;"></div>
                                        <h2 class="mb-1 fw-bold text-center text-white">Forgot Password? 🔒</h2>
                                        <p class="mb-4 text-center text-white">Enter your email and we'll send you instructions to reset your password</p>

                <!-- Şifre Hatırlat Email İnput -->
                <div class="group">
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" required name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>



                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                    <span class="highlight"></span>
                    <span class="bar"></span>
                    <label><i class="material-icons input-ikon">mail_outline</i><span class="span-input">E-Mail</span></label>
                </div>
                <!-- #Şifre Hatırlat Email İnput Bitiş -->

                <!-- Şifremi Hatırlat Buton -->
                <button  class="sifre-hatirlat-buton">{{__('Send Password Reset Link')}}</button>
                <!-- #Şifremi Hatırlat Buton Bitiş -->

                <!-- Zaten Hesap Var Link -->
                <a class="zaten-hesap-var-link" href="/"><< Home</a>
                <!-- #Zaten Hesap Var Link Bitiş -->


            </form>

{{--            <div class="card" style="background-color: #383d75">--}}
{{--                <div class="card-header">{{ __('Reset Password') }}--}}
{{--                <a href="/" class="float-right"> << Home</a>--}}
{{--                </div>--}}

{{--                <div class="card-body">--}}
{{--                   --}}

{{--                        <h2 class="mb-1 fw-bold text-center text-white">Forgot Password? 🔒</h2>--}}
{{--                        <p class="mb-4 text-center text-white">Enter your email and we'll send you instructions to reset your password</p>--}}

{{--                    <form method="POST" action="{{ route('password.email') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right text-white">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0 justify-content-center">--}}
{{--                            <div class="col-md-6 text-center">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Send Password Reset Link') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
@endsection
