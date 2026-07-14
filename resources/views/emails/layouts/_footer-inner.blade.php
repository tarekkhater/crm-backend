<tr>
    <td class="email-footer">
        <p>{{ config('email-branding.footer.disclaimer') }}</p>
        <p>
            @if(config('email-branding.support_email'))
                <a href="mailto:{{ config('email-branding.support_email') }}">{{ config('email-branding.support_email') }}</a>
            @endif
            @if(config('email-branding.support_phone'))
                &nbsp;|&nbsp; {{ config('email-branding.support_phone') }}
            @endif
        </p>
        <p>
            <a href="{{ config('email-branding.website_url') }}">{{ parse_url(config('email-branding.website_url'), PHP_URL_HOST) ?: config('email-branding.website_url') }}</a>
        </p>
        <p class="copyright">{{ \App\Helpers\EmailBranding::copyright() }}</p>
    </td>
</tr>
