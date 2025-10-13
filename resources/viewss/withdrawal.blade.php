@extends('layouts.pages')

@section('content')
    <!-- START SECTION BANNER -->
    <section class="section_breadcrumb dark_light_bg" id="w-fag" data-z-index="1" data-parallax="scroll">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="banner_text text-center">
                        <h1 class="animation" data-animation="fadeInUp" data-animation-delay="1.1s"> Withdrawal Info</h1>
                        <ul class="breadcrumb bg-transparent justify-content-center animation m-0 p-0" data-animation="fadeInUp" data-animation-delay="1.3s">
                            <li><a href="#">Home</a> </li>
                            <li><span> Withdrawal Info</span></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- START SECTION ABOUT US -->
    <section id="about" class="small_pt">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="text_md_center">
                        <img class="animation" data-animation="zoomIn" data-animation-delay="0.2s" src="https://binaryoptionsfx.online/asset/assets/images/about_img2.png" alt="aboutimg2"/>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 res_md_mt_30 res_sm_mt_20">
                    <div class="title_default_light title_border">
                        <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s">Withdrawal Info</h4>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            Crypto Assets was created in order to provide a better Binary Options service to traders
                            around the world. Our founders believed that there is a serious need for such a broker
                            because in spite of the fact that BOs are arguably the best online financial trading instrument
                            available on the market, in recent years public and institutional opinions have shifted notably in
                            a negative direction. </p>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                            This unfortunate reality was caused by the simple truth that traders were not receiving
                            the quality of service that they expected. As a result, in the recent 2 years the biggest Binary Options
                            brokers in the world, both European-regulated and offshore, closed down due to massive negative
                            feedback from their customers.</p>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                        It was not regulation, governments or a market crash that ruined these companies.
                            It was because their customers were unhappy with the way they were treated and the quality of
                            service they received in general. And when talking about quality of service,
                            we at cryptoassest.com are very confident that no one will argue with us when
                            we state that withdrawal requests form the biggest share of a trader’s general satisfaction with his/her broker.</p>

                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                            That is why we have created this page in order to make our promise to you as a trader, and to answer the questions most important to you now.
                            If the lines below are not sufficient to answer all of your concerns, we would like to kindly remind you that our customer support staff is available 24 hours a day for you.
                            Please do not hesitate to send us a message.
                        </p>

                    </div>
                    <a href="{{ route('login') }}" class="btn btn-default btn-radius video animation" data-animation="fadeInUp" data-animation-delay="1s">Let's Get Started <i class="ion-ios-arrow-thin-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12 offset-lg-2">
                    <div class="title_default_dark title_border text-center">
                        <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s">Frequently Asked Questions</h4>
                        <!-- <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">Frequently asked questions (FAQ) or Questions and Answers (Q&A), are listed questions and answers, all supposed to be commonly asked in some context</p> -->
                    </div>
                </div>
            </div>
            <div class="row small_space">
                <div class="col-lg-8 col-md-12 offset-lg-2">
                    <div id="accordion" class="faq_question">
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
                                        How long does withdrawals take?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1" class="collapse  show " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    Withdrawals are paid in a space of 0 - 24 hours, considering the functioning of the blockchain network when there are many transactions to be added to a block.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                                        How can I withdraw to my bank account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    It's very easy. Depending on your location, we will  re-direct you to a leading web wallet that will connect to your bank account. You can transact seamlessly between your Crypto Asset live trading account and your web wallet. If you want to move funds into your bank account, you can easily withdraw it from your web wallet. All of the web wallets we work with are leading and respected financial institutions, e-money issuers or payment service providers.
                                    Hence, you can rest assured that your money is being held by a licensed third party.
                                </div>
                            </div>
                        </div>

                        <!-- <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.6s">
          <div class="card-header" id="headingTwo">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Which cryptocurrency is best to buy today?</a> </h6>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body"> The best cryptocurrency to buy is one we are willing to hold onto even if it goes down. For example, I believe in Steem enough that I am willing to hold it even if it goes down 99% and would start buying more of it if the price dropped.</div>
          </div>
        </div>
        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.8s">
          <div class="card-header" id="headingThree">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">How about day trading crypto?</a> </h6>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body"> While profits are possible trading cryptocurrencies, so are losses. My first year involved me spending hundreds of hours trading Bitcoin with a result of losing over $5,000 with nothing to show for it. Simply trading digital currencies is very similar to gambling because no one really knows what is going to happen next although anyone can guess! While I was lucky to do nothing expect lose money when I started, the worst thing that can happen is to get lucky right away and get a big ego about what an amazing cryptocurrency trader we are. </div>
          </div>
        </div>
        <div class="card animation" data-animation="fadeInUp" data-animation-delay="1s">
          <div class="card-header" id="headingFour">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">When to sell a cryptocurrency?</a> </h6>
          </div>
          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
            <div class="card-body"> Before Steem I was all in an another altcoin and really excited about it. When I first bought the price was low and made payments to me every week just for holding it. As I tried to participate in the community over the next several months, I was consistently met with a mix of excitement and hostility. When I began talking openly about this, the negative emotions won over in the community and in me. Originally I had invested and been happy to hold no matter what the price which quickly went up after I bought it. </div>
          </div>
        </div>
        <div class="card animation" data-animation="fadeInUp" data-animation-delay="1.2s">
          <div class="card-header" id="headingFive">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">How does one acquire bitcoins?</a> </h6>
          </div>
          <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
            <div class="card-body">While it may be possible to find individuals who wish to sell bitcoins in exchange for a credit card or PayPal payment, most exchanges do not allow funding via these payment methods. This is due to cases where someone buys bitcoins with PayPal, and then reverses their half of the transaction. This is commonly referred to as a chargeback.</div>
          </div>
        </div>
        <div class="card animation" data-animation="fadeInUp" data-animation-delay="1.4s">
          <div class="card-header" id="headingSix">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Can I make money with Bitcoin?</a> </h6>
          </div>
          <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
            <div class="card-body">You should never expect to get rich with Bitcoin or any emerging technology. It is always important to be wary of anything that sounds too good to be true or disobeys basic economic rules.</div>
          </div>
        </div>
        <div class="card animation" data-animation="fadeInUp" data-animation-delay="1.6s">
          <div class="card-header" id="headingSeven">
            <h6 class="mb-0"> <a class="collapsed" data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">What happens when bitcoins are lost?</a> </h6>
          </div>
          <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
            <div class="card-body">When a user loses his wallet, it has the effect of removing money out of circulation. Lost bitcoins still remain in the block chain just like any other bitcoins. However, lost bitcoins remain dormant forever because there is no way for anybody to find the private key(s) that would allow them to be spent again. Because of the law of supply and demand, when fewer bitcoins are available, the ones that are left will be in higher demand and increase in value to compensate.</div>
          </div>
        </div> -->
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 offset-lg-2">
                    <div id="accordion" class="accordion-style">
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne7">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne7" aria-expanded="true" aria-controls="collapseOne">
                                        When can I withdraw money?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne7" class="collapse " aria-labelledby="headingOne7" data-parent="#accordion">
                                <div class="card-body">
                                    You can make a request at anytime, including weekends.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne8">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne8" aria-expanded="true" aria-controls="collapseOne">
                                        What is withdrawal limit?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne8" class="collapse " aria-labelledby="headingOne8" data-parent="#accordion">
                                <div class="card-body">
                                    There is no limit for withdrawals.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne9">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne9" aria-expanded="true" aria-controls="collapseOne">
                                        How long does it take to credit my deposit?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne9" class="collapse " aria-labelledby="headingOne9" data-parent="#accordion">
                                <div class="card-body">
                                    It needs 2-3 network confirmations and may take up to 30 minutes.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne10">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne10" aria-expanded="true" aria-controls="collapseOne">
                                        Can I lose my money?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne10" class="collapse " aria-labelledby="headingOne10" data-parent="#accordion">
                                <div class="card-body">
                                    It is always the possibility in the sphere of investment. However, in our case, the probability is relatively low and There is always an opportunity in the area of ​​investment.  In our case, however, the probability is relatively low and, with the help of a fund manager, it almost risk-free when you invest through our platform
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne11">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne11" aria-expanded="true" aria-controls="collapseOne">
                                        Can't find an answer to your question
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne11" class="collapse " aria-labelledby="headingOne11" data-parent="#accordion">
                                <div class="card-body">
                                    feel free to contact us on live chat or via email : info@cryptoassest.com
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- END SECTION ABOUT US -->

    <!-- END SECTION BANNER -->
    <!-- START SECTION SERVICES -->
    <section id="service" class="small_pb small_pt">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                    <div class="title_default_light title_border text-center">
                        <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s">How it Works</h4>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">Get instant results by following these 3 simple steps.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Sign Up</h4>
                        <img src="/images/service_icon1.png" alt="service_icon1"/>
                        <h4>Seamless</h4>
                        <p>Sign up via our registration link. Provide valid details in the form fields and hit the submit button.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Fund Account</h4>
                        <img src="/images/service_icon2.png" alt="service_icon1"/>
                        <h4>Automatic</h4>
                        <p>After verification of your details, add funds to your account. This  makes you ready to invest.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Start Earning</h4>
                        <img src="/images/service_icon3.png" alt="service_icon1"/>
                        <h4>Quick and easy</h4>
                        <p>Invest in any suitable plan on our platform and your money starts growing. Earn more via referrals.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END SECTION SERVICES -->


    <!-- START SECTION SERVICES -->

    <!-- START SECTION SERVICES -->
    <section id="token" class="section_token token_sale bg_light_dark" data-z-index="1" data-parallax="scroll" data-image-src="assets/images/token_bg.png">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                    <div class="title_default_light title_border text-center">
                        <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s">Our Features</h4>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">Great features for investors to join us. We operate a unique system.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Guaranteed Investment</h4>
                        <i class="fa fa-money fa-3x"></i>
                        <!-- <img src="https://binaryoptionsfx.online/asset/assets/images/" alt="service_icon1"/> -->
                        <!-- <h4></h4> -->
                        <p>Your investment begins to grow immediately you have invested. Profits begin to accrue to your investment as the clock ticks.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Quick Deposits</h4>
                        <i class="fa fa-bitcoin fa-3x"></i>
                        <!-- <img src="https://binaryoptionsfx.online/asset/assets/images/" alt="service_icon1"/> -->
                        <!-- <h4></h4> -->
                        <p>Our deposit system is automated and easy.<br /> Your balance is credited with deposited amount instantly.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <h4 class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase font-weight-600 text-black">Instant Withdrawals</h4>
                        <i class="fa fa-exchange fa-3x"></i>
                        <!-- <img src="https://binaryoptionsfx.online/asset/assets/images/" alt="service_icon1"/> -->
                        <!-- <h4></h4> -->
                        <p>Withdrawals are automatically processed within 0-10 minutes. Your funds arrives your wallet timely.</p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="steps-ul">
                    <ul>
                        <li class="li-1">
                            <h6 style="color: #fff" class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><a href="{{ route('register') }}" target="_blank">Register</a> with Crypto Assets and then <a href="{{ route('backend.deposits.create') }}" target="_blank">Make A Deposit</a>  If you already have a trading account with Crypto Assets, please, <a href="{{ route('login') }}" target="_blank">Login</a> and make a deposit..</h6>
                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/dash.png">
                    </div>
                    <ul style="margin-top: 40px">
                        <li class="li-2" style="color: #fff">
                            <h6 style="color: #fff"><strong>Multiple Payment System</strong></h6>
                            <p>Easy deposit and withdrawal with our multiple paymment channels.</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/1.png">
                    </div>
                    <ul style="margin-top: 40px">
                        <li class="li-3">
                            <h6 style="color: #fff"><strong>Realtime Trading Result.</strong></h6>

                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/2.png">
                    </div>

                </div>

            </div>

        </div>
    </section>

    <br/> <br/> <br/>



    <!-- END SECTION SERVICES -->


@endsection
