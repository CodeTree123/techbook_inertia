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



// inventory routes
Route::middleware('admin')->group(function () {
    Route::controller('InventoryController')->prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/techyeah/index', 'index')->name('techyeah.index');
        Route::any('/techyeah/customer/index', 'customerIndex')->name('customer.index');
        Route::get('/techyeah/customer', 'customerDetails')->name('customer');
        Route::get('/add/item', 'create')->name('add.item');
        Route::post('/item/store', 'store')->name('item.store');
        Route::get('/item/edit/{id}', 'edit')->name('item.edit');
        Route::post('/item/update/{id}', 'update')->name('item.update');
        Route::get('/item/destroy/{id}', 'destroy')->name('item.destroy');
        Route::get('/customer/autocomplete', 'customerAutocomplete')->name('customer.autocomplete');
        Route::get('/item/details', 'itemDetails')->name('item.details');
    });
});
