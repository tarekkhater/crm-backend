<?php

namespace App\Http;


use App\Http\Middleware\RolePermission;
use App\Http\Middleware\UserActivity;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // \App\Http\Middleware\OwnCors::class,
        \App\Http\Middleware\ForceJsonResponse::class,
        //  \App\Http\Middleware\DynamicCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CheckSystemStatus::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \App\Http\Middleware\CheckSystemStatus::class,
            //   \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'RoleMiddleware' => RolePermission::class,
        'UserActivity' => UserActivity::class,
        'cors' => \App\Http\Middleware\OwnCors::class,
        'UserVerified' => \App\Http\Middleware\User\UserVerified::class,
        'blockedUser' => \App\Http\Middleware\User\BlockedUser::class,
        'moneyuser' => \App\Http\Middleware\User\MoneyUser::class,
        'balanceuser' => \App\Http\Middleware\User\BalanceUser::class,
        'AdminVerified' => \App\Http\Middleware\Admin\AdminVerified::class,
        'blockedAdmin' => \App\Http\Middleware\Admin\BlockedAdmin::class,
        'checktotalamounttrading' => \App\Http\Middleware\User\CheckTotalAmountTrading::class,
        'role' => \Laratrust\Middleware\LaratrustRole::class,
        'permission' => \Laratrust\Middleware\LaratrustPermission::class,
        'ability' => \Laratrust\Middleware\LaratrustAbility::class,
        'check.system.status' => \App\Http\Middleware\CheckSystemStatus::class,
        'CheckOtpEmailForget' => \App\Http\Middleware\User\CheckOtpEmail::class,
        'CheckOtpEmailVerified' => \App\Http\Middleware\User\CheckOtpEmailVerified::class,
        'CheckOtpEmailForgetAdmin' => \App\Http\Middleware\Admin\CheckOtpEmail::class,
        'CheckOtpEmailVerifiedAdmin' => \App\Http\Middleware\Admin\CheckOtpEmailVerified::class,
        'checkRoleAndPermission' => \App\Http\Middleware\CheckRoleAndPermission::class,
        'CheckKyc'=>\App\Http\Middleware\User\CheckKyc::class,
        'CheckTrade'=>\App\Http\Middleware\User\CheckTrade::class,
'CheckWithdrawal'=>\App\Http\Middleware\User\CheckWithdrawal::class,
'CheckLead'=>\App\Http\Middleware\User\CheckLead::class,
'check.time' => \App\Http\Middleware\User\CheckTimeForTransaction::class,
'checkUserToken' => \App\Http\Middleware\CheckUserToken::class,
    ];
}