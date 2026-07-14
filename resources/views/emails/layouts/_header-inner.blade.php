<tr>
    <td class="email-header">
        <a href="{{ config('email-branding.website_url') }}" style="text-decoration: none;">
            <img src="{{ \App\Helpers\EmailBranding::logoUrl() }}"
                 alt="{{ \App\Helpers\EmailBranding::companyName() }}"
                 width="{{ config('email-branding.logo_width', '180') }}"
                 style="max-width: {{ config('email-branding.logo_width', '180') }}px; height: auto;" />
        </a>
    </td>
</tr>
