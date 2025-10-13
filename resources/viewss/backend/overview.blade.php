@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="h4 my-auto text-capitalize">
                            My Profile</h4>
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Profile</a></li>
                        </ol>

                    </div>


                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="container py-5">
            @if(Auth::user()->referred_by > 0)
                <p>Referred by: <strong>
                    {{ DB::table('users')->where('id', Auth::user()->referred_by)->value('username') }}
                </strong></p>
            @endif
            <!-- @if(Auth::user()->referred_by != NULL)
                <p>Referred by: <strong>
                    {{ DB::table('users')->where('id', Auth::user()->referred_by)->value('username') }}
                </strong></p>
            @endif -->
            <p>
                <!--Referrer's link: <a href="{{ url('/ref/' . Auth::user()->id) }}" target="__blank">{{ url('/ref/' . Auth::user()->id) }}</a>-->
            </p>
            <p>
               <!-- You have referred: <strong>{{ $ref = DB::table('users')->where('referred_by', Auth::user()->id)->count() }}</strong> {{ $ref < 1 ? 'people' : 'peoples'}}.-->
            </p>
            @if(auth()->user()->hasRole(['superadmin','admin','manager']))
                <p>
                    <form action="{{ route('backend.user.token.generate') }}" method="post">
                        @csrf
                        @if(auth()->user()->access_token)
                            <div class="input-group my-3">
                                @php
                                    $token = auth()->user()->access_token;
                                @endphp
                            <!--    <input type="text" id="walletAddress" readonly value="{{ $token }}" class="form-control" placeholder="Wallet Address" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" onclick="copyAccessToken('{{ $token }}')" type="button">
                                        <span class="fa fa-copy" id="copyText"></span>
                                    </button>-->
                                </div>
                            </div>
                        @endif
                       <!-- <button class="btn btn-primary">Generate Access Token</button>-->
                    </form>
                </p>
            @endif

        <!-- START: Card Data-->


        <profile :user='@json(auth()->user())' :accounts='@json($accounts)' :messages='@json($messages)'
                 :transactions='@json($transactions)'
                 interv="{{ setting('api_interval',1000) }}"
                 plan="{{ auth()->user()->plan }}"
                 :trades='@json($trades)'
                 :open_trades='@json($open_trades)'
                 check_trade_url="{{ route('backend.api.trade.check') }}"
                 close_trade_url="{{ route('backend.api.trade.close') }}"
                 trade_url="{{ route('backend.trade.store') }}"
                 all_trade_url="{{ route('backend.api.trades') }}"
                 edite_profile="{{route('backend.profile.edit')}}"
                 path="{{asset('icon/')}}"
        >

        </profile>

        <!-- END: Card DATA-->
    </div>


@endsection

@section('js')
    <script type="text/javascript">
        function copyAccessToken(accessToken) {
            console.log(accessToken);
            navigator.clipboard.writeText(accessToken);
            document.getElementById('copyText').innerHTML = ' Copied!'
            setTimeout(() => {
                document.getElementById('copyText').innerHTML = '';
            }, 2000);
        }
    </script>
@endsection

