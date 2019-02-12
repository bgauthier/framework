<?php

use Laravel\Telescope\Http\Middleware\Authorize;

return [

    /*
    |--------------------------------------------------------------------------
    | Framework Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Framework route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => [
        \Framework\Modules\Core\Middleware\FrameworkMiddleware::class,
        'web',
        Authorize::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | FontAwesome pro enabled
    |--------------------------------------------------------------------------
    |    
    |
    */

    'fontAwesomeProEnabled' => FALSE,

    /*
    |--------------------------------------------------------------------------
    | FontAwesome enabled
    |--------------------------------------------------------------------------
    |
    |
    */

    'fontAwesomeEnabled' => TRUE,

    /*
    |--------------------------------------------------------------------------
    | Template path
    |--------------------------------------------------------------------------
    |
    |
    */

    'templatePath' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    |
    */

    'logo' => '/vendor/framework/images/logiksuite_full.png',


    /*
    |--------------------------------------------------------------------------
    | Default user menu
    |--------------------------------------------------------------------------
    |
    |
    */

    'userMenu' => [
        [
            'Label' => 'Logout',
            'Href' => '/logout',
            'Icon' => 'fas fa-user-lock',
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Component classes
    |--------------------------------------------------------------------------
    |   Allows you to specify what classes will be added to a component
    |
    */
    'componentClasses' => [
        'Table' => ['table-sm', 'table-bordered', 'table-striped']
    ],

    /*
    |--------------------------------------------------------------------------
    | Component styles
    |--------------------------------------------------------------------------
    |   Allows you to specify what styles attribute will be added to a component
    |
    */
    'componentStyles' => [

    ],

];