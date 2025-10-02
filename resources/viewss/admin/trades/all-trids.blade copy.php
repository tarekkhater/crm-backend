@extends('admin.layouts.admin-app')

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
   
    <div class="br-pagebody" id="content">

    @if (Request()->has('user'))
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Add New Trade
                </button>
            </p>

            @include('notification')
            <div class="collapse {{ request()->get('asset') ? 'show' : '' }}" id="collapseExample">
                <div class="br-section-wrapper">
                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Trade [increase or decrease opening rate to alter profit]</h6>

                    <form action="{{ route('admin.trades.store') }}" method="POST">
                        @csrf
                        <div class="form-layout form-layout-1">
                            <div class="row mg-b-25">

                                <input name="user_id" type="hidden" value="{{ $user->id }}">

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label">Select Asset Type: <span class="tx-danger">*</span></label>
                                        <select v-model="data.a_type" id="asset"  name="type" required class="form-control">
                                            @foreach( ['crypto','stocks','forex','indices','commodities'] as $item)
                                            <option  value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- col-8 -->
                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label">Select Asset : <span class="tx-danger">*</span></label>
                                        <select v-model="data.coin_id" id="asset" @change="updateStatus(data.coin_id)" name="coin_id" required class="form-control">
                                            @foreach(\App\Models\CurrencyPair::whereType($c_type)->where('rate','!=',0.0000)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- col-8 -->
                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize"> Opening Rate [@{{ coinPrice }}]: <span class="tx-danger">*</span></label>
                                        <input v-model="data.value" disabled class="form-control" min="1" step="any" :value="data.value" required type="number" name="value" placeholder="Asset Value">
                                        <input v-model="data.value" :value="data.value" required type="hidden" name="value">
                                    </div>
                                </div><!-- col-8 -->

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize">Traded Volume : <span class="tx-danger">*</span></label>
                                        <input v-model="data.volume" class="form-control" step="any" required type="number" name="amt" placeholder="Traded Volume">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize">Amount in USD : <span class="tx-danger">*</span></label>
                                        <input v-model="data.amount"  class="form-control" step="any" required type="number" name="amount" placeholder="Traded Amount">
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize">Leverage : <span class="tx-danger">*</span></label>
                                        <input v-model="data.leverage" disabled class="form-control" step="any" placeholder="leverage">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label">Trade Type: <span class="tx-danger">*</span></label>
                                        <select id="type" required class="form-control" name="type">
                                            <option value="buy">Buy</option>
                                            <option value="sell">Sell</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label">Take Profit: <span class="tx-danger">*</span></label>
                                        <select v-model="data.is_take_profit" required class="form-control" name="is_take_profit">
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label">Stop loss: <span class="tx-danger">*</span></label>
                                        <select v-model="data.is_stop_loss" id="trade_type" required class="form-control" name="is_stop_loss">
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="data.is_take_profit > 0">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize">Take Profit : <span class="tx-danger">*</span></label>
                                        <input v-model="data.take_profit" :value="data.take_profit" class="form-control" step="any" required type="number" name="take_profit" placeholder="Take Profit">
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="data.is_stop_loss > 0">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label text text-capitalize">Stop loss : <span class="tx-danger">*</span></label>
                                        <input :value="data.stop_loss" v-model="data.stop_loss" class="form-control" step="any" required type="number" name="stop_loss" placeholder="stop_loss">
                                    </div>
                                </div>






                            </div><!-- row -->

                            <div class="form-layout-footer">
                                <button class="btn btn-primary" type="submit">Submit Trade</button>
                            </div><!-- form-layout-footer -->
                        </div><!-- form-layout -->
                    </form>
                </div><!-- br-section-wrapper -->
            </div>

    @endif

        


    </div>


            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Open Trades</h6>




                <div class="table-wrapper">
                    <form action="{{ route('admin.users.trades.delete') }}" method="POST" id="deleteTradeForm">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger mb-2 delete-multiple-trades" style="display: none;">Delete Selected</button>
                    <table id="datatable1" class="table table-bordered table-condensed display responsive">
                            <thead>
                            <tr>
                                <th></th>
                                <th>User Name</th>
                                <th>Opened at</th>
                                <th>Currency / Asset</th>
                                <th>Amount</th>
                                <th>Opening Value</th>
                                <th>Current Rate</th>
                                <th>Qty</th>
                                <th>Direction</th>
                                <th>Status</th>
{{--                                <th>Result</th>--}}
                                <th>PNL</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                {{--                                            <th></th>--}}
                            </tr>
                            </thead>
                            @if (count($trades) < 1)
                                <tbody>
                                <tr>
                                    <td colspan="100%">No results found</td>
                                </tr>
                                </tbody>
                            @else
                                <tbody>
                                @foreach($trades as $item)
                                    <tr>
                                        <td><center><input type="checkbox" name="delete_trade_ids[]" value="{{ $item->id }}" onchange="toggleTradeToDelete(this)"></center></td>
                                        <td>
                                            @if($item->user) 
                                            {{$item->user->first_name}}
                                            @else 
                                            ' '
                                            @endif
                                            
                                            @if($item->user) 
                                            {{$item->user->last_name}}
                                            @else 
                                            ' '
                                            @endif
                                        </td>
                                        <td>{{ $item->open_at }}</td>
                                        <td>{{ optional($item->currency)->name }}</td>
                                        <td>{{ $item->traded_amount }} USD</td>
                                        <td>{{ $item->opening_price }} USD</td>
                                        <td>{{ $item->closing_price }} USD</td>
                                        <td>{{ $item->qty }} {{ optional($item->currency)->sym }}</td>

                                        <td>
                                            @if ($item->trade_type == 'buy')
                                                <span class="badge badge-success p-2">Buy</span>
                                            @else
                                                <span class="badge badge-warning p-2">Sell</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 0)
                                                <span class="badge badge-warning p-2">Running</span>
                                            @else
                                                <span class="badge badge-danger p-2">Closed</span>
                                            @endif
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
{{--                                            @if ($item->profit)--}}
                                                {{ amt($item->profit) }}
{{--                                            @else--}}
{{--                                                <span class="badge badge-danger p-2">Running</span>--}}
{{--                                            @endif--}}
                                        </td>
                                        <td>
                                            @if($item->status === 0)
                                                <a href="" data-toggle="modal" data-target="#editTrade{{ $item->id }}" class="btn btn-primary">Edit</a>
                                            @else
                                                <a href="" disabled="" data-toggle="modal" class="btn btn-warning">Closed</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form method="POST" action="{!! route('admin.trades.destroy', $item->id) !!}" accept-charset="UTF-8">
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
                                @endforeach
                                </tbody>
                            @endif


                    </table>
                    </form>
                </div><!-- table-wrapper -->

                @foreach($p_trade as $item)
                    <div id="editTrade{{$item->id}}" class="modal fade">
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
                                            <div class="row mg-b-25">

                                                <input name="id" type="hidden" value="{{ $item->id }}">

                                                <div class="col-md-12">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label"> Current Opening Rate : [{{ $item->opening_price }}]</label>
                                                        <input  value="{{ $item->opening_price }}" class="form-control" required type="number" step="any" name="opening_price" placeholder="opening_price">
                                                    </div>
                                                </div><!-- col-8 -->
                                                <div class="col-md-12">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label"> Current PNL: <span class="tx-danger">*</span></label>
                                                        <input disabled value="{{ $item->profit }}" class="form-control" required type="number" step="any" name="profit" placeholder="PNL">
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
                @endforeach

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