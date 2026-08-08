<!-- Hospital Sidebar -->
<div class="sidebar" id="sidebar">

    <div class="sidebar-logo active">

        <a href="{{ route('hospital.dashboard') }}"
           class="logo logo-normal d-flex align-items-center justify-content-center w-100">

            <img src="{{ asset($site->site_logo) }}"
                 alt="Logo">

        </a>

        <a href="{{ route('hospital.dashboard') }}"
           class="logo logo-white">

            <img src="{{ asset($site->site_logo) }}"
                 alt="Logo">

        </a>

        <a href="{{ route('hospital.dashboard') }}"
           class="logo-small">

            <img src="{{ asset($site->site_logo) }}"
                 alt="Logo">

        </a>

        <a id="toggle_btn"
           href="javascript:void(0);">

            <i data-feather="chevrons-left"
               class="feather-16"></i>

        </a>

    </div>


    <div class="sidebar-inner slimscroll">

        <div id="sidebar-menu"
             class="sidebar-menu">

            <ul>

                {{-- Dashboard --}}
                <li class="submenu-open">

                    <h6 class="submenu-hdr">
                        Dashboard
                    </h6>

                    <ul>

                        <li class="{{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">

                            <a href="{{ route('hospital.dashboard') }}">

                                <i class="ti ti-layout-grid fs-16 me-2"></i>

                                <span>Dashboard</span>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- Hospital Management --}}
                <li class="submenu-open">

                    <h6 class="submenu-hdr">
                        Hospital Management
                    </h6>

                    <ul>

                        <li class="{{ request()->routeIs('hospital.doctors.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.doctors.index') }}">

                                <i class="ti ti-stethoscope fs-16 me-2"></i>

                                <span>Doctors</span>

                            </a>

                        </li>


                        <li class="{{ request()->routeIs('hospital.appointments.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.appointments.index') }}">

                                <i class="ti ti-calendar-check fs-16 me-2"></i>

                                <span>Appointments</span>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- Ambulance --}}
                <li class="submenu-open">

                    <h6 class="submenu-hdr">
                        Ambulance Management
                    </h6>

                    <ul>

                        <li class="{{ request()->routeIs('hospital.ambulances.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.ambulances.index') }}">

                                <i class="ti ti-ambulance fs-16 me-2"></i>

                                <span>Ambulances</span>

                            </a>

                        </li>


                        <li class="{{ request()->routeIs('hospital.ambulance-bookings.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.ambulance-bookings.index') }}">

                                <i class="ti ti-map-pin fs-16 me-2"></i>

                                <span>Ambulance Requests</span>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- Pharmacy --}}
                <li class="submenu-open">

                    <h6 class="submenu-hdr">
                        Pharmacy
                    </h6>

                    <ul>

                        <li class="{{ request()->routeIs('hospital.medicines.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.medicines.index') }}">

                                <i class="ti ti-pill fs-16 me-2"></i>

                                <span>Medicines</span>

                            </a>

                        </li>


                        <li class="{{ request()->routeIs('hospital.medicine-orders.*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.medicine-orders.index') }}">

                                <i class="ti ti-shopping-cart fs-16 me-2"></i>

                                <span>Medicine Orders</span>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- Account --}}
                <li class="submenu-open">

                    <h6 class="submenu-hdr">
                        Account
                    </h6>

                    <ul>

                        <li class="{{ request()->routeIs('hospital.profile*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.profile') }}">

                                <i class="ti ti-user fs-16 me-2"></i>

                                <span>Profile</span>

                            </a>

                        </li>
                        <li class="{{ request()->routeIs('hospital.coupons*') ? 'active' : '' }}">

                            <a href="{{ route('hospital.coupons.index') }}">

                                <i class="ti ti-ticket fs-16 me-2"></i>

                                <span>Coupons</span>

                            </a>

                        </li>


                        <li>

                            <a href="{{ route('hospital.logout') }}">

                                <i class="ti ti-logout fs-16 me-2 text-danger"></i>

                                <span class="text-danger">
                                    Logout
                                </span>

                            </a>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</div>
<!-- /Hospital Sidebar -->