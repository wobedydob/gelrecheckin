<?php

use Service\Redirect;
use Service\Route;
use Service\Session;

//Route::addRoute('/', 'views/templates/home.php', 'Home');
//Route::addRoute('/404', 'views/templates/404.php', '404');
//Route::addRoute('/info', 'info.php', 'info');
//
//Route::addRoute('/service-desk', 'views/templates/login/service-desk.php', 'Service Desk Login');


Route::get('/', function (){}, 'views/templates/home.php')->name('home');
Route::addRedirect('/home', '/'); // todo: make this work...

//Route::get('/info', function (){}, 'info.php')->name('info'); // todo: remove
//

// logout route
Route::get('/logout', function () {
    Session::new()->clear();
    Redirect::to('/');
});

Route::get('/service-desk', [\Controller\ServiceDeskController::class, 'login'])->name('service-desk');
Route::post('/service-desk', [\Controller\ServiceDeskController::class, 'authenticate'])->name('service-desk-authenticate');

Route::group('/service-desk', function () {
    Route::get('/dashboard', [\Controller\ServiceDeskController::class, 'dashboard'])->name('admin.dashboard');
});


//Route::group('/admin', function () {
//    Route::get('/dashboard', function () {
//        echo 'Admin Dashboard';
//    })->name('admin.dashboard');
//
//    Route::post('/settings', function () {
//        echo 'Admin Settings';
//    })->name('admin.settings');
//});
//
//Route::group('/user', function () {
//    Route::get('/profile', function () {
//        echo 'User Profile';
//    })->name('user.profile');
//});
//
//Route::auth(function () {
//    return isset($_SESSION['user']) && $_SESSION['role'] === 'user';
//});