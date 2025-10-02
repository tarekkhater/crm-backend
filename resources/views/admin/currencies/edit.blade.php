@extends('admin.layouts.admin-app')


@section('content')
@include('sweetalert::alert')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-item active"> Currency Pairs Layouts</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Currency Pair</h4>
    </div>

    <div class="br-pagebody">
        @include('notification')
        <div class="br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Update Currency Pair (enter base currency for forex and crypto)</h6>

            <form action="{{ route('admin.currencies.update', $currency_pair->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('name', optional($currency_pair)->name) }}" required type="text" name="name" placeholder="Enter Name">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Symbol (BTC): <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('sym', optional($currency_pair)->sym) }}" required type="text" name="sym" placeholder="Enter Symbol">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Exchange & Pair : <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('ex_sym', optional($currency_pair)->ex_sym) }}" required type="text" name="ex_sym" placeholder="Enter Exchange & Pair">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Base Currency (USD):</label>
                                <input class="form-control" value="{{ old('ex_sym', optional($currency_pair)->base) }}" type="text" name="base" placeholder="base currency">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Type (Crypto): <span class="tx-danger">*</span></label>
                                <select class="form-control" name="type">
                                    @foreach($types as $item)
                                    <option class="text-capitalize" {{ $currency_pair->type === $item ? 'selected' : '' }} value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Leverage: <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('leverage', optional($currency_pair)->leverage) }}" required type="number" step="any" name="leverage" placeholder="Enter leverage">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Image :  <span class="tx-danger">*</span></label>
                                @include('admin.partials.image-upload',['field' => 'image','image' => $currency_pair->image,'id' => 'img'])
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Commission (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('com',$currency_pair->com)}}" required type="number" step="any" name="com" placeholder="Enter Commission">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Buy Spread (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('buy_spread',$currency_pair->buy_spread)}}" min="0" required type="number" step="any" name="buy_spread" placeholder="Enter Buy Spread">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Sell Spread (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('sell_spread',$currency_pair->sell_spread)}}" min="0" required type="number" step="any" name="sell_spread" placeholder="Enter Sell Spread">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Can be traded: <span class="tx-danger">*</span></label>
                                <select name="disabled" class="form-control">
                                    <option {{ $currency_pair->disabled ? ' ' : 'selected'  }} value="0">Yes</option>
                                    <option  {{ $currency_pair->disabled ? 'selected' : '' }}  value="1">No</option>
                                </select>
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <button class="btn btn-primary" type="submit">Update Form</button>
                            </div>
                        </div><!-- col-8 -->




                        <!-- col-8 -->
                        {{--                        <div class="col-lg-6">--}}
                        {{--                                @include('admin.partials.image-uploader',['field' => 'logo'])--}}

                        {{--                        </div><!-- col-8 -->--}}
                    </div><!-- row -->
                </div><!-- form-layout -->
            </form>
        </div><!-- br-section-wrapper -->
    </div>

<!-- ########## END: MAIN PANEL ########## -->

        @section('js')
            <script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
            <script src="{{ asset('lib/jquery-switchbutton/jquery.switchButton.js') }}"></script>
            <script src="{{ asset('lib/peity/jquery.peity.js') }}"></script>
            <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
            <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
            <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
            <script src="{{ asset('lib/highlightjs/highlight.pack.js') }}"></script>
            <script>
                $(function(){
                    'use strict';

                    $('#datatable1').DataTable({

                        language: {
                            searchPlaceholder: 'Search...',
                            sSearch: '',
                            lengthMenu: '_MENU_ items/page',
                        },
                        "ordering": true
                    });

                    $('#datatable2').DataTable({
                        bLengthChange: false,
                        searching: false,
                        "ordering": true

                    });

                    // Select2
                    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

                });
            </script>
       @endsection

@endsection
