<?php

use Illuminate\Support\Facades\Route;

// This app exists only to render stories for Storybook, which talks to the
// /storybook_preview routes registered by Blast itself. Anything else lands
// here.
//
// Deployed, public/storybook-static holds the UI built by build.sh and this is
// the entry point to it. Locally that directory is absent and start.sh serves
// the dev server on :6006 instead, so fall back to it.
//
// The redirect names index.html rather than the directory so it does not
// depend on the web server resolving a directory index inside public/.
Route::get('/', function () {
    return file_exists(public_path('storybook-static/index.html'))
        ? redirect('/storybook-static/index.html')
        : redirect('http://localhost:6006');
});
