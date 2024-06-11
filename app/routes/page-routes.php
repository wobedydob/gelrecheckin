<?php

use Controller\DashboardController;
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
Route::addRedirect('/home', '/');

Route::get('/logout', function () {
    Session::instance()->clear();
    Redirect::to('/');
});

Route::get('/inloggen/passagier', [PassengerController::class, 'login'])->name('login')->guest();
Route::post('/inloggen/passagier', [PassengerController::class, 'authenticate'])->name('login.authenticate')->guest();

Route::get('/inloggen/medewerker', [ServiceDeskController::class, 'login'])->name('service.desk.login')->guest();
Route::post('/inloggen/medewerker', [ServiceDeskController::class, 'authenticate'])->name('service.desk.authenticate')->guest();

Route::get('/dashboard', [DashboardController::class, 'handle'])->name('dashboard')->auth([Passenger::USER_ROLE, ServiceDesk::USER_ROLE]);
Route::get('/vluchten', [FlightsController::class, 'handle'])->name('flights')->auth([Passenger::USER_ROLE, ServiceDesk::USER_ROLE]);

Route::get('/vluchten/toevoegen', [FlightsController::class, 'add'])->name('flights.add')->auth([ServiceDesk::USER_ROLE]);
Route::post('/vluchten/toevoegen', [FlightsController::class, 'addFlight'])->name('flights.add.post')->auth([ServiceDesk::USER_ROLE]);
Route::get('/vluchten/{id}', [FlightsController::class, 'show'])->name('flights.show')->auth([ServiceDesk::USER_ROLE]);
Route::get('/vluchten/{id}/bewerken', [FlightsController::class, 'edit'])->name('flights.edit')->auth([ServiceDesk::USER_ROLE]);
Route::post('/vluchten/{id}/bewerken', [FlightsController::class, 'editFlight'])->name('flights.edit.post')->auth([ServiceDesk::USER_ROLE]);
Route::get('/vluchten/{id}/verwijderen', [FlightsController::class, 'delete'])->name('flights.delete')->auth([ServiceDesk::USER_ROLE]);

Route::get('/passagiers', [PassengerController::class, 'serviceDesk'])->name('service.desk.passengers')->auth([ServiceDesk::USER_ROLE]);
Route::get('/passagiers/toevoegen', [PassengerController::class, 'add'])->name('flights.add')->auth([ServiceDesk::USER_ROLE]);
Route::post('/passagiers/toevoegen', [PassengerController::class, 'addPassenger'])->name('flights.add.flight')->auth([ServiceDesk::USER_ROLE]);
Route::get('/passagiers/{id}', [PassengerController::class, 'show'])->name('service.desk.passengers')->auth([ServiceDesk::USER_ROLE]);
Route::get('/passagiers/{id}/bewerken', [PassengerController::class, 'edit'])->name('passenger.edit')->auth([ServiceDesk::USER_ROLE]);
Route::post('/passagiers/{id}/bewerken', [PassengerController::class, 'editPassenger'])->name('passenger.edit.post')->auth([ServiceDesk::USER_ROLE]);
Route::get('/passagiers/{id}/verwijderen', [PassengerController::class, 'delete'])->name('passenger.delete')->auth([ServiceDesk::USER_ROLE]);