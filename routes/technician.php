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

// technicians routes
Route::middleware('admin')->group(function () {
    Route::namespace('technicians')->group(function () {
        Route::controller('TechnicianController')->prefix('technician')->name('technician.')->group(function () {
            Route::get('/index', 'index')->name('index');
            Route::get('/index/documents/unverified', 'dUnverifiedTech')->name('unverified.index');
            Route::get('/index/documents/verified', 'dVerifiedTech')->name('verified.index');
            Route::get('/index/available/tech', 'availableTech')->name('available.index');
            Route::get('/index/disable/tech', 'disableTech')->name('disable.index');
            Route::get('/registration', 'create')->name('create');
            Route::post('/skills/store', 'skills')->name('skills.store');
            Route::get('get/skills', 'getSkills')->name('get.skills');
            Route::get('all/skill', 'allSkill')->name('all.skill');
            Route::post('/store/data', 'storeTech')->name('store');
            //begin List
            Route::get('/skill/list', 'skillList')->name('skillList');
            //end List
            //edit Cat
            Route::get('/cat/edit/{id}', 'catEdit')->name('catEdit');
            Route::get('/cat/delete/{id}', 'catDelete')->name('catDelete');
            Route::PUT('/cat/edit/post/{id}', 'catEditPost')->name('catEditPost');
            //end cat edit

            //edit Technician
            Route::get('/tech/edit/{id}', 'techEdit')->name('techEdit');
            Route::get('/tech/delete/{id}', 'techDelete')->name('techDelete');
            Route::PUT('/tech/edit/post/{id}', 'techEditPost')->name('techEditPost');
            //end Technician edit

            //getting technician profile
            Route::post('get/profile', 'getTech')->name('get.profile');
            //star update
            Route::post('star/update', 'updateStar')->name('star.update');

            //search tech
            Route::get('/search', 'techSearch')->name('techSearch');
            //view pdf
            Route::get('/view-pdf/coi/{id_coi}', 'viewPdf_coi')->name('viewPdf_coi');
            Route::get('/view-pdf/msa/{id_msa}', 'viewPdf_msa')->name('viewPdf_msa');
            Route::get('/view-pdf/nda/{id_nda}', 'viewPdf_nda')->name('viewPdf_nda');
            //comment update
            Route::post('comment/update', 'updateComment')->name('comment.update');
            Route::post('multiple/skills/store', 'storeSkills')->name('multi.skill.store');

            //technician import
            Route::get('/bulk/import/view', 'importView')->name('import.view');
            Route::post('/import/excel', 'import')->name('excel.import');
            Route::get('/download/sample/excel', 'sampleExcel')->name('download.sampleExcel');


            //technicians exports
            Route::get('/export-fake-technicians', 'export')->name('fake.export');
            Route::get('/get/import/progress', 'progress')->name('import.progress');
            //technician Check-In/Out
            Route::get('/technicians/check/in/out', 'checkInOut')->name('check.in.out');
            Route::get('/technicians/pdf/in/out', 'generatePDFCheckInOut')->name('pdf.in.out');
            Route::get('/distance', 'findClosestLocations');
            Route::get('/coordinates/response', 'getCoordinate')->name('coordinate.get');
            Route::get('/geocode/view', 'geocodeIndex')->name('geocode.index');
            Route::get('/autocomplete', 'techAutocomplete')->name('autocomplete');
            Route::post('/assign/coordinate', 'assignLatLong')->name('assign.coordinate');
            Route::get('/multi/assign/coordinate', 'multiAssignCoordinate')->name('multiAssign.coordinate');
            // Route to fetch technicians by zipcode
            Route::get('/zipCode/search', 'zipCodeSearch')->name('zipCode.search');

            // Route to fetch technician details
            Route::get('/zipCode/{id}', 'zipCodeDetails')->name('zipCode.details');

            // Engineer
            Route::get(
                '/engineer-list',
                'engineerList'
            )->name('engineer.list');
            Route::post(
                '/engineer-add',
                'createEngineer'
            )->name('engineer.add');
            
        });
    });
});
Route::namespace('technicians')->group(function () {
    Route::controller('TechnicianController')->prefix('technician')->name('technician.')->group(function () {
        // Route to fetch technicians by zipcode
        Route::get('/zipCode/search', 'zipCodeSearch')->name('zipCode.search');

        // Route to fetch technician details
        Route::get('/zipCode/details/{id}', 'zipCodeDetails')->name('zipCode.details');
        Route::post(
            '/engineer-assign/{id}',
            'assignEng'
        )->name('engineer.assign');
    });
});