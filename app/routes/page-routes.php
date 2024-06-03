<?php

use Controller\HomeController;
use Controller\ServiceDeskController;
use Service\Redirect;
use Service\Route;
use Service\Session;

Route::get('/phpinfo', function () {
    phpinfo();
    die();
})->name('home');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::addRedirect('/home', '/'); // todo: make this redirection work

Route::get('/logout', function () {
    Session::instance()->clear();
    Redirect::to('/');
});

Route::group('/service-desk', function () {

    Route::get('/login', [ServiceDeskController::class, 'login'])->name('service.desk.login')->guest();
    Route::post('/login', [ServiceDeskController::class, 'authenticate'])->name('service.desk.authenticate')->guest();

    Route::get('/dashboard', [ServiceDeskController::class, 'dashboard'])->name('service.desk.dashboard')->auth(['service_desk']);

});
