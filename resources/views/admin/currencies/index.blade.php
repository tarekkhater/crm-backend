@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/highlightjs/github.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

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
        <p>
            @if(check_permission('create-currency-pair'))
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Add Currency Pair
            </button>
            @endif
            @if (check_permission('update-leverage'))
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#update_leverage" aria-expanded="false" aria-controls="collapseExample">
                Update Leverage
            </button>
            @endif
            @if(check_permission('update-buy'))
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#buy_update" aria-expanded="false" aria-controls="collapseExample">
                Update Buy
            </button>
            @endif
            @if(check_permission('update-sell'))
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#sell_update" aria-expanded="false" aria-controls="collapseExample">
                Update sell
            </button>
            @endif
        </p>

        @include('notification')
        @if(check_permission('create-currency-pair'))
        <div class="collapse" id="collapseExample">
        <div class="br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Currency Pair (Add base currency for crypto and forex)</h6>

            <form action="{{ route('admin.currencies.store') }}" method="POST">
                @csrf
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="name" placeholder="Enter Name">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Exchange & Symbol (COINBASE:BTCUSD): <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="ex_sym" placeholder="Enter Exchange & Symbol">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Symbol (BTC): <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="sym" placeholder="Enter Symbol">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Base Currency (USD):</label>
                                <input value="{{ old('base','USD') }}" class="form-control" type="text" name="base" placeholder="Bass Currency">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Type (Crypto): <span class="tx-danger">*</span></label>
                                <select class="form-control" name="type">
                                    @foreach($types as $item)
                                    <option value="{{ $item }}" class="text-capitalize">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Image :<span class="tx-danger">*</span></label>
                                @include('admin.partials.image-upload',['field' => 'image','id' => 'image'])

                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Leverage: <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('leverage',400)}}" required type="number" step="any" name="leverage" placeholder="Enter leverage">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Commission (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('com',4)}}" required type="number" step="any" name="com" placeholder="Enter Commission">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Buy Spread (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('buy_spread',0)}}" min="0" required type="number" step="any" name="buy_spread" placeholder="Enter Buy Spread">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Sell Spread (%): <span class="tx-danger">*</span></label>
                                <input  class="form-control" value="{{ old('sell_spread',0)}}" min="0" required type="number" step="any" name="sell_spread" placeholder="Enter Sell Spread">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Can be traded: <span class="tx-danger">*</span></label>
                              <select name="disabled" class="form-control">
                                  <option selected value="0">Yes</option>
                                  <option  value="1">No</option>
                              </select>
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">

                                <button class="btn btn-primary" type="submit">Submit Form</button>

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
        @endif
    </div>

    <div class="br-pagebody">
            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Currency Pairs List</h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table table-bordered table-condensed display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-15p">S.No</th>
                            <th class="wd-15p">Type</th>
                            <th class="wd-35p">Name / Sym / Leverage</th>
                            <th class="wd-35p">Exchange</th>
                            <th class="wd-25p">Commission</th>
                            <th class="wd-10p">Rate</th>
                            <th class="wd-10p">Image</th>

                            <th></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($currency_pairs as $currency_pair)

                        <tr>
                            <td>{{ $currency_pair->id }}</td>
                            <td class="text-capitalize">{{ $currency_pair->type }}
                            <br/>
                                @if ($currency_pair->disabled)
                                    <span class="badge badge-danger">Cant Be Traded</span>
                                @endif
                            </td>
                            <td>{{ $currency_pair->name }} / {{ $currency_pair->sym }} / ({{ $currency_pair->leverage }} X) <br/>
                            Buy spread : {{ $currency_pair->buy_spread }} % <br/>
                            Sell spread : {{ $currency_pair->sell_spread }} %
                            </td>
                            <td>{{ $currency_pair->ex_sym }}</td>
                            <td>{{ $currency_pair->com }}%</td>
                            <td>{{ $currency_pair->rate }}</td>
                            <td><img src="{{ $currency_pair->image }}" height="40" /> </td>

                            <td class="text-center">

                                <form method="POST" action="{!! route('admin.currencies.destroy', $currency_pair->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    {{ csrf_field() }}



                                </form>


                                    <div class="btn-group justify-center" role="group">
                                        @if(check_permission('delete-currency-pair'))
                                        <button type="submit" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                            <span class="fa fa-trash" aria-hidden="true"></span>
                                        </button>
                                        @endif
                                        @if(check_permission('update-currency-pair'))
                                        <a href="{{ route('admin.currencies.edit', $currency_pair->id ) }}" class="btn btn-primary" title="Edit Currency Pair">
                                            <span class="fa fa-edit" aria-hidden="true"></span>
                                        </a>
                                        @endif

                                    </div>


                            </td>

                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

    </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

    @if (check_permission('update-leverage'))
    <div class="modal" tabindex="-1" role="dialog" id="update_leverage">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">updte All Leverage</h5>
                    <button type="button" class="close" onclick="close_model()" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <label CLASS="col-form-label">Leverage</label>
                        <input class="form-control" type="number" step=".01" name="leverage" id="leverage_update">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="save_form('#leverage_update','{{route("admin.leverage_update")}}')" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" onclick="close_model()" >Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(check_permission('update-buy'))
    <div class="modal" tabindex="-1" role="dialog" id="buy_update">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">update All Buy</h5>
                    <button type="button" class="close" onclick="close_model()" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <label CLASS="col-form-label">Bue</label>
                        <input class="form-control" type="number" step=".01" name="update_bue" id="update_bue">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="save_form('#update_bue','{{route("admin.buy_update")}}')" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" onclick="close_model()" >Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(check_permission('update-sell'))
    <div class="modal" tabindex="-1" role="dialog" id="sell_update">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">update All Buy</h5>
                    <button type="button" class="close" onclick="close_model()" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        <label CLASS="col-form-label">Sell</label>
                        <input class="form-control" type="number" step=".01" name="update_sell" id="update_sell">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="save_form('#update_sell','{{route("admin.sell_update")}}')" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" onclick="close_model()" >Close</button>
                </div>
            </div>
        </div>
    </div>

@endif
@endsection

    @section('js')
        <script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
        <script src="{{ asset('lib/jquery-switchbutton/jquery.switchButton.js') }}"></script>
        <script src="{{ asset('lib/peity/jquery.peity.js') }}"></script>
        <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
        <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('lib/highlightjs/highlight.pack.js') }}"></script>
        <script>

            function save_form(element,url){
                var leverage_update=$(element).val()


              if(leverage_update >=0 &&leverage_update.length >0){
                  $.ajax({
                      type:"get",
                      url:url,
                      data:{id:leverage_update},
                      success:function (data){
                          $('.modal').removeClass("show");
                          location.reload();
                      }
                  })

              }
            }
             function close_model(){

                 $('.modal').removeClass("show");
             }
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
