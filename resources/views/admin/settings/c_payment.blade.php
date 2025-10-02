@extends('admin.layouts.admin-app')

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('admin.settings.index') }}"> Settings</a>
{{--            <span class="breadcrumb-item active">Settings Layouts</span>--}}
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Custom Payment Link</h4>
    </div>

    <div class="br-pagebody">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.store') }}" method="POST">

                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Settings List</h6>

                <input value="Save" type="submit" class="btn btn-success float-right" />

                <div class="table-wrapper">
                    <table class="table table-bordered display table-condensed responsive nowrap">
                        <thead>
                        <tr>

                            <th class="wd-20p"> Name</th>
                            <th class="wd-80p">Value</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-capitalize">Title / Name</td>
                            <td>
                                <input class="form-control" width="70%" name="payment_name" required value="{{ setting('payment_name') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Image </td>
                            <td>
                                @include('admin.partials.image-upload',['field' => 'payment_image','image' => setting('payment_image'),'id' => 'logo'])

{{--                                <textarea class="form-control" name="first_banner_text">{{ setting('first_banner_text') }}</textarea>--}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Text / Info <br/> (Allows use of html tags)</td>
                            <td>
                                <textarea rows="7" class="form-control" name="payment_info">{{ setting('payment_info') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Button Name/Title</td>
                            <td>
                                <input class="form-control" width="70%" name="payment_button" required value="{{ setting('payment_button') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Payment Link / URL</td>
                            <td>
                                <input class="form-control" width="70%" name="payment_link" required value="{{ setting('payment_link') }}">
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

                {{-- <input value="Save" type="submit" class="btn btn-success float-right" /> --}}

                </form>

            </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

@endsection


