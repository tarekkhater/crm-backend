@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('email-branding.website_url')])
{{ \App\Helpers\EmailBranding::companyName() }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ \App\Helpers\EmailBranding::companyName() }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
