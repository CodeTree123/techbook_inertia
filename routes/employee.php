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



// employee routes
Route::middleware('admin')->group(function () {
    Route::namespace('employee')->group(function () {
        Route::controller('EmployeeController')->prefix('employee')->name('employee.')->group(function () {
            Route::get('/work/order/{id}', 'workOrder')->name('workOrder');
            Route::get('/work/order/view/{id}', 'workOrderView')->name('workOrder.view');
            Route::get('/index', 'employee')->name('index');
            Route::get('/edit/{id}', 'employeeEdit')->name('edit');
            Route::post('/add', 'addEmployee')->name('add');
            Route::post('/update/{id}', 'update')->name('update');
        });
    });
});
Route::namespace('employee')->group(function () {
    Route::controller('EmployeeController')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/search', 'getEmployeeSearch')->name('search');;
    });
});
