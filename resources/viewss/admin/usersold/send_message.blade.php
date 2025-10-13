@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection



@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="breadcrumb-item" href="#">Send message</a>
{{--            <span class="breadcrumb-item active">CMS Edit</span>--}}
        </nav>
    </div><!-- br-pageheader -->



    <div class="br-pagebody">
        @include('notification')

    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="br-section-wrapper">

                <form action="{{ route('admin.user.send-message') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="form-layout form-layout-1">
                        <div class="row">
                            <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Subject : <span class="tx-danger">*</span></label>
                                <input class="form-control" name="subject" required>

                            </div>
                            </div>
                                <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Content : <span class="tx-danger">*</span></label>
                                <textarea required id="my-editor" name="message" class="form-control">{!! old('message') !!}</textarea>

                            </div>
                            </div>
                        </div>

                        <div class="form-layout-footer">
                            <button class="btn btn-primary" type="submit">Send message</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </form>
            </div><!-- br-section-wrapper -->
    </div>


@endsection

    @section('js')

        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script>
            var options = {
                filebrowserImageBrowseUrl: '/filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };

            CKEDITOR.replace('my-editor', options);
        </script>
@endsection
