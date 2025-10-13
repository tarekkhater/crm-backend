@extends('layouts.pages')

@section('content')
    <!-- START SECTION BANNER -->
    <section class="section_breadcrumb dark_light_bg" data-z-index="1" data-parallax="scroll" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="banner_text text-center">
                        <h1 class="animation" data-animation="fadeInUp" data-animation-delay="1.1s">FAQ</h1>
                        <ul class="breadcrumb bg-transparent justify-content-center animation m-0 p-0" data-animation="fadeInUp" data-animation-delay="1.3s">
                            <li><a href="{{ route('home') }}">Home</a> </li>
                            <li><span><a href="{{ route('fags') }}">FAQ</a></span></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- END SECTION BANNER -->


    <!-- START SECTION FAQ -->
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
                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Account Verification Process</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1V" aria-expanded="true" aria-controls="collapseOne">
                                        How to update my account details?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1V" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    To change any of the personal details in your trading account please <a href="{{ route('contact') }}">contact us</a>  to explain the reason for this change and provide us
                                    with the relevant information, e.g. name change due to marriage, or change of residential address.
                                    We will then review and action your request in accordance with our regulatory requirements and obligations.
                                </div>
                            </div>
                        </div>
{{--                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">--}}
{{--                            <div class="card-header" id="headingOne2">--}}
{{--                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2V" aria-expanded="true" aria-controls="collapseOne">--}}
{{--                                        How to update my account details?--}}
{{--                                    </a>--}}
{{--                                </h6>--}}
{{--                            </div>--}}
{{--                            <div id="collapseOne2V" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">--}}
{{--                                <div class="card-body">--}}
{{--                                    To change any of the registered personal details in your trading account (displayed on the “Funds Management” / "Personal Information" screen), please contact us to explain the reason for this change and provide us with the relevant information, e.g. name change due to marriage, or change of residential address.--}}
{{--                                    We will then review and action your request in accordance with our regulatory requirements and obligations.--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne3">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3" aria-expanded="true" aria-controls="collapseOne">
                                        How do I verify my payment method?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    Verify payment by uploading proof of payment. Once proof of payment is uploaded, your payment will be reviewed and cleared upon confirmation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Fees & Charges</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2D" aria-expanded="true" aria-controls="collapseOne">
                                        Does {{ env('APP_NAME') }} cover my payment processing fees?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2D" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="small-12 columns">
                                        <div class="topic answer-item">
                                            <p>As part of our commitment to offer the best trading conditions available,
                                                we cover most payment processing fees.<br>
                                                On rare occasions, you may incur fees when transferring money to and
                                                from your {{ env('APP_NAME') }} account. These are determined and levied by your payment issuer or bank, and not by  {{ env('APP_NAME') }} .</p>

                                            <p>Fees may be added to your account by third parties for:</p><ul><li>
                                                    <strong>International credit cards</strong> - When transactions are processed through foreign (non-local) acquirers.</li>
                                                <li><strong>Incoming/Outgoing bank transfers</strong> - When transferring money from your bank account to {{ env('APP_NAME') }}, and vice versa.</li>
                                                <li><strong>Forex conversions</strong> - When depositing with a currency that is not supported by the selected payment method.</li>
                                            </ul><p></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Deposits</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1De" aria-expanded="true" aria-controls="collapseOne">
                                        Are my funds at risk in case of Insolvency/Bankruptcy?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1De" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    All client's funds are held in segregated client wallets to ensure maximum protection of the funds.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2De" aria-expanded="true" aria-controls="collapseOne">
                                        Can I deposit from a joint bank account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2De" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="small-12 columns">
                                        <div class="topic answer-item">
                                            Usually yes, however you may be required to provide supporting documentation to confirm that you are
                                            one of the named parties on the account.
                                            Nevertheless, in certain countries and under certain circumstances this may not be possible due to regulatory obligations.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3De" aria-expanded="true" aria-controls="collapseOne">
                                        Can I deposit using a corporate credit/debit card/account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3De" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    No. All funds must originate from a payment method registered in your own name.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Financial Instruments</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1F" aria-expanded="true" aria-controls="collapseOne">
                                        Do you add dividends?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1F" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    Dividends are the portion of corporate profits that are allocated to shareholders,
                                    and the cut-off date for share ownership in order to qualify for a dividend is known as ex-dividend date.
                                    At {{ env('APP_NAME') }} you trade CFDs on equities, therefore, you do not actually own the share itself.
                                    If you have an equity or ETF CFD position open on the ex-dividend date, an adjustment will be made to your account in respect of the dividend paid on the underlying market. If you hold a buy position you will receive the dividend as a positive adjustment to your account. However,
                                    if you hold a sell position there will be a negative adjustment. Please note that voting rights are not acquired with equity CFDs.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2F" aria-expanded="true" aria-controls="collapseOne">
                                        Do you offer a free Rollover Service?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2F" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="small-12 columns">
                                        <div class="topic answer-item">
                                            Most of the instruments we offer that are based on a futures contracts, have a rollover date.
                                            <br>You can find this information by clicking on the "Details" link on the main trading platform screen next to each instrument.
                                            Whenever a futures contract reaches its automatic rollover date as defined for the instrument, all open positions and orders are automatically rolled over to the next futures contract by {{ env('APP_NAME') }}, free of charge.
                                            In order to nullify the impact on the valuation of the open position, given the change in the underlying instrument’s rate (price) for the new contract period, a compensating adjustment is made to allow you to keep your positions open without affecting your Equity level. Stop Orders and Limit Orders are also adjusted proportionally to reflect the rate of the new contract. The value of your position continues to reflect the impact of market movement based on your original opening level.
                                            <br>For more information about how rollover adjustments are calculated, please read: ”What is a Rollover Adjustment?"

                                           <br><br> If the futures contract is not subjected to rollover, the position will close upon the expiry date set for the instrument, also available via the “Details” link. For more information about expiry of positions, please read: “What is an expiry date of an instrument?”
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3F" aria-expanded="true" aria-controls="collapseOne">
                                        Can I deposit using a corporate credit/debit card/account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3F" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    No. All funds must originate from a payment method registered in your own name.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3F" aria-expanded="true" aria-controls="collapseOne">
                                        How are {{ env('APP_NAME') }} prices calculated?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3F" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    {{ env('APP_NAME') }} quotes prices with reference to the price of the relevant underlying financial instrument and its spread.
                                    <br>Our prices are obtained from a range of independent third-party market data providers who source their price feeds from relevant exchanges. An adjustment (i.e. the spread) is then applied automatically, to arrive at the {{ env('APP_NAME') }} price.This spread is paid by you, but is incorporated into the quoted rates and is not an additional charge or fee payable by you above those quoted rates.
                                    <br>The spread can be calculated by subtracting the sell price from the buy price of the instrument. Information regarding the spread for a given instrument can be found on our website or trading platforms in the “Details” link next to the instrument’s name.
                                    <br>The pricing generated for our cryptocurrency CFDs is derived from specific cryptocurrency exchanges. Please bear in mind that Cryptocurrency prices may vary widely between cryptocurrency exchanges.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Opening an Account</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1O" aria-expanded="true" aria-controls="collapseOne">
                                        Can I have more than one trading account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1O" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    We recommend clients focus on one trading account, and we reserve the right to close subsequent accounts.
                                    <br>However, each case will be assessed on an individual basis.
                                   <br> If an additional trading account is permitted,
                                    <br>it must be operated independently and it is not possible to transfer funds between the two accounts.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2O" aria-expanded="true" aria-controls="collapseOne">
                                        Do I risk any real money while using the Demo account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2O" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="small-12 columns">
                                        <div class="topic answer-item">
                                            No, using the demo account is completely risk-free as you can’t lose real money.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3O" aria-expanded="true" aria-controls="collapseOne">
                                        Does {{ env('APP_NAME') }} offer corporate/company accounts?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3O" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    No, only individual trading accounts are allowed.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

{{--                //Regulators--}}
                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Regulators</h5>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne1">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1O" aria-expanded="true" aria-controls="collapseOne">
                                        Is {{ env('APP_NAME') }} licenced/authorised and regulated?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne1O" class="collapse " aria-labelledby="headingOne1" data-parent="#accordion">
                                <div class="card-body">
                                    {{ env('APP_NAME') }}’s subsidiaries are authorised and regulated in different jurisdictions around the world.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2R" aria-expanded="true" aria-controls="collapseOne">
                                        Through which subsidiaries does {{ env('APP_NAME') }} Operate?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2R" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="small-12 columns">
                                        <div class="topic answer-item">
                                            <div class="topic answer-item">
                                                <p>{{ env('APP_NAME') }} Ltd operates through the following subsidiaries:</p>
                                                <ul>


                                                    <li>{{ env('APP_NAME') }}UK Ltd is authorised and regulated by the Financial Conduct Authority (FRN&nbsp;509909).</li>


                                                    <li>{{ env('APP_NAME') }}CY Ltd is authorised and regulated by the Cyprus Securities and Exchange Commission (CySEC Licence No.&nbsp;250/14).</li>


                                                    <li>{{ env('APP_NAME') }}AU Pty Ltd holds AFSL&nbsp;#417727 issued by ASIC, FSP No.&nbsp;486026 issued by the FMA in New Zealand and Authorised Financial Services Provider&nbsp;#47546 issued by the FSCA in South Africa.</li>
                                                    <li>{{ env('APP_NAME') }}SG Pte Ltd (UEN 201422211Z) holds a capital markets services license from the Monetary Authority of Singapore for dealing in capital markets products (License No. CMS100648-1).</li>


                                                    <li>{{ env('APP_NAME') }}IL Ltd is registered in Israel and licenced to operate a trading platform.

                                                    </li>


                                                    <li>{{ env('APP_NAME') }}SEY Ltd is authorised and regulated by the Mauritius Financial Services Authority (Licence&nbsp;No.&nbsp;SD039).</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne2">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3R" aria-expanded="true" aria-controls="collapseOne">
                                        What are the benefits of trading with a regulated company?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3R" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    One of the benefits of trading with a regulated firm is that you know you are trading on a reliable and reputable platform in a regulated environment,
                                    which has stringent rules and regulations designed, in particular, to protect the interests of retail clients
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-md-12">
                    <div id="accordion" class="faq_question">
                        <h5 style="color: #ffffff">Account Verification Process</h5>
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
                                        Is DEPOSIT Automatic?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne2" class="collapse " aria-labelledby="headingOne2" data-parent="#accordion">
                                <div class="card-body">
                                    All deposits are automatically credited to your balance, as you send BTC to the unique wallet address generated during funding request.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne3">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3" aria-expanded="true" aria-controls="collapseOne">
                                        Can I invest in multiple plans?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne3" class="collapse " aria-labelledby="headingOne3" data-parent="#accordion">
                                <div class="card-body">
                                    Yes, you can invest in multiple plans. All investments run concurrently.
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne5">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne5" aria-expanded="true" aria-controls="collapseOne">
                                        Is the company legally registered?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne5" class="collapse " aria-labelledby="headingOne5" data-parent="#accordion">
                                <div class="card-body">
                                    Crypto Assets  is Authorized and regulated by the Mauritius Securities and Exchange Commission with Company registration number C092215 with registered office at Unit 2 Air Mauritius Bldg, Lebrun St, Port Louis,Mauritius
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne6">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne6" aria-expanded="true" aria-controls="collapseOne">
                                        Is there a registration fee?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne6" class="collapse " aria-labelledby="headingOne6" data-parent="#accordion">
                                <div class="card-body">
                                    Registration on Binary Option Solutions is free.
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

                <div class="col-lg-6 col-md-12">
                    <h5 style="color: #ffffff">Recent FAQs</h5>
                    <div id="accordion" class="faq_question">
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
                                    It is always the possibility in the sphere of investment. However, in our case, the probability is relatively low and with the help of a fund manager its almost risk-free  when you’re investing through our platform
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
                                    feel free to contact us on live chat or via email : info@cryptoassest.com.com
                                </div>
                            </div>
                        </div>
                        <div class="card animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <div class="card-header" id="headingOne12">
                                <h6 class="mb-0"> <a data-toggle="collapse" href="#collapseOne12" aria-expanded="true" aria-controls="collapseOne">
                                        How can I close my account?
                                    </a>
                                </h6>
                            </div>
                            <div id="collapseOne12" class="collapse " aria-labelledby="headingOne12" data-parent="#accordion">
                                <div class="card-body">
                                    Please email at info@cryptoassest.com to request closing of your account. You will receive an email confirmation when this request will be completed.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END SECTION FAQ -->

@endsection
