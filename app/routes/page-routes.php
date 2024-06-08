<?php

use Controller\FlightsController;
use Controller\HomeController;
use Controller\PassengerController;
use Controller\ServiceDeskController;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Redirect;
use Service\Route;
use Service\Session;

Route::get('/phpinfo', function () {
    phpinfo();
    die();
})->name('home');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::addRedirect('/home', '/'); // todo: make this redirection work

Route::get('/login', [PassengerController::class, 'login'])->name('login')->guest();
Route::post('/login', [PassengerController::class, 'authenticate'])->name('login.authenticate')->guest();

Route::get('/dashboard', [PassengerController::class, 'dashboard'])->name('dashboard')->auth([Passenger::USER_ROLE]);

Route::get('/logout', function () {
    Session::instance()->clear();
    Redirect::to('/');
});

Route::group('/service-desk', function () {

    Route::get('/login', [ServiceDeskController::class, 'login'])->name('service.desk.login')->guest();
    Route::post('/login', [ServiceDeskController::class, 'authenticate'])->name('service.desk.authenticate')->guest();

    Route::get('/dashboard', [ServiceDeskController::class, 'dashboard'])->name('service.desk.dashboard')->auth([ServiceDesk::USER_ROLE]);

});

Route::get('/vluchten', [FlightsController::class, 'serviceDesk'])->name('flights.service.desk')->auth([ServiceDesk::USER_ROLE]);
Route::get('/vluchten/add', [FlightsController::class, 'add'])->name('flights.add')->auth([ServiceDesk::USER_ROLE]);
Route::post('/vluchten/add', [FlightsController::class, 'addFlight'])->name('flights.add.flight')->auth([ServiceDesk::USER_ROLE]);

Route::get('/vluchten/{id}', [FlightsController::class, 'show'])->name('flights.show')->auth([ServiceDesk::USER_ROLE]);

Route::get('/mijn-vluchten', [FlightsController::class, 'passenger'])->name('flights.passenger')->auth([Passenger::USER_ROLE]);

