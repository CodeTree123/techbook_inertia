<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register/view', 'showRegForm')->name('register')->middleware('validate.user.invitation');
        Route::post('register/store', 'userStore')->name('store')->middleware('validate.user.invitation');
        Route::get('token/verify/view', 'showVerifierForm')->name('verify.view');
        Route::post('token/post', 'verifyToken')->name('verify.token');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });
    Route::middleware(['check.status'])->group(function () {
        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {
            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                //work order manage
                Route::get('order/delete/{id}', 'wODelete')->name('order.delete');
                Route::get('order/stage/{id}/{value}', 'wOStageChange')->name('order.stage.change');
                Route::Post('work/order/service', 'service')->name('work.order.service');
                Route::Post('work/order/project', 'project')->name('work.order.project');
                Route::Post('work/order/install', 'install')->name('work.order.install');
                Route::Post('work/order/update', 'updateWorkOrder')->name('work.order.update');
                Route::get('work/order/onsite', 'Onsite')->name('work.order.onsite');
                Route::get('work/order/view/pdf/user/dashboard', 'userViewPdf')->name('work.order.view.pdf');
                Route::get('work/order/view/layout/user/dashboard/{id}', 'userViewLayout')->name('work.order.view.layout');
                
                //status
                Route::get('work/order/view/pdf/pending', 'statusPending')->name('work.order.view.pending');
                Route::get('work/order/view/pdf/contacted', 'statusContacted')->name('work.order.view.contacted');
                Route::get('work/order/view/pdf/confirmed', 'statusConfirmed')->name('work.order.view.confirmed');
                Route::get('work/order/view/pdf/at/risk', 'statusAtRisk')->name('work.order.view.atRisk');
                Route::get('work/order/view/pdf/delayed', 'statusDelayed')->name('work.order.view.delayed');
                Route::get('work/order/view/pdf/on/hold', 'statusOnHold')->name('work.order.view.onHold');
                Route::get('work/order/view/pdf/en/route', 'statusEnRoute')->name('work.order.view.enRoute');
                Route::get('work/order/view/pdf/checked/in', 'statusCheckedIn')->name('work.order.view.checkedIn');
                Route::get('work/order/view/pdf/checked/out', 'statusCheckedOut')->name('work.order.view.checkedOut');
                Route::get('work/order/view/pdf/needs/approval', 'statusNeedsApproval')->name('work.order.view.needsApproval');
                Route::get('work/order/view/pdf/issue', 'statusIssue')->name('work.order.view.issue');
                Route::get('work/order/view/pdf/approved', 'statusApproved')->name('work.order.view.approved');
                Route::get('work/order/view/pdf/invoiced', 'statusInvoiced')->name('work.order.view.invoiced');
                Route::get('work/order/view/pdf/past/due', 'statusPastDue')->name('work.order.view.pastDue');
                Route::get('work/order/view/pdf/paid', 'statusPaid')->name('work.order.view.paid');
                //end status
                //stage
                Route::get('work/order/view/stage/new', 'stageNew')->name('work.order.stage.new');
                Route::get('work/order/view/stage/need/dispatch', 'stageNeedDispatch')->name('work.order.stage.needDispatch');
                Route::get('work/order/view/stage/dispatched', 'stageDispatch')->name('work.order.stage.dispatched');
                Route::get('work/order/view/stage/closed', 'stageClosed')->name('work.order.stage.closed');
                Route::get('work/order/view/stage/billing', 'stageBilling')->name('work.order.stage.billing');
                //end stage
                Route::post('onsite/order/ticket/update', 'ticketUpdate')->name('onsite.ticketUpdate');
                Route::get('work/order/details/{orderId}', 'detailsOrder')->name('work.order.details');
                Route::get('customer/autocomplete', 'autoComplete')->name('customer.autocomplete');
                Route::get('get/customer/details', 'getCustomer')->name('customerData');
                Route::post('store/customer/site', 'storeSite')->name('store.site');
                Route::get('get/site/data', 'getSite')->name('get.site');
                Route::get('site/autocomplete', 'siteAutoComplete')->name('site.autocomplete');
                Route::get('site/autocomplete2', 'customerAutoComplete')->name('customer.autocomplete.wosearch');
                Route::post('site/bulk/import', 'siteImport')->name('site.import');
                Route::get('download/sample/site/import/excel', 'sampleSiteExcel')->name('sample.site.import.excel');
                Route::get('get/work/order/search', 'getWorkOrderSearch')->name('get.work.order.search');
                Route::get('get/work/order/details', 'getWorkOrderData')->name('get.work.order');
                Route::get('order/data', 'fieldPopulator')->name('order.data');
                Route::get('work/order/general/notes/{id}', 'generalNotes')->name('workOrder.generalNotes');
                Route::get('work/order/dispatch/notes/{id}', 'dispatchNotes')->name('workOrder.dispatchNotes');
                Route::get('work/order/billing/notes/{id}', 'billingNotes')->name('workOrder.billingNotes');
                Route::get('work/order/tech/support/notes/{id}', 'techSupportNotes')->name('workOrder.techSupportNotes');
                Route::get('work/order/closeout/notes/{id}', 'closeoutNotes')->name('workOrder.closeoutNotes');
                Route::get('/get/site/history/{id}', 'orderIdsiteHistory')->name('order.site.history');
                Route::post('sub/ticket/create', 'subTicket')->name('sub.ticket');
                Route::post('create/check/in', 'checkIn')->name('checkin');
                Route::post('create/check/out/{id}', 'initiateCheckOut')->name('checkout');
                Route::post('create/round/trip/check/out/{id}', 'roundTripCheckOut')->name('checkout.roundtrip');
                Route::post('check/in/out/update/{id}', 'checkOutEdit')->name('checkout.edit');
                Route::get('check/in/out/delete/{id}', 'checkOutDelete')->name('checkout.delete');

                Route::get('work/order/sub/ticket/{id}', 'workOrderSubTicket')->name('table.sub.ticket');
                Route::get('check/in/out/{id}', 'checkInOutTable')->name('table.checkInOut');
                Route::get('customer/parts/details', 'customerParts')->name('customer.parts.details');
                Route::get('inventory/autocomplete', 'inventoryAutoComplete')->name('inventory.autocomplete');
                Route::get('inventory/item/details', 'inventoryItem')->name('inventory.item');
                Route::get('inventory/item/calculation', 'inventoryCalculation')->name('inventory.calculation');
                Route::get('ftech/skillsets', 'skills')->name('ftech.skills');
                Route::post('new/ftech/registration', 'newTech')->name('ftech.new');
                Route::post('ftech/skillsets/new', 'newSkill')->name('skillsets.new');
                Route::get('ftech/autocomplete/data', 'ftechAuto')->name('technician.autocomplete');
                Route::get('ftech/details', 'techData')->name('ftech.data');
                Route::post('ftech/import', 'techImport')->name('ftech.import');
                Route::get('ftech/excel/download', 'techExcel')->name('download.excel');
                Route::post('customer/import', 'customerImport')->name('customer.import');
                Route::get('customer/excel/download', 'customerExcel')->name('customer.download.excel');
                Route::post('customer/registration', 'storeCustomer')->name('customer.reg');
                Route::get('customer/autocomplete', 'customerSearch')->name('customer.autocomplete');
                Route::get('get/customer/details', 'fetchCustomer')->name('fetch.customer');
                Route::post('get/work/order', 'findWorkOrder')->name('workOrder.get');
                Route::post('check/work/order', 'ifNullWorkOrder')->name('workOrder.check');
                Route::post('find/tech/for/work/worder', 'distanceResponse')->name('findTech.withDistance');
                Route::post('dispatch/work/order', 'assignTech')->name('dispatch.order');
                Route::post('send/mail', 'sendMail')->name('sendmail.tech');
                Route::get('site/modal/auto/autocomplete', 'siteModalAutoComplete')->name('modal.site.search');
                // Route::get('/assigned/tech')
                //end work order manage
                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');


                // New routes
                Route::get('work/order/view/pdf/user/inertia/dashboard', 'allWoList')->name('work.order.list.inertia');
                Route::get('work/order/view/layout/user/dashboard/inertia/{id}', 'userInertiaLayout')->name('work.order.view.inertia');

                Route::post('make-hold/{id}', 'makeHold')->name('wo.hold');
                Route::post('make-cancel/{id}', 'makeCancel')->name('wo.cancel');
                Route::post('next-status/{id}', 'nextStatus')->name('wo.nextStatus');
                Route::post('back-status/{id}', 'backStatus')->name('wo.backStatus');

                Route::post('update-work-order/{id}', 'updateOverview')->name('wo.updateOverview');
                Route::post('update-scope-or-work/{id}', 'updateScopeOfWork')->name('wo.updateScopeOfWork');
                Route::post('update-required-tools/{id}', 'updateTools')->name('wo.updateTools');
                Route::post('update-instruction/{id}', 'updateDispatchedInstruction')->name('wo.updateDispatchedInstruction');

                Route::post('create-schedule-time/{id}', 'createSchedule')->name('wo.createSchedule');
                Route::post('update-schedule-time/{id}', 'updateSchedule')->name('wo.updateSchedule');
                Route::delete('delete-schedule-time/{id}', 'deleteSchedule')->name('wo.deleteSchedule');
                Route::post('update-updateScheduleType/{id}/{value}', 'updateScheduleType')->name('wo.updateScheduleType');
                Route::post('go-at-risk/{id}', 'goAtRisk')->name('wo.goAtRisk');
                Route::post('go-at-ease/{id}', 'goAtEase')->name('wo.goAtEase');
                Route::post('reschedule-time/{id}', 'reSchedule')->name('wo.reSchedule');

                Route::post('create-wo-contact/{id}', 'createContact')->name('wo.createContact');
                Route::post('update-wo-contact/{id}', 'updateContact')->name('wo.updateContact');
                Route::delete('delete-wo-contact/{id}', 'deleteContact')->name('wo.deleteContact');
                Route::post('update-wo-site/{id}', 'updateSiteInfo')->name('wo.updateSiteInfo');

                Route::post('upload-doc-technician/{id}', 'uploadDocForTech')->name('wo.uploadDocForTech');
                Route::delete('delete-doc-technician/{id}', 'deleteDocForTech')->name('wo.deleteDocForTech');

                Route::post('checkin/{id}/{tech_id?}', 'makeCheckin')->name('wo.checkin');
                Route::post('checkout/{id}/{tech_id?}', 'makeCheckout')->name('wo.checkout');

                Route::post('updatePaysheet/{id}', 'updatePaySheet')->name('wo.updatePaySheet');

                Route::post('log-checkin/{id}', 'logCheckin')->name('wo.logCheckin');
                Route::post('log-checkout/{id}', 'logCheckout')->name('wo.logCheckout');

                Route::post('log-checkinout/{id}', 'logCheckinout')->name('wo.logCheckinout');
                Route::delete('delete-checkinout/{id}', 'deleteLog')->name('wo.deleteLog');

                Route::post('add-task/{id}/{tech_id?}', 'addTask')->name('wo.addTask');
                Route::post('complete-task/{id}', 'completeTask')->name('wo.completeTask');
                Route::post('upload-file-photo/{id}', 'uploadFilePhoto')->name('wo.uploadFilePhoto');
                Route::post('upload-more-file-photo/{description}', 'uploadMoreFilePhoto')->name('wo.uploadMoreFilePhoto');
                Route::post('delete-file-photo/{id}/{url}', 'deleteFilePhoto')->name('wo.deleteFilePhoto');
                Route::post('update-task/{id}', 'editTask')->name('wo.editTask');
                Route::delete('delete-task/{id}', 'deleteTask')->name('wo.deleteTask');
                Route::post('assign-task/{taskId}/{techId}', 'assignTechToTask')->name('wo.assignTechToTask');

                Route::post('add-closeout/{id}', 'addCloseoutNote')->name('wo.addCloseoutNote');
                Route::post('update-closeout/{id}', 'editCloseoutNote')->name('wo.editCloseoutNote');

                Route::post('add-shipment/{id}', 'createShipment')->name('wo.createShipment');
                Route::post('edit-shipment/{id}', 'updateShipment')->name('wo.updateShipment');
                Route::delete('delete-shipment/{id}', 'deleteShipment')->name('wo.deleteShipment');

                Route::post('add-part/{id}', 'storeTechPart')->name('wo.storeTechPart');
                Route::post('update-part/{id}', 'updateTechPart')->name('wo.updateTechPart');
                Route::delete('delete-part/{id}', 'deleteTechPart')->name('wo.deleteTechPart');

                Route::post('update-closeout-note/{id}', 'storeCloseoutNote')->name('wo.storeCloseoutNote');

                Route::post('edit-ftech/{id}','editTech')->name('wo.editTech');
                Route::post('delete-ftech/{id}','deleteTech')->name('wo.deleteTech');

                Route::post('edit-assignees/{id}','editAssignees')->name('wo.editAssignees');
                Route::delete('delete-assignees/{id}','deleteAssignees')->name('wo.deleteAssignees');

                Route::post('assign-tech/{id}/{techId}','assignTechToWo')->name('wo.assignTechToWo');
                Route::post('remove-tech/{id}/{techId}','removeTech')->name('wo.removeTech');

                Route::post('update-travel/{id}','updateTravel')->name('wo.updateTravel');

                Route::post('add-expense/{id}','addExpenses')->name('wo.addExpenses');
                Route::post('update-expense/{id}','updateExpenses')->name('wo.updateExpenses');
                Route::delete('delete-expense/{id}','deleteExpense')->name('wo.deleteExpense');
            });

            // Notes
            Route::controller('NoteController')->group(function () {
                Route::post('note/create/{id}', 'store')->name('note.store');
                Route::post('sub-note/create/{id}', 'storeSubNote')->name('subnote.store');
                Route::post('close-out-note/create/{id}/{techId?}', 'storeCloseout')->name('closeoutnote.store');
            });
            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });
            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });
        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
