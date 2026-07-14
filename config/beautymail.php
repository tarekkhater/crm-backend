<?php

return [

    'colors' => [
        'highlight' => env('MAIL_COLOR_PRIMARY', '#1A1A1B'),
        'button'    => env('MAIL_COLOR_ACCENT', '#22C55E'),
    ],

    'view' => [
        'senderName'  => env('MAIL_COMPANY_NAME', 'Paradox Investing'),
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo' => [
            'path'   => '%PUBLIC%/images/logo.png',
            'width'  => '180px',
            'height' => 'auto',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
