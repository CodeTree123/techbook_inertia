        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.dashboard')}}">
                <div class="sidebar-brand-icon ">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                    <div id="my_logo">

                        <img src="{{getImage(getFilePath('logoIcon') .'/logo.png')}}" width="50px" height="50px" id="my_logo_image">

                    </div>
                </div>
                <div class="sidebar-brand-text mx-3"> {{$general->site_name}} <sup><i class="fas fa-laugh-wink"></i></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{route('admin.dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                User
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed " href="javascript:void(0)" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Manage User</span>
                    @if($bannedUsersCount > 0 || $emailUnverifiedUsersCount > 0 || $mobileUnverifiedUsersCount > 0 || $kycUnverifiedUsersCount > 0 || $kycPendingUsersCount > 0)
                    <span class="menu-badge pill bg-danger ms-auto">
                        <i class="fa fa-exclamation"></i>
                    </span>
                    @endif
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Menu:</h6>
                        <a class="collapse-item" href="{{route('admin.users.active')}}"><i class="menu-icon fa fa-dot-circle">Active User</i></a>
                        <a class="collapse-item" href="{{route('admin.users.banned')}}"><i class="menu-icon fa fa-dot-circle">Banned User</i>
                            @if($bannedUsersCount)
                            <span class="bg-danger">{{$bannedUsersCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.users.email.unverified')}}"><i class="menu-icon fa fa-dot-circle">Email Unverified</i>
                            @if($emailUnverifiedUsersCount)
                            <span class="bg-danger">{{$emailUnverifiedUsersCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.users.mobile.unverified')}}"><i class="menu-icon fa fa-dot-circle">Mobile Unverified</i>
                            @if($mobileUnverifiedUsersCount)
                            <span class="bg-danger">{{$mobileUnverifiedUsersCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.users.kyc.unverified')}}"><i class="menu-icon fa fa-dot-circle">KYC Unverified</i>
                            @if($kycUnverifiedUsersCount)
                            <span class="bg-danger">{{$kycUnverifiedUsersCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.users.with.balance')}}"><i class="menu-icon fa fa-dot-circle">With Balance</i></a>
                        <a class="collapse-item" href="{{route('admin.users.all')}}"><i class="menu-icon fa fa-dot-circle">All User</i></a>
                        <a class="collapse-item" href="{{route('admin.users.notification.all')}}"><i class="menu-icon fa fa-dot-circle">Notification to all</i></a>
                    </div>
                </div>
            </li>
            <!-- Heading -->
            <div class="sidebar-heading">
                Gateway
            </div>
            <!-- Nav Item - Payment Gateway -->
            <li class="nav-item">
                <a class="nav-link collapsed " href="javascript:void(0)" data-toggle="collapse" data-target="#collapse-Two" aria-expanded="true" aria-controls="collapse-Two">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Payment Gateway</span>
                </a>
                <div id="collapse-Two" class="collapse" aria-labelledby="heading-Two" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gateway:</h6>
                        <a class="collapse-item" href="{{route('admin.gateway.manual.index')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Manual Gateways')</i></a>
                    </div>
                </div>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Deposit
            </div>
            <!-- Nav Item - Deposit -->
            <li class="nav-item">
                <a class="nav-link collapsed " href="javascript:void(0)" data-toggle="collapse" data-target="#collapse--Two" aria-expanded="true" aria-controls="collapse--Two">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Deposits</span>
                    @if(0 < $pendingDepositsCount) <span class="menu-badge pill bg-danger ms-auto">
                        <i class="fa fa-exclamation"></i>
                        </span>
                        @endif
                </a>
                <div id="collapse--Two" class="collapse" aria-labelledby="heading--Two" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Deposit:</h6>
                        <a class="collapse-item" href="{{route('admin.deposit.pending')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Pending Deposits')</i>
                            @if($pendingDepositsCount)
                            <span class="menu-badge pill bg-danger ms-auto">{{$pendingDepositsCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.deposit.approved')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Approved Deposits')</i></a>
                        <a class="collapse-item" href="{{route('admin.deposit.successful')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Successful Deposits')</i></a>
                        <a class="collapse-item" href="{{route('admin.deposit.rejected')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Rejected Deposits')</i></a>
                        <a class="collapse-item" href="{{route('admin.deposit.initiated')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Initiated Deposits')</i></a>
                        <a class="collapse-item" href="{{route('admin.deposit.list')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('All Deposits')</i></a>
                    </div>
                </div>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Withdraw
            </div>
            <!-- Nav Item - Withdraw -->
            <li class="nav-item">
                <a class="nav-link collapsed " href="javascript:void(0)" data-toggle="collapse" data-target="#collapse---Two" aria-expanded="true" aria-controls="collapse--Two">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Withdrawals</span>
                    @if(0 < $pendingWithdrawCount) <span class="menu-badge pill bg-danger ms-auto">
                        <i class="fa fa-exclamation"></i>
                        </span>
                        @endif
                </a>
                <div id="collapse---Two" class="collapse" aria-labelledby="heading---Two" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Withdraw:</h6>
                        <a class="collapse-item" href="{{route('admin.withdraw.pending')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Pending Withdrawals')</i>
                            @if($pendingWithdrawCount)
                            <span class="menu-badge pill bg-danger ms-auto">{{$pendingWithdrawCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.withdraw.method.index')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Withdrawal Methods')</i></a>
                        <a class="collapse-item" href="{{route('admin.withdraw.approved')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Approved Withdrawals')</i></a>
                        <a class="collapse-item" href="{{route('admin.withdraw.rejected')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Rejected Withdrawals')</i></a>
                        <a class="collapse-item" href="{{route('admin.withdraw.log')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('All Withdrawals')</i></a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Support Ticket Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse-support" aria-expanded="true" aria-controls="collapse-support">
                    <i class="fa fa-sms"></i>
                    <span>Support Ticket</span>
                    @if(0 < $pendingTicketCount) <span class="menu-badge pill bg-danger ms-auto">
                        <i class="fa fa-exclamation"></i>
                        </span>
                        @endif
                </a>
                <div id="collapse-support" class="collapse" aria-labelledby="headingsupport" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Support Ticket:</h6>
                        <a class="collapse-item" href="{{route('admin.ticket.assign')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Assign Ticket')</i></a>
                        <a class="collapse-item" href="{{route('admin.ticket.pending')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Pending Ticket')</i>
                            @if($pendingTicketCount)
                            <span class="menu-badge pill bg-danger ms-auto">{{$pendingTicketCount}}</span>
                            @endif</a>
                        <a class="collapse-item" href="{{route('admin.ticket.closed')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Closed Ticket')</i></a>
                        <a class="collapse-item" href="{{route('admin.ticket.answered')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Answer Ticket')</i></a>
                        <a class="collapse-item" href="{{route('admin.ticket.index')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('All Ticket')</i></a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Reports Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-list"></i>
                    <span>Report</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Reports:</h6>
                        <a class="collapse-item" href="{{route('admin.report.transaction')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Transaction Log')</i></a>
                        <a class="collapse-item" href="{{route('admin.report.login.history')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Login History')</i></a>
                        <a class="collapse-item" href="{{route('admin.report.notification.history')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Notification History')</i></a>
                    </div>
                </div>
            </li>
            <!-- Nav Item - Subscribers-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.subscriber.index') }}">
                    <i class="fa fa-thumbs-up"></i>
                    <span>@lang('Subscribers')</span>
                </a>
            </li>
            <!-- Nav Item - Items-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.item.index') }}">
                    <i class="fa fa-box"></i>
                    <span>@lang('Items')</span>
                </a>
            </li>
            <!-- Nav Item - Expenses-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.item.index') }}">
                    <i class="fas fa-balance-scale"></i>
                    <span>@lang('Expenses')</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Settings
            </div>

            <!-- Nav Item - General Setting -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('admin.setting.index')}}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>@lang('General Setting')</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('admin.setting.logo.icon')}}">
                    <i class="fas fa-images"></i>
                    <span>@lang('Logo & Favicon')</span>
                </a>
            </li>

            <!-- Nav Item - Notification Setting -->
            <li class="nav-item">
                <a class="nav-link collapsed " href="javascript:void(0)" data-toggle="collapse" data-target="#collapse-notification" aria-expanded="true" aria-controls="collapse-notification">
                    <i class="fas fa-bell"></i>
                    <span>@lang('Notification Setting')</span>
                </a>
                <div id="collapse-notification" class="collapse" aria-labelledby="heading-notification" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Notication:</h6>
                        <a class="collapse-item" href="{{route('admin.setting.notification.global')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Global Template')</i></a>
                        <a class="collapse-item" href="{{route('admin.setting.notification.email')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Email Setting')</i></a>
                        <a class="collapse-item" href="{{route('admin.setting.notification.sms')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('SMS Setting')</i></a>
                        <a class="collapse-item" href="{{route('admin.setting.notification.templates')}}">
                            <i class="menu-icon fa fa-dot-circle">@lang('Notification Templates')</i></a>
                    </div>
                </div>
            </li>
            <!-- kyc setting -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('admin.kyc.setting')}}">
                    <i class="fas fa-user-check"></i>
                    <span>@lang('KYC Setting')</span>
                </a>
            </li>

            <!-- kyc setting -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('admin.extensions.index')}}">
                    <i class="fas fa-cogs"></i>
                    <span>@lang('Extension')</span>
                </a>
            </li>

            <!-- Heading -->
            <div class="sidebar-heading">
                Frontend Manager
            </div>

            <!-- Nav Item - General Setting -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('admin.frontend.manage.pages')}}">
                    <i class="fa fa-list"></i>
                    <span>@lang('Manage Pages')</span>
                </a>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fa fa-puzzle-piece"></i>
                    <span>@lang('Manage Section')</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Sections:</h6>
                        @php
                        $lastSegment = collect(request()->segments())->last();
                        @endphp
                        @foreach(getPageSections(true) as $k => $secs)
                        @if($secs['builder'])

                        <a href="{{ route('admin.frontend.sections',$k) }}" class="collapse-item">
                            <i class="fas fa-dot-circle">{{__($secs['name'])}}</i>
                        </a>

                        @endif
                        @endforeach
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
        <!-- End of Sidebar -->