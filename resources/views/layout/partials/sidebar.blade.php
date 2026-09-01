<!-- Sidebar -->
<div class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo active">
                <a href="{{ url('admin/dashboard') }}" class="logo logo-normal d-flex align-items-center">
                        <img src="{{ asset($site->site_logo) }}" alt="Logo">
                        {{-- <img src="{{ asset('website/images/vishwa.png') }}" alt="Vishwa" style="height:50px;"> --}}
                </a>
                <a href="{{url('admin/dashboard')}}" class="logo logo-white">
                        <img src="{{ asset($site->site_logo) }}" alt="Img">
                        {{-- <img src="{{asset('website')}}/images/vishwa.png" alt="Img"> --}}
                </a>
                <a href="{{url('admin/dashboard')}}" class="logo-small">
                        <img src="{{ asset($site->site_logo) }}" alt="Img">
                        {{-- <img src="{{asset('website')}}/images/vishwa.png" alt="Img"> --}}
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                        <i data-feather="chevrons-left" class="feather-16"></i>
                </a>
        </div>
        <!-- /Logo -->

        <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                        <ul>
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Dashboard</h6>
                                        <ul>
                                                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.dashboard') }}"><i
                                                                        class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span></a>
                                                </li>
                                        </ul>
                                </li>
                                <!------schedule----->
                                <!------ Hospital Management ----->
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Hospital Management</h6>

                                        <ul>

                                                <li
                                                        class="{{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.hospitals.index') }}">
                                                                <i class="ti ti-building-hospital fs-16 me-2"></i>
                                                                <span>Hospitals</span>
                                                        </a>
                                                </li>

                                                <li
                                                        class="{{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.specializations.index') }}">
                                                                <i class="ti ti-stethoscope fs-16 me-2"></i>
                                                                <span>Specializations</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.diagnostics.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.diagnostics.index') }}">
                                                                <i class="ti ti-microscope fs-16 me-2"></i>
                                                                <span>Diagnostics</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.lab-tests.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.lab-tests.index') }}">
                                                                <i class="ti ti-test-pipe fs-16 me-2"></i>
                                                                <span>Lab Tests</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.lab-tests-bookings.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.lab-tests-bookings.index') }}">
                                                                <i class="ti ti-test-pipe fs-16 me-2"></i>
                                                                <span>LabTests Bookings</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.ambulance-types.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.ambulance-types.index') }}">
                                                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                                                <span>Ambulance Types</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.appointments.index') }}">
                                                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                                                <span>Doctor Appointments</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.patient-medical-reports.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.patient-medical-reports.index') }}">
                                                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                                                <span>Patient Medical Reports</span>
                                                        </a>
                                                </li>
                                                {{-- <li
                                                        class="{{ request()->routeIs('admin.lab-tests-bookings.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.lab-tests-bookings.index') }}">
                                                                <i class="ti ti-test-pipe fs-16 me-2"></i>
                                                                <span>Lab Test Bookings</span>
                                                        </a>
                                                </li> --}}
                                                <li
                                                        class="{{ request()->routeIs('admin.ambulances.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.ambulances.index') }}">
                                                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                                                <span>Ambulances</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.ambulance-bookings.*') ? 'active' : '' }}">

                                                        <a href="{{ route('admin.ambulance-bookings.index') }}">

                                                                <i class="ti ti-map-pin fs-16 me-2"></i>

                                                                <span>Ambulance Requests</span>

                                                        </a>

                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.medicine-categories.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.medicine-categories.index') }}">
                                                                <i class="ti ti-category fs-16 me-2"></i>
                                                                <span>Medicine Categories</span>
                                                        </a>
                                                </li>

                                                <li
                                                        class="{{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.medicines.index') }}">
                                                                <i class="ti ti-pill fs-16 me-2"></i>
                                                                <span>Medicines</span>
                                                        </a>
                                                </li>
                                                <li
                                                        class="{{ request()->routeIs('admin.medicine-orders.*') ? 'active' : '' }}">

                                                        <a href="{{ route('admin.medicine-orders.index') }}">

                                                                <i class="ti ti-shopping-cart fs-16 me-2"></i>

                                                                <span>Medicine Orders</span>

                                                        </a>

                                                </li>
                                                <li class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">

                                                        <a href="{{ route('admin.doctors.index') }}">

                                                                <i class="ti ti-stethoscope fs-16 me-2"></i>

                                                                <span>Doctors</span>

                                                        </a>

                                                </li>

                                                <li class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.coupons.index') }}">
                                                                <i class="ti ti-ticket fs-16 me-2"></i>
                                                                <span>Coupons</span>
                                                        </a>
                                                </li>

                                        </ul>
                                </li>
                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Customer Management</h6>

                                        <ul>

                                                <li
                                                        class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.customers.index') }}">
                                                                <i class="ti ti-building-hospital fs-16 me-2"></i>
                                                                <span>Customers</span>
                                                        </a>
                                                </li>



                                        </ul>
                                </li>


                                <li class="submenu-open">
                                        <h6 class="submenu-hdr">Settings</h6>
                                        <ul>
                                                <li
                                                        class="{{ request()->routeIs('admin.settings.company.*') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.settings.company') }}">
                                                                <i class="ti ti-address-book fs-16 me-2"></i>
                                                                <span>Settings</span>
                                                        </a>
                                                </li>
                                                <li>
                                                        <a href="{{ route('admin.logout') }}"
                                                                class="{{ Request::is('signin') ? 'active' : '' }}"><i
                                                                        class="ti ti-logout fs-16 me-2"
                                                                        style="color: red;"></i><span
                                                                        style="color: red;">Logout</span>
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                        </ul>
                </div>
        </div>
</div>