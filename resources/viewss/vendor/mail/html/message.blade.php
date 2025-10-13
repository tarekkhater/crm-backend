@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot
<div style="text-align: center ;padding: 10px;background: #161a39;margin-bottom: 25px">
    <img width="75px" height="75px" src="{{asset('assets/images/reset.png')}}" alt="{{ config('app.name') }}">
    <br>
    <h3 style="text-align: center;color: #fff" >Please reset your password</h3>

</div>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{--{{ $subcopy }}--}}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
    <div style="background: #161a39;color: #fff">
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
    </div>
@endcomponent
@endslot
@endcomponent
