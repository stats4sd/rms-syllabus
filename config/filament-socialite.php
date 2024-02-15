<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth callback middleware
    |--------------------------------------------------------------------------
    |
    | This option defines the middleware that is applied to the OAuth callback url.
    |
    */

    'middleware' => [
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ],

    'registration' => true,

    'providers' => [
         'google' => [
         	'icon' => 'fab-google',
         	'label' => 'Google',
         ],
        //  'linkedin' => [
        //     'icon' => 'fab-linkedin',
        //     'label' => 'LinkedIn',
        // ],
        // 'github' => [
        //     'icon' => 'fab-github',
        //     'label' => 'GitHub',
        // ],
        // 'facebook' => [
        //     'icon' => 'fab-facebook',
        //     'label' => 'Facebook',
        // ],
    ],

    'user_model' => \App\Models\User::class,

];
