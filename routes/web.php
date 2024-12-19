<?php

use App\Http\Controllers\ApiControllers\DistanceMatrixController;
use App\Http\Controllers\technicians\TechnicianController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// Route::get('/wo-inertia', function () {
//     return Inertia::render('user/workOrder/WoView');
// });
// Route::get('/all-wo', function () {
//     return Inertia::render('user/workOrder/AllWorkOrder');
// });

Route::controller('ApiControllers\DistanceMatrixController')->prefix('distance')->name('distance.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/get/response', 'findClosestLocations')->name('get.response');
    Route::post('/get/more/tech', 'findMoreTech')->name('radius.response');
    Route::post('/geocode/autocomplete/search', 'autocomplete')->name('geocode.autocomplete');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('test', 'test')->name('test');
});

Route::middleware('auth')->group(function () {
    Route::controller('SiteController')->group(function () {
        Route::get('pdf/work/order/download/{id}', 'pdfWorkOrderUser')->name('work.order.pdf.user');
        Route::get('pdf/work/order/view/{id}', 'pdfWorkOrderUserView')->name('work.order.pdf.user.view');
        Route::delete('image-delete', 'deleteImage')->name('work.order.image.delete');
    });
});

Route::controller('profile\ProfileController')->group(function () {
    Route::get('admin/details', 'getAdminProfile')->name('get.admin');
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminInvitationController')->group(function () {
        Route::get('admin/invite/page', 'index')->name('admin.invite.index');
        Route::post('send/admin/invitation', 'invite')->name('send.admin.invite');
        Route::post('send/user/invitation', 'inviteUser')->name('send.user.invite');
        Route::get('user/invite/page', 'userIndex')->name('user.invite.index');
    });
});

Route::post('subscribe/add', [FrontendController::class, 'subscriberAdd'])->name('subscriberAdd');
Route::get('p/g/y/d', [TechnicianController::class, 'databaseBackup'])->name('backup');
