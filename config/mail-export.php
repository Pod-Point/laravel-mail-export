<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main switch
    |--------------------------------------------------------------------------
    |
    | This is where you can completely enable or disable the package export
    | functionality. If disabled, this will only prepare the export when
    | it will not actually store it anywhere. Does what's on the tin.
    |
    */

    'enabled' => env('MAIL_EXPORT', true),

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the package in order to export a copy of your mail sent out by
    | the framework. Make sure your filesystem disks are configured!
    |
    | You can set this to `null` and we will automatically use the default
    | disk configured for your application.
    |
    */

    'disk' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Path
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem path that should be used
    | by the package when exporting a mail. This can also be changed at
    | a Mailable or Message level. Make sure to check our README.md.
    |
    */

    'path' => 'email-exports',

];
