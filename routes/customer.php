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
// Customers routes
Route::middleware('admin')->group(function () {
    Route::namespace('customers')->group(function () {
        Route::controller('CustomerController')->prefix('customer')->name('customer.')->group(function () {
            Route::get('/index', 'index')->name('index');
            Route::get('/index/with/order', 'customerWithOrder')->name('index.with.order');
            Route::get('/registration', 'create')->name('create');
            Route::post('/store/customer', 'storeCustomer')->name('store');
            Route::get('/get/customer', 'getCustomers')->name('get');
            //begin customer site
            Route::post('/store/site', 'sites')->name('site.store');
            Route::get('/list/site', 'siteList')->name('site.list');
            //end customer site
            //begin site edit
            Route::get('/edit/site/{id}', 'editSite')->name('site.edit');
            Route::post('/delete/site/{id}', 'deleteSite')->name('site.delete');
            Route::PUT('/site/edit/post/{id}', 'editSitePost')->name('editSitePost');
            Route::post('/customer-site/get', 'getCustomerSite')->name('getSite');
            //end site edit
            //begin customer edit
            Route::get('/edit/customer/{id}', 'cusEdit')->name('edit');
            Route::get('/delete/customer/{id}', 'cusDelete')->name('delete');
            Route::PUT('/edit/post/{id}', 'cusEditPost')->name('cusEditPost');
            //end customer edit
            //fetching customer company name
            Route::get('/getUserName/{id}', 'getComName')->name('comName');
            //end
            //vendor care
            Route::get('/vendor/care', 'vendorCare')->name('vendorCare');
            Route::post('/vendor/care/create', 'vendorCareCreate')->name('vendorCareCreate');

            //customer zone
            Route::get('/customer/zone/{id}', 'customerZone')->name('customerZone');
            //work order
            Route::get('/all/order/list', 'workOrderAll')->name('work.order.all');
            Route::get('/order/edit/{id}', 'editOrder')->name('work.order.edit');
            Route::get('/order/delete/{id}', 'deleteOrder')->name('work.order.delete');
            Route::PUT('/post/edit/{id}', 'editPost')->name('work.order.post');
            Route::get('/order/view/{id}', 'viewOrder')->name('work.order.view');
            Route::post('/work/order/create', 'orderCreate')->name('order.create');
            //begin customer invoice
            Route::get('/invoice/{id}', 'viewInvoice')->name('invoice');
            Route::get('/invoice/paid/list', 'paidInvoice')->name('paid.invoice');
            Route::get('/invoice/due/list', 'dueInvoice')->name('due.invoice');
            Route::get('/invoice/all/history', 'allInvoice')->name('invoice.history');
            Route::post('/work/perform', 'workPerform')->name('work.perform');
            //download pdf
            Route::get('pdf/invoice/download/{id}', 'pdfInvoice')->name('pdf');
            Route::get('pdf/work/order/download/{id}', 'pdfWorkOrder')->name('work.order.pdf');
            Route::get('/multi/assign/coordinate/site', 'multiAssignCoordinateForSite')->name('multiAssign.coordinate');

        });
    });
});