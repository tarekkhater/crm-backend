@extends('admin.layouts.admin-app')

@section('css')

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">

@endsection

@section('content')
    <div class="br-mainpanel">
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Edit {{ $currency_pair->name }}</h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">

        <div class="row mg-t-10">
            <div class="col-xl-12">

                @include('notification')

            <div class="form-layout form-layout-4">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Edit {{ $currency_pair->name }}</h6>

                <form action="{{ route('admin.trading_hours.update', $currency_pair) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                    <label class="col-sm-4 form-control-label"> Trading Days: <span class="tx-danger">*</span></label>
                    <select  name="days[]" class="form-control @error('country') is-invalid @enderror select2" multiple autocomplete="days" require autofocus>

                       <option value="Saturday" @if(in_array("Saturday", $daysArray)) selected @endif>Saturday</option>
                       <option value="Sunday" @if(in_array("Sunday", $daysArray)) selected @endif>Sunday</option>
                       <option value="Monday" @if(in_array("Monday", $daysArray)) selected @endif>Monday</option>
                       <option value="Tuesday" @if(in_array("Tuesday", $daysArray)) selected @endif>Tuesday</option>
                       <option value="Wednesday" @if(in_array("Wednesday", $daysArray)) selected @endif>Wednesday</option>
                       <option value="Thursday" @if(in_array("Thursday", $daysArray)) selected @endif>Thursday</option>
                       <option value="Friday" @if(in_array("Friday", $daysArray)) selected @endif>Friday</option>

                    </select>
                </div><!-- row -->

                @if(is_array($startTime) && is_array($endTime))

                <div class="row mg-t-20 wrapper">
                    @foreach($startTime as $K=>$start)

                    <div class="col-md-6 new-wrapper">
                        <label class="col-sm-4 form-control-label"> Trading Open Time {{$K+1}}: <span class="tx-danger">*</span></label>

                            <input type="time" class="form-control" value="{{$start}}" name="open_at[]" />

                    </div>
                    @endforeach

                    @foreach($endTime as $i=>$end)
                    <div class="col-md-6 new-wrapper">
                        <label class="col-sm-4 form-control-label"> Trading Close Time {{$i+1}}: <span class="tx-danger">*</span></label>

                            <input type="time" class="form-control" value="{{$end}}"  name="close_at[]" />


                    </div>
                    @endforeach

                </div>
                @else
                <div class="row mg-t-20 wrapper">
                    <div class="col-md-4 new-wrapper">
                        <label class="col-sm-4 form-control-label"> Trading Open At: <span class="tx-danger">*</span></label>

                            <input type="time" class="form-control" value="{{$startTime}}"  name="open_at" />

                    </div>

                    <div class="col-md-4 new-wrapper">
                        <label class="col-sm-4 form-control-label"> Trading Close At: <span class="tx-danger">*</span></label>

                            <input type="time" class="form-control" value="{{$endTime}}" name="close_at" />


                    </div>

                </div>
                @endif

                    </div>



                    <!-- col-4 -->
                    <div class="form-layout-footer mg-t-30">
                    <button class="btn btn-info">Submit Form</button>


                    </div><!-- form-layout-footer -->
                </form>
            </div><!-- form-layout -->
            </div><!-- col-6 -->

        </div>
        </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js" integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>


//Add Input Fields
$(document).ready(function() {
    //var max_fields = 10; //Maximum allowed input fields
    var wrapper    = $(".wrapper"); //Input fields wrapper
    var new_wrapper = $(".new-wrapper");
    var add_button = $(".add_fields"); //Add button class or ID
    var x = 1; //Initial input field is set to 1

//- Using an anonymous function:

 //When user click on add input button
 $(add_button).click(function(e){
        e.preventDefault();
 //Check maximum allowed input fields
        //if(x < max_fields){
            x++; //input field increment
 //add input field
            //$(wrapper).append('<div><input type="time" class="form-control" name="open_at[]"  /> <a href="javascript:void(0);" class="remove_field">Remove</a></div>');
            //$(wrapper).append('<div><input type="time" class="form-control" name="close_at[]"  /> <a href="javascript:void(0);" class="remove_field">Remove</a></div>');
          //  $(wrapper).append('<div><label class="col-sm-4 form-control-label"> Trading Open At: <span class="tx-danger">*</span></label><input type="time" class="form-control"  name="open_at[]" /></div><div class="col-md-4 wrapper"><label class="col-sm-4 form-control-label"> Trading Close At: <span class="tx-danger">*</span></label><input type="time" class="form-control"  name="close_at[]" /></div><div class="col-md-4"><label class="col-sm-4 form-control-label mg-t-20"></label><a href="javascript:void(0);" class="remove_field btn btn-danger">Remove</a></div>');

            $(wrapper).append('<div class="col-md-4"><label class="col-sm-4 form-control-label"> Trading Open At: <span class="tx-danger">*</span></label><input type="time" class="form-control"  name="open_at[]" /></div><div class="col-md-4"><label class="col-sm-4 form-control-label"> Trading Close At: <span class="tx-danger">*</span></label><input type="time" class="form-control"  name="close_at[]" /></div><a href="javascript:void(0);" class="remove_field btn btn-danger">Remove</a>');
       // }
    });

    //when user click on remove button
    $(wrapper).on("click",".remove_field", function(e){
        e.preventDefault();
 $(this).parent('div').remove(); //remove inout field
 x--; //inout field decrement
    })
});
</script>


    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection
