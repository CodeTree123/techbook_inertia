<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Assign technicians routes
Route::middleware('admin')->group(function () {
    Route::controller('DispatchTeamController')->prefix('dispatch')->name('dispatch.')->group(function () {
        Route::get('/get/distance/page/{id}', 'index')->name('get.distance.page');
        Route::post('assign/ftech/', 'assignTech')->name('assign.ftech');
        //begin dispatched ticket
        Route::get('dispatched/ticket/', 'dispatchTicket')->name('dTicket');
    });
});
