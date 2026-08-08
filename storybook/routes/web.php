<?php

use Illuminate\Support\Facades\Route;

// This app exists only to render stories for Storybook, which talks to the
// /storybook_preview routes registered by Blast itself. Anything else lands
// here.
Route::get('/', fn () => redirect('http://localhost:6006'));
