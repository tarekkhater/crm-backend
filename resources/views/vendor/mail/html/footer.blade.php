<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center" style="background: {{ config('email-branding.colors.primary_dark') }}; color: rgba(255,255,255,0.85); padding: 24px 32px; border-radius: 0 0 4px 4px;">
<p style="color: rgba(255,255,255,0.75); font-size: 12px; margin: 0 0 8px;">{{ config('email-branding.footer.disclaimer') }}</p>
{{ Illuminate\Mail\Markdown::parse($slot) }}
<p style="color: rgba(255,255,255,0.55); font-size: 11px; margin-top: 12px;">{{ \App\Helpers\EmailBranding::copyright() }}</p>
</td>
</tr>
</table>
</td>
</tr>
