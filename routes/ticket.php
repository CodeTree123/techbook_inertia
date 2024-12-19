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

Route::middleware('admin')->group(function () {
    Route::namespace('TechYeahTickets')->group(function () {
        Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
            //open ticket
            Route::get('open/ticket/', 'openTicket')->name('opTicket');
            //begin dispatched ticket
            Route::get('dispatched/ticket/', 'dispatchTicket')->name('dTicket');
            //onsite Ticket
            Route::get('onsite/ticket/', 'onsiteTicketView')->name('oTicket');
            Route::get('onsite/{id}', 'onsiteTicket')->name('onsite.ticket');
            //invoiced ticket
            Route::get('invoice/ticket/', 'invoicedTicketView')->name('iTicket');
            Route::get('invoice/{id}', 'invoicedTicket')->name('invoice.ticket');
            //closed ticket
            Route::get('close/ticket/', 'closedTicketView')->name('cTicket');
            Route::get('close/{id}', 'closedTicket')->name('close.ticket');
            //hold ticket
            Route::get('hold/ticket/', 'onHoldTicketView')->name('hTicket');
            Route::get('hold/{id}', 'onHoldTicket')->name('hold.ticket');
        });
    });
});
