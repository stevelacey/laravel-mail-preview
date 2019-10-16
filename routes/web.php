<?php

use Illuminate\Support\Facades\Route;

Route::get('/mailpreview', 'MailPreviewController@index')
    ->name('mailpreview');

Route::get('/mailpreview/{path}.eml', 'MailPreviewController@download')
    ->name('mailpreview_eml');

Route::get('/mailpreview/{path}.html', 'MailPreviewController@html')
    ->name('mailpreview_html');

Route::get('/mailpreview/view-source/{path}.html', 'MailPreviewController@source')
    ->name('mailpreview_source');

Route::get('/mailpreview/{path}.txt', 'MailPreviewController@text')
    ->name('mailpreview_text');

Route::get('/mailpreview/{path}', 'MailPreviewController@show')
    ->name('mailpreview.show');
