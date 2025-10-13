@extends('admin.layouts.admin-app')
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
@section('style')
    <link href="{{ asset('lib/highlightjs/github.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">

    
    
@endsection

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel"  >
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-item active"> Trades Layouts</span>
        </nav>
    </div><!-- br-pageheader -->

    <div class="br-pagebody" id="content-trade">


  






    <div class="br-section-wrapper mb-2" v-if="trades.length > 0">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Open Trades</h6>

                <div class="table-wrapper" >
                <div class="d-flex justify-content-end mb-4">
            <a class="btn btn-primary" href="{{ route('admin.printPDF')}}">Export to PDF</a>
        </div>
                    <table id="datatable1" class="table table-bordered table-condensed display responsive">
                    <thead>
                        <tr>
                        <th>user Name</th>
                            <th>Opened at</th>
                            <th>Currency / Asset</th>
                            <th>Leverage</th>
                            <th>Amount</th>
                            <th>Opening Value</th>
                            <th>Current Value</th>
                            <th>Qty</th>
                            <th>Direction</th>
                            <th>Status</th>
                            {{--                                <th>Result</th>--}}
                            <th>PNL</th>
                            <th>Edit</th>
                            <th>Close</th>
                            <th>Delete</th>
                            {{--                                            <th></th>--}}
                        </tr>
                        </thead>

                            <tbody>
                            <tr v-for="item in trades">
                            <td v-if="item.user">
                                <a :href="'/admin/users/'+item.user.id">@{{ item.user.first_name }} @{{ item.user.last_name }}</a>
                            </td>
                            <td v-else>'Not Found'</td>

                                <td>@{{ item.open_at }}</td>
                                <td>@{{ item.currency ? item.currency.name : '' }}</td>
                                <td>@{{ item.leverage }} </td>
                                <td>@{{ item.traded_amount }} USD</td>
                                <td>@{{ item.opening_price }} USD</td>
                                <td>@{{ item.closing_price }} USD</td>
                                <td>@{{ item.qty }} @{{ item.currency ? item.currency.sym : '' }}</td>


                                <td>
                                    <span v-if="item.trade_type === 'buy'" class="badge badge-success p-2">Buy</span>
                                    <span v-else  class="badge badge-warning p-2">Sell</span>
                                </td>
                                <td>
                                    <span v-if="item.status < 1" class="badge badge-warning p-2">Running</span>
                                    <span v-else class="badge badge-danger p-2">Closed</span>
                                </td>

                                {{--                                        @if ($item->status == 1)--}}
                                {{--                                            <td>--}}
                                {{--                                                @if ($item->result === 2)--}}
                                {{--                                                    <span class="badge badge-danger p-2">loss</span>--}}
                                {{--                                                @elseif ($item->result === 1)--}}
                                {{--                                                    <span class="badge badge-success p-2">Won</span>--}}
                                {{--                                                @elseif ($item->result === 3)--}}
                                {{--                                                    <span class="badge badge-warning p-2">Draw</span>--}}
                                {{--                                                @endif--}}
                                {{--                                            </td>--}}
                                {{--                                        @else--}}
                                {{--                                            <td>--}}
                                {{--                                                <a  href="{{ route('trade.manual_close', $item->id) }}" class="btn btn-danger">Close Trade</a>--}}
                                {{--                                            </td>--}}
                                {{--                                        @endif--}}
                                <td>
                                    @{{ item.profit }}
                                </td>
                                <td>

                                    <button v-if="item.status == 0"  @click="editTrade(item)" class="btn btn-primary">Edit</button>
{{--                                    <button v-if="item.status == 0"  data-toggle="modal" :data-target="'#editTrade'+item.id" class="btn btn-primary">Edit</button>--}}
                                    <a v-else href="" disabled="" data-toggle="modal" class="btn btn-warning">Closed</a>

                                </td>
                                <td>
                                    <a  :href="'/close/trade/'+item.id+'/trades'" class="btn btn-danger">Close</a>
                                </td>
                                <td class="text-center">
                                    {{--                                            {!! route('admin.trades.destroy', $item->id) !!}--}}

                                    <form method="POST" :action="'/admin'" accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        <div class="btn-group justify-center" role="group">
                                            <button type="submit" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                <span class="fa fa-trash" aria-hidden="true"></span>
                                            </button>
                                        </div>

                                    </form>

                                </td>
                            </tr>
                            </tbody>


                    </table>

                </div><!-- table-wrapper -->


    </div>

               

                <div id="editTrade" ref="modal" class="modal fade">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content tx-size-sm">
                            <div class="modal-header pd-x-20">
                                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"> Modify trade opening rate to alter profit</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.user.updateTrade') }}" method="POST">

                                <div class="modal-body pd-20">

                                    @csrf
                                    <div class="form-layout form-layout-1">
                                        <div class="row mg-b-25" v-if="form">

                                            <input name="id" type="hidden" :value="eId">

                                            <div class="col-md-12">
                                                <div class="form-group mg-b-10-force">
                                                    <label class="form-control-label"> Current Opening Rate : [@{{ form.opening_price }}]</label>
                                                    <input v-model="eOP"  :value="eOP" class="form-control" required type="number" step="any" name="opening_price" placeholder="opening_price">
                                                </div>
                                            </div><!-- col-8 -->
                                            <div class="col-md-12">
                                                <div class="form-group mg-b-10-force">
                                                    <label class="form-control-label"> Current PNL: <span class="tx-danger">*</span></label>
                                                    <input disabled v-model="form.profit" :value="form.profit" class="form-control" required type="number" step="any" name="profit" placeholder="PNL">
                                                    <em v-if="calculating">Calculating pnl .............</em>

                                                </div>
                                            </div><!-- col-8 -->
                                        </div>
                                    </div>

                                </div><!-- modal-body -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary tx-size-xs">Save</button>
                                    <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


            </div>



</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->


@endsection




