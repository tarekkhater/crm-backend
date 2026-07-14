<?php

namespace App\Helpers;

class EmailBranding
{
    public static function logoUrl(): string
    {
        $path = config('email-branding.logo_path', 'images/logo.png');

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    public static function companyName(): string
    {
        return config('email-branding.company_name', 'Paradox Investing');
    }

    public static function color(string $key): string
    {
        return config("email-branding.colors.{$key}", '#1B2A4A');
    }

    public static function copyright(): string
    {
        return str_replace(':year', date('Y'), config('email-branding.footer.copyright'));
    }
}
