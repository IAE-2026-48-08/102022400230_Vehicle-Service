<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/documentation#/Vehicles');
});

Route::get('/graphql-playground', function () {
    return view('graphql-playground');
});
