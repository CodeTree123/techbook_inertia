<style>
    .nav.nav-pills.nav-sidebar.flex-column li {
        margin-bottom: 10px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a {
        text-align: left;
        padding: 10px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a i {
        margin-right: 10px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a:hover {
        background-color: #f0f0f0;
    }

    .nav.nav-pills.nav-sidebar.flex-column li {
        margin-bottom: 10px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a {
        text-align: left;
        padding: 10px;
        font-size: 18px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a i {
        margin-right: 10px;
        font-size: 18px;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a {
        background-color: #FFFFFF;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .nav.nav-pills.nav-sidebar.flex-column li a:hover {
        background-color: #FFFFFF;
        /* transform: scale(1.05); */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    p {
        color: black;
    }

    .logo-container {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .logo-container:hover {
        transform: scale(1.2);
    }

    .field-technicians>a {
        background-color: black;
    }


    .customer>a {
        background-color: black;
    }

    .work-order>a {
        background-color: black;
    }

    .ticket>a {
        background-color: black;
    }

    /* Children p tag sections background color */
    .field-technicians .nav-treeview p {
        background-color: black;
    }

    .customer .nav-treeview p {
        background-color: black;
    }

    .work-order .nav-treeview p {
        background-color: black;
    }

    .ticket .nav-treeview p {
        background-color: black;
    }
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-rounded">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboardNew') }}" class="brand-link">
        <img src="{{ asset('assetsNew/dist/img/logo.png') }}" alt="AdminLTE Logo" class="" style="width:40px;">
        <span class="logo-container">
            <img class="side-logo mt-2" src="{{ asset('assetsNew/dist/img/sidelogo.png') }}" alt="">
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills main-item nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{ route('admin.dashboardNew') }}" class="nav-link ">
                        <i class="fas fa-tachometer-alt fa-1x sidebar-icon " style="color: teal;"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (auth()->guard('admin')->user()->role_id != Status::DISPATCH_TEAM ||
                auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user fa-1x" style="color: teal;"></i>
                        <p>
                            Employees
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('employee.index') }}" class="nav-link ">
                                <i class="fas fa-users fa-1x" style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Employee List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-globe fa-1x " style="color: teal;"></i>
                        <p>
                            Field Technicians
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('technician.index') }}" class="nav-link">
                                <i class="fas fa-list-ul fa-1x " style="color: teal;"></i>
                                <p>Technician List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.engineer.list') }}" class="nav-link">
                                <i class="fas fa-list-ul fa-1x " style="color: teal;"></i>
                                <p>Engineer List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.geocode.index') }}" class="nav-link">
                                <i class="fas fa-list-ul fa-1x " style="color: teal;"></i>
                                <p>Address Conversion</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.verified.index') }}" class="nav-link">
                                <i class="fas fa-check-circle fa-1x " style="color: teal;"></i>
                                <p>Verified Ftechs<sup><span
                                            class="badge badge-success">{{ $verifiedTCount }}</span></sup></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.unverified.index') }}" class="nav-link">
                                <i class="fas fa-question fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Unverified Ftechs<sup><span
                                            class="badge badge-success">{{ $unverifiedTCount }}</span></sup></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.available.index') }}" class="nav-link">
                                <i class="fas fa-envelope fa-1x sidebar-icon " style="color: teal;"></i>
                                <p>Available Ftechs<sup><span
                                            class="badge badge-success">{{ $availableFtechCount }}</span></sup></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.disable.index') }}" class="nav-link">
                                <i class="fas fa-ban fa-1x " style="color: teal;"></i>

                                <p>Disable Ftechs<sup><span
                                            class="badge badge-success">{{ $disableTCount }}</span></sup></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.create') }}" class="nav-link">
                                <i class="fas fa-user-plus fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added red color -->
                                <p>Registration</p>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.skillList') }}" class="nav-link">
                                <i class="fas fa-cogs fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added purple color -->
                                <p>Skill Sets</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('distance.index') }}" class="nav-link">
                                <i class="fas fa-map-marker-alt fa-1x " style="color: teal;"></i>
                                <p>Measure Distance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.import.view') }}" class="nav-link">
                                <i class="fas fa-file-import fa-1x " style="color: teal;"></i>
                                <p>Bulk Import</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('technician.check.in.out') }}" class="nav-link">
                                <i class="fas fa-file-import fa-1x " style="color: teal;"></i>
                                <p>Check-In/Out</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user fa-1x" style="color: teal;"></i>
                        <p>
                            Customer
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link ">
                                <i class="fas fa-users fa-1x" style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Customer List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index.with.order') }}" class="nav-link ">
                                <i class="fas fa-arrow-up fa-1x " style="color: teal;"></i>
                                <p>With Order<sup class="badge badge-success"
                                        style="font-size:10px;">{{ $orderCustomerCount }}</sup></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.create') }}" class="nav-link">
                                <i class="fas fa-user-plus fa-1x" style="color: teal;"></i>
                                <!-- Increased size and added red color -->
                                <p>Registration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.site.list') }}" class="nav-link">
                                <i class="fas fa-list-ul fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added purple color -->
                                <p>Site List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt fa-1x" style="color: teal;"></i>
                        <!-- Increased size and added blue color -->
                        <p>
                            Work Order
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('customer.vendorCare') }}" class="nav-link">
                                <i class="fas fa-wrench fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Vendor List Care</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('customer.work.order.all') }}" class="nav-link">
                                <i class="fas fa-file-invoice fa-1x" style="color: teal;"></i>
                                <p>All Work Order List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-invoice fa-1x " style="color: teal;"></i>

                        <p>
                            Invoice
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('customer.invoice.history') }}" class="nav-link ">
                                <i class="fas fa-users fa-1x" style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>All Invoice List</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-warehouse " style="color: teal; font-size:14px;"></i>
                        <!-- Increased size and added blue color -->

                        <p>
                            Inventory
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('inventory.techyeah.index') }}" class="nav-link">
                                <i class="fas fa-boxes fa-1x " style="color: teal;"></i>
                                <p>TechYeah Inventory</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('inventory.customer.index') }}" class="nav-link">
                                <i class="fas fa-cubes fa-1x " style="color: teal;"></i>
                                <p>Customer Inventory</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-ticket-alt fa-1x "
                            style="color: teal;"></i><!-- Increased size and added blue color -->
                        <p>
                            Ticket
                            <i class="fas fa-angle-left right"></i> <sup><span
                                    class="badge badge-success">{{ $allCount }}</span></sup>

                        </p>
                    </a>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.opTicket') }}" class="nav-link">
                                <i class="fas fa-folder-open fa-1x " style="color: teal;"></i>
                                <p>Open Tickets <sup><span
                                            class="badge badge-success">{{ $countOp }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.dTicket') }}" class="nav-link">
                                <i class="fas fa-bolt fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Dispatched Ticket <sup><span
                                            class="badge badge-success">{{ $countD }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.oTicket') }}" class="nav-link">
                                <i class="fas fa-building fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Onsite Ticket <sup><span
                                            class="badge badge-success">{{ $countO }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.iTicket') }}" class="nav-link">
                                <i class="fas fa-file-invoice fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Invoiced Ticket <sup><span
                                            class="badge badge-success">{{ $countI }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.cTicket') }}" class="nav-link">
                                <i class="fas fa-lock fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Closed Ticket <sup><span
                                            class="badge badge-success">{{ $countC }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="{{ route('ticket.hTicket') }}" class="nav-link">
                                <i class="fas fa-clock fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>On Hold Ticket <sup><span
                                            class="badge badge-success">{{ $countH }}</span></sup></p>
                            </a>
                        </li>
                    </ul>
                <li class="nav-item menu-open">
                    <a href="{{ route('backup') }}" class="nav-link ">
                        <i class="fas fa-database" style="color: teal;"></i>
                        <p>
                            Database Backup
                        </p>
                    </a>
                </li>
                </li>
                @elseif(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM ||
                auth()->guard('admin')->user()->role_id == Status::DISPATCH_TEAM)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt fa-1x " style="color: teal;"></i>
                        <!-- Increased size and added blue color -->
                        <p>
                            Ticket
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ticket.dTicket') }}" class="nav-link">
                                <i class="fas fa-wrench fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Dispatched Ticket</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview mx-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-list fa-1x " style="color: teal;"></i>
                                <!-- Increased size and added orange color -->
                                <p>Pending</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="nav-header">Others Content</li> -->
            </ul>
            @endif
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>