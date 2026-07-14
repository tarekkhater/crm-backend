@php
    $c = config('email-branding.colors');
@endphp
<style type="text/css">
    body, table, td, p, a, li { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
    img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; display: block; }
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        background-color: {{ $c['background'] }};
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: {{ $c['text'] }};
        line-height: 1.6;
    }
    .email-wrapper { width: 100%; background-color: {{ $c['background'] }}; padding: 24px 12px; }
    .email-container { max-width: 600px; margin: 0 auto; }
    .email-card {
        background-color: {{ $c['card'] }};
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 26, 46, 0.08);
    }
    .email-header {
        background-color: {{ $c['primary'] }};
        padding: 28px 32px;
        text-align: center;
    }
    .email-header img { margin: 0 auto; max-width: {{ config('email-branding.logo_width', '180') }}px; height: auto; }
    .email-header .company-name {
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin: 12px 0 0;
        opacity: 0.9;
    }
    .email-body { padding: 32px; }
    .email-footer {
        background-color: {{ $c['primary_dark'] }};
        padding: 24px 32px;
        text-align: center;
    }
    .email-footer p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 12px;
        line-height: 1.5;
        margin: 0 0 8px;
    }
    .email-footer a { color: {{ $c['accent'] }}; text-decoration: none; }
    .email-footer .copyright { color: rgba(255, 255, 255, 0.55); font-size: 11px; margin-top: 12px; }
    .btn-primary {
        display: inline-block;
        background-color: {{ $c['accent'] }};
        color: {{ $c['primary_dark'] }} !important;
        font-weight: 600;
        font-size: 14px;
        padding: 12px 28px;
        border-radius: 6px;
        text-decoration: none;
        margin-top: 16px;
    }
    .badge-title {
        background-color: {{ $c['highlight_bg'] }};
        border-left: 4px solid {{ $c['accent'] }};
        padding: 12px 16px;
        font-size: 16px;
        font-weight: 600;
        color: {{ $c['primary'] }};
        border-radius: 0 6px 6px 0;
        margin-bottom: 20px;
    }
    @media only screen and (max-width: 620px) {
        .email-body, .email-header, .email-footer { padding: 20px !important; }
        .email-header img { max-width: 140px !important; }
    }
</style>
