@extends('admin.layouts.admin-app')

{{-- @section('css')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">
@endsection --}}

@section('content')
    <div class="br-mainpanel">
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">
            <a href="{{ route('admin.users.show', $user) }}">
                <button class="btn btn-secondary btn-sm" style="cursor: pointer;">&lt; Back</button> 
            </a>
            Add Deposit for {{ $user->name }}
        </h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">

        <div class="row mg-t-10">
            <div class="col-xl-10">

                @include('notification')

            <div class="form-layout form-layout-4">
                {{-- <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Edit {{ $user->name }}</h6> --}}

                <form action="{{ route('admin.users.deposits.store', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Plan:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <select class="form-control" @error('plan_id') is-invalid @enderror name="plan_id" required>
                                <option value="">- Select Plan -</option>
                                @foreach ($packages as $item)
                                    <option value="{{ $item->id }}" @if(old('plan_id') == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>

                            @error('plan_id')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div><!-- row -->

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Payment Method:</label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="text" placeholder="Payment Method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" value="{{ old('payment_method') }}" />

                            @error('payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Amount:<span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="number" placeholder="Amount" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>

                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Promo Code:</label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="text" placeholder="Promo Code" class="form-control @error('promo_code') is-invalid @enderror" name="promo_code" value="{{ old('promo_code') }}">

                            @error('promo_code')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Proof: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="proof" type="file" class="form-control @error('proof') is-invalid @enderror" name="proof" value="{{ old('proof') }}">

                        @error('proof')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Message</label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <textarea Placeholder="Message" name="message" rows="5" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>

                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- col-4 -->
                    <div class="form-layout-footer mg-t-30">
                    <button class="btn btn-info">Create Deposit</button>
                    
                    <input type="reset" class="btn btn-secondary" value="Cancel">
                    </div><!-- form-layout-footer -->
                </form>
            </div><!-- form-layout -->
            </div><!-- col-6 -->

        </div>
        </div>
</div>
@endsection

{{-- @section('js')
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection --}}
