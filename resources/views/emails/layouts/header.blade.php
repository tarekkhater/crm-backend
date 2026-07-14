<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ \App\Helpers\EmailBranding::companyName() }}</title>
    @include('emails.layouts._styles')
</head>
<body>
<div class="email-wrapper">
    <table class="email-container" width="100%" cellpadding="0" cellspacing="0" role="presentation" align="center">
        <tr>
            <td>
                <table class="email-card" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    @include('emails.layouts._header-inner')
                    <tr>
                        <td class="email-body">
