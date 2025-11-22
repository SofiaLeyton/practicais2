<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', function () {
    $url = env('PRODUCTS_SERVICE_URL') . '/productos';
    $response = Http::get($url);
    return $response->json();
});
