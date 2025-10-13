@extends('admin.layouts.admin-app')

@section('css')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">
@endsection

@section('content')
    <div class="br-mainpanel">
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">
            <a href="{{ url()->previous() }}">
                <button class="btn btn-secondary btn-sm" style="cursor: pointer;">&lt; Back</button>
            </a>
            Edit {{ $transaction->user->name }} Transaction
        </h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">

        <div class="row mg-t-10">
            <div class="col-xl-10">

                @include('notification')

            <div class="form-layout form-layout-4">
                {{-- <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Edit {{ $user->name }}</h6> --}}

                <form action="{{ route('admin.users.transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                    <label class="col-sm-4 form-control-label">Created At: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input type="datetime-local" placeholder="YYYY-MM-DD HH:MM:SS" class="form-control @error('created_at') is-invalid @enderror" name="created_at" value="{{ date('Y-m-d\TH:i:s', strtotime($transaction->created_at)) }}" required>

                        @error('created_at')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div><!-- row -->
                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Amount<span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input type="number" step="0.01" placeholder="Amount" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ $transaction->amount }}" required>

                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div><!-- row -->

                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Fund Type: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input type="text" placeholder="Fund Type" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ $transaction->type }}" required>

                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>
                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Account Type: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="account_type" type="text" placeholder="Account Type" class="form-control @error('account_type') is-invalid @enderror" name="account_type" value="{{ $transaction->account_type }}" required>

                        @error('account_type')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>
                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Source: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="source" type="text" placeholder="source" class="form-control @error('source') is-invalid @enderror" name="source" value="{{ $transaction->source }}" required>

                        @error('source')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Note</label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <textarea name="note" rows="5" class="form-control @error('note') is-invalid @enderror">{{ $transaction->note }}</textarea>

                            @error('note')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- col-4 -->
                    <div class="form-layout-footer mg-t-30">
                    <button class="btn btn-info">Update Transaction</button>

                    <input type="reset" class="btn btn-secondary" value="Cancel">
                    </div><!-- form-layout-footer -->
                </form>
            </div><!-- form-layout -->
            </div><!-- col-6 -->

        </div>
        </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection
