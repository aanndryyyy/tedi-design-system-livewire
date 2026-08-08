<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asset path
    |--------------------------------------------------------------------------
    |
    | Public path (relative to the document root) where the TEDI stylesheet,
    | script bundle and fonts are published by:
    |
    |     php artisan vendor:publish --tag=tedi-assets
    |
    */
    'asset_path' => 'vendor/tedi',

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | The theme class applied to the document root. TEDI core ships
    | "tedi-theme--default" (light) and "tedi-theme--dark".
    |
    */
    'theme' => 'tedi-theme--default',
];
