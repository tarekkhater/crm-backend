<?php

return [

    'company_name' => env('MAIL_COMPANY_NAME', 'Paradox Investing'),

    'tagline' => env('MAIL_COMPANY_TAGLINE', 'Smart investing. Clear results.'),

    'logo_path' => env('MAIL_LOGO_PATH', 'images/logo.png'),

    'logo_width' => env('MAIL_LOGO_WIDTH', '180'),

    'website_url' => env('MAIL_WEBSITE_URL', env('APP_URL', 'https://trade.pdxterminal.app')),

    'support_email' => env('MAIL_SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS', 'support@paradoxinvesting.com')),

    'support_phone' => env('MAIL_SUPPORT_PHONE', ''),

    'colors' => [
        'primary'      => env('MAIL_COLOR_PRIMARY', '#1A1A1B'),
        'primary_dark' => env('MAIL_COLOR_PRIMARY_DARK', '#0F0F10'),
        'accent'       => env('MAIL_COLOR_ACCENT', '#22C55E'),
        'accent_hover' => env('MAIL_COLOR_ACCENT_HOVER', '#16A34A'),
        'background'   => env('MAIL_COLOR_BACKGROUND', '#F4F6F9'),
        'card'         => '#FFFFFF',
        'text'         => '#1E293B',
        'text_muted'   => '#64748B',
        'border'       => '#E2E8F0',
        'highlight_bg' => '#ECFDF5',
    ],

    'footer' => [
        'disclaimer' => 'You are receiving this email because you have an account with Paradox Investing or subscribed to our communications. If you no longer wish to receive these emails, please contact support.',
        'copyright'  => '© :year Paradox Investing. All rights reserved.',
    ],

];
