<?php

use Controller\HomeController;
use Controller\ServiceDeskController;
use Service\Redirect;
use Service\Route;
use Service\Session;

Route::get('/phpinfo', function () { phpinfo(); die(); })->name('home');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::addRedirect('/home', '/'); // todo: make this work

Route::get('/logout', function () {
    Session::instance()->clear();
    Redirect::to('/');
});

Route::get('/service-desk', [ServiceDeskController::class, 'login'])->name('service-desk');
Route::post('/service-desk', [ServiceDeskController::class, 'authenticate'])->name('service-desk-authenticate');

//Route::get('/service-desk/dashboard', [ServiceDeskController::class, 'dashboard'])->name('service-desk-dashboard')->middleware('auth');

Route::get('/service-desk/dashboard', [ServiceDeskController::class, 'dashboard'])
     ->name('service-desk-dashboard')
     ->auth(['service_desk']);