@extends('layouts.master')

@section('content')
    <!-- HEADER TITLE BREADCRUBS SECTION -->
    <div class="header-title-breadcrumb relative">
        <div class="header-title-breadcrumb-overlay text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-6 text-left">
                        <h1>Contact</h1>
                    </div>
                    <div class="col-md-5 col-sm-6 col-xs-6">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="active">Contact</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="no-padding content-area no-sidebar" role="main">
        <div class="container-fluid">
            <div class="row entry-content">
                <!-- Section1 - Cryptic About Us -->
                <div class="clearfix"></div>
                <div class="cryptic_about_us padding_80 data_background text-center" data-background="/assets/images/bitcurrency-members.jpg">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title-subtile-holder wow ">
                                    <h1 class="section-title dark_title">CONTACT US</h1>
                                    <div class="section-subtitle dark_subtitle">Have a question or inquiry to make<br /> Contact Binary 24 Trade.
                                    </div>
                                </div>
                                <div class="spacer_80"></div>
                                <div class="clearfix"></div>
                                <div role="form" lang="en-US" dir="ltr">
                                    <div class="screen-reader-response"></div>

                                    <form id="contact_form"  action="#"  class="wpcf7-form" >
                                        <div class="bitcurrency-contact">
                                            <input type="text" name="user_name" class="" value="" size="40" aria-required="true" aria-invalid="false" placeholder="First Name" />
                                            <br />
                                            <input type="text" name="user_last_name" class="" value="" size="40" aria-required="true" aria-invalid="false" placeholder="Last Name" />
                                            <br />
                                            <input type="email" name="user_email" class="" value="" size="40" aria-required="true" aria-invalid="false" placeholder="Email Adress" />
                                            <br />
                                            <textarea name="user_message" class="" cols="40" rows="10" aria-invalid="false" placeholder="Insert message"></textarea>
                                            <input type="submit" name="contact_me" value="Send Message" class="wpcf7-form-control wpcf7-submit" />
                                            <p class="success_message">Thank you! We'll get back to you as soon as possible.</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section2 - Cryptic Subscribe to our Newsletter -->
                <div class="clearfix"></div>

                <div id="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2427.9353394185005!2d13.37962631596193!3d52.51650924424007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a851c428a92a43%3A0x784cfd724198fb47!2sBrandenburger+Tor!5e0!3m2!1sen!2sro!4v1524741844542" style="width:100%;height:400px" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection
