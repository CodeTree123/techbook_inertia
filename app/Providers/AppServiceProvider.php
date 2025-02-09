<?php

namespace App\Providers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\Withdrawal;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Tighten\Ziggy\Ziggy;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        view()->composer('*', function ($view) {
            $ziggy = new Ziggy();
            $view->with('ziggy', $ziggy->toArray());
        });
        //
        $general = gs();
        $viewShare['general'] = $general;
        // $viewShare['language'] = Language::all();
        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);


        view()->composer('admin.include.sidebar', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount' => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount'   => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'   => User::kycUnverified()->count(),
                'kycPendingUsersCount'   => User::kycPending()->count(),
                'pendingDepositsCount'    => Deposit::pending()->count(),
                'pendingWithdrawCount'    => Withdrawal::pending()->count(),
                'pendingTicketCount'     => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
            ]);
        });

        view()->composer('admin.includeNew.sidebar', function ($view) {
            $view->with([
                'orderCustomerCount' => Customer::WithOrder()->count(),
                'unverifiedTCount' => Technician::DocUnverifiedFtech()->count(),
                'verifiedTCount' => Technician::DocVerifiedFtech()->count(),
                'availableFtechCount' => Technician::AvailableFtech()->count(),
                'disableTCount' => Technician::AssignedFtech()->count(),

                'allCount' => WorkOrder::all()->count(),
                // 'paidInvoiceCount' => WorkOrder::PaidInvoice()->count(),
                // 'dueInvoiceCount' => WorkOrder::DueInvoice()->count(),
                'countOp' => WorkOrder::PendingTicket()->count(),
                'countD' => WorkOrder::ContactedTicket()->count(),
                'countO' => WorkOrder::ConfirmedTicket()->count(),
                'countI' => WorkOrder::PaidTicket()->count(),
                'countC' => WorkOrder::PaidTicket()->count(),
                'countH' => WorkOrder::OnHoldTicket()->count(),
            ]);
        });

        view()->composer('admin.include.topbar', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('admin.includeNew.topbar', function ($view) {
            $view->with([
                'allCount' => WorkOrder::all()->count(),
                'countOp' => WorkOrder::PendingTicket()->count(),
                'countD' => WorkOrder::ContactedTicket()->count(),
                'countO' => WorkOrder::ConfirmedTicket()->count(),
                'countI' => WorkOrder::AtRiskTicket()->count(),
                'countC' => WorkOrder::DelayedTicket()->count(),
                'countH' => WorkOrder::OnHoldTicket()->count(),
            ]);
        });

        view()->composer('admin.dashboardNew', function ($view) {
            $view->with([
                'allCustomerCount' => Customer::all()->count(),
                'orderCustomerCount' => Customer::WithOrder()->count(),
            ]);
        });

        view()->composer('user.workOrder.list_pdf_view', function ($view) {
            $view->with([
                'new' => WorkOrder::where('stage', Status::STAGE_NEW)->count(),
                'needDispatch' => WorkOrder::where('stage', Status::STAGE_NEED_DISPATCH)->count(),
                'dispatched' => WorkOrder::where('stage', Status::STAGE_DISPATCH)->count(),
                'closed' => WorkOrder::where('stage', Status::STAGE_CLOSED)->count(),
                'billing' => WorkOrder::where('stage', Status::STAGE_BILLING)->count(),
                'pending' => WorkOrder::where('status', Status::PENDING)->count(),
                'contacted' => WorkOrder::where('status', Status::CONTACTED)->count(),
                'confirmed' => WorkOrder::where('status', Status::CONFIRM)->count(),
                'atRisk' => WorkOrder::where('status', Status::AT_RISK)->count(),
                'delayed' => WorkOrder::where('status', Status::DELAYED)->count(),
                'onHold' => WorkOrder::where('status', Status::ON_HOLD)->count(),
                'enRoute' => WorkOrder::where('status', Status::EN_ROUTE)->count(),
                'checkedIn' => WorkOrder::where('status', Status::CHECKED_IN)->count(),
                'checkedOut' => WorkOrder::where('status', Status::CHECKED_OUT)->count(),
                'needsApproval' => WorkOrder::where('status', Status::NEEDS_APPROVAL)->count(),
                'issue' => WorkOrder::where('status', Status::ISSUE)->count(),
                'approved' => WorkOrder::where('status', Status::APPROVED)->count(),
                'invoiced' => WorkOrder::where('status', Status::INVOICED)->count(),
                'pastDue' => WorkOrder::where('status', Status::PAST_DUE)->count(),
                'paid' => WorkOrder::where('status', Status::PAID)->count(),
            ]);
        });


        Paginator::useBootstrapFive();

        $non_converted_techs = Technician::whereRaw("ST_X(co_ordinates) IS NULL OR ST_Y(co_ordinates) IS NULL")->count();
        view()->share('non_converted_techs', $non_converted_techs);

        Inertia::share([
            'flash' => function () {
                return [
                    'verifyError' => Session::get('verifyError'),
                    'success' => Session::get('success'),
                ];
            },
        ]);
    }
}
