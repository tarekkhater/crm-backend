@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">
        <!-- START: Breadcrumbs-->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Update password</h4>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">

                                <div class="col-md-12">
                                    <div class="id_info">
                                        <form method="POST" action="{{ route('backend.update_pass') }}">
                                            @csrf

                                            @foreach ($errors->all() as $error)
                                                <p class="text-danger">{{ $error }}</p>
                                            @endforeach

                                            <div class="form-group row">
                                                <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                                                <div class="col-md-6">
                                                    <input id="password" type="password" required class="form-control" name="current_password" autocomplete="current-password">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                                                <div class="col-md-6">
                                                    <input id="new_password" type="password"  required class="form-control" name="new_password" autocomplete="current-password">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>

                                                <div class="col-md-6">
                                                    <input id="new_confirm_password" required type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                                                </div>
                                            </div>

                                            <div class="form-group row mb-0">
                                                <div class="col-md-8 offset-md-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        Update Password
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>


@endsection

@section('js')

@endsection
