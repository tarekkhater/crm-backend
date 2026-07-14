@props(['url'])
<tr>
<td class="header" style="background: linear-gradient(135deg, {{ config('email-branding.colors.primary_dark') }} 0%, {{ config('email-branding.colors.primary') }} 100%); padding: 28px 0; text-align: center;">
<a href="{{ $url ?? config('email-branding.website_url') }}" style="display: inline-block; text-decoration: none;">
<img src="{{ \App\Helpers\EmailBranding::logoUrl() }}" class="logo" alt="{{ \App\Helpers\EmailBranding::companyName() }}" style="height: auto; max-height: 70px; width: auto; max-width: {{ config('email-branding.logo_width', '180') }}px; margin: 0 auto;">
</a>
</td>
</tr>
