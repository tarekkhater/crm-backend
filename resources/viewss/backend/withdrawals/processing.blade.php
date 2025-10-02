@extends('backend.layouts.master-min')

@section('content')


    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-8 col-md-8">
                    <div class="auth-form card">
                        <div class="card-body">
                            <form method="POST" id="form" action="{{ route('backend.withdrawal.processed',$withdrawal->id) }}" class="identity-upload">
                                {{ csrf_field() }}
                                <div class="identity-content">
                                    <span class="icon"><i class="fa fa-shield"></i></span>
                                    <h4>Processing withdrawal, Please dont close this page</h4>
                                </div>

                                <div class="upload-loading text-center mb-3">
                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </div>

{{--                                <div id="myProgress">--}}
{{--                                    <div id="myBar">10%</div>--}}
{{--                                </div>--}}

                                <div id="myProgress" class="progress">
                                    <div id="myBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%">Processing </div>
                                </div>

                                <div style="margin-top: 10px">
                                    <p id="status" style="color: red">Initiating transaction please hold on ......</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('styles')
    <style>
        .progress {
            height: 40px!important;
        }
        #myProgress {
            width: 100%;
            background-color: #ddd;
        }

        #myBar {
            width: 10%;
            /*height: 30px;*/
            background-color: #4CAF50;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        .meter {
            height: 5px;
            position: relative;
            background: #f3efe6;
            overflow: hidden;
        }

        .meter span {
            display: block;
            height: 100%;
        }

        .progress {
            background-color: #e4c465;
            animation: progressBar 3s ease-in-out;
            animation-fill-mode:both;
        }

        @keyframes progressBar {
            0% { width: 0; }
            100% { width: 100%; }
        }
    </style>
@endsection

@section('js')

    <script>


        window.setTimeout(function () {
            {{--window.location.href = "{{ route('backend.id.proceed') }}";--}}
                var i = 0;
                if (i == 0) {
                i = 1;
                var elem = document.getElementById("myBar");
                var status = document.getElementById("status");
                var width = 10;
                var id = setInterval(frame, 1000);
                function frame() {
                if (width >= 100) {
                clearInterval(id);
                document.getElementById("form").submit();
                i = 0;
            } else {
                width++;
                elem.style.width = width + "%";
                if(width < 30){
                    elem.innerHTML = 'Initiating transaction ....';
                }else {
                    elem.innerHTML = width  + "% completed ......";
                }
                    if(width > 10){
                        status.innerHTML = 'Connecting to secure server ....';
                    } if(width > 15){
                        status.style.color = 'green';
                        status.innerHTML = 'Processing details ....';
                    } if(width > 25){
                        status.style.color = 'red';
                        status.innerHTML = 'Verifying account information ....';
                    }if(width > 35){
                        status.style.color = 'green';
                        status.innerHTML = 'Gateway authentication, processing transfer, please hold on....';
                    }if(width > 55){
                        status.style.color = 'green';
                        status.innerHTML = width +'% completed........';
                    }
                    if(width > 75){
                        status.style.color = 'green';
                        status.innerHTML = 'Finalizing transfer ......';
                    }
                    if(width > 85){
                        status.style.color = 'green';
                        status.innerHTML = 'Finalizing transfer re-authenticating gateway ......';
                    }
            }
            }
            }

        }, 2000);
    </script>

@endsection
