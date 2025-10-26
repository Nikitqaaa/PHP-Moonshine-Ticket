<?php

use App\Models\User;
use MoonShine\Laravel\Forms\LoginForm;
use MoonShine\Laravel\Pages\ErrorPage;
use MoonShine\Laravel\Pages\LoginPage;
use App\Http\Middleware\ImpersonateUser;
use MoonShine\Laravel\Forms\FiltersForm;
use MoonShine\Laravel\Pages\ProfilePage;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use MoonShine\Laravel\Http\Middleware\Authenticate;
use MoonShine\Laravel\Http\Middleware\ChangeLocale;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use MoonShine\Laravel\Exceptions\MoonShineNotFoundException;

return [
    'title'      => env('MOONSHINE_TITLE', 'PROVIZOR TICKET'),
    'logo'       => env('MOONSHINE_LOGO', '/img/logo.svg'),
    'logo_small' => env('MOONSHINE_LOGO_SMALL', '/img/provizor.svg'),

    // Default flags
    'use_migrations'             => true,
    'use_notifications'          => true,
    'use_database_notifications' => true,

    // Routing
    'domain'          => env('MOONSHINE_DOMAIN'),
    'prefix'          => env('MOONSHINE_ROUTE_PREFIX', 'admin'),
    'page_prefix'     => env('MOONSHINE_PAGE_PREFIX', 'page'),
    'resource_prefix' => env('MOONSHINE_RESOURCE_PREFIX', 'resource'),
    'home_route'      => 'moonshine.index',

    // Error handling
    'not_found_exception' => MoonShineNotFoundException::class,

    // Middleware
    'middleware' => [
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        ChangeLocale::class,
        ImpersonateUser::class,
    ],

    // Storage
    'disk'         => env('FILESYSTEM_DISK', 'public'),
    'disk_options' => [],
    'cache'        => 'redis',

    // Authentication and profile
    'auth' => [
        'enabled'    => true,
        'guard'      => 'moonshine',
        'model'      => User::class,
        'middleware' => Authenticate::class,
        'pipelines'  => [],
    ],

    // Authentication and profile
    'user_fields' => [
        'username' => 'email',
        'password' => 'password',
        'name'     => 'name',
        'avatar'   => 'avatar',
    ],

    // Layout, pages, forms
    'layout' => App\MoonShine\Layouts\MoonShineLayout::class,

    'forms' => [
        'login'   => LoginForm::class,
        'filters' => FiltersForm::class,
    ],

    'pages' => [
        'dashboard' => App\MoonShine\Pages\Dashboard::class,
        'profile'   => ProfilePage::class,
        'login'     => LoginPage::class,
        'error'     => ErrorPage::class,
    ],

    // Localizations
    'locale'  => 'ru',
    'locales' => [
        //        'ru',
        //        'en'
    ],
];
