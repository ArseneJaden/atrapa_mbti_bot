<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookTelegramController;

Route::post('/telegram/webhook', [WebhookTelegramController::class, 'handle']);

Route::get('/', function () {
    return view('welcome');
});


