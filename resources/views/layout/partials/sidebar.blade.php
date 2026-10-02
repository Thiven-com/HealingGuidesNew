<!-- Sidebar -->
<div class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo active">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-normal d-flex align-items-center"
            style="width:150px;height:50px;">
            <img src="{{ asset($site->site_logo) }}" alt="Logo"
                style="max-width:100%;max-height:45px;width:auto;height:auto;object-fit:contain;">
        </a>

        <a href="{{ route('admin.dashboard') }}" class="logo logo-white">
            <img src="{{ asset($site->site_logo) }}" alt="Logo">
        </a>

        <a href="{{ route('admin.dashboard') }}" class="logo-small">
            <img src="{{ asset($site->site_logo) }}" alt="Logo">
        </a>

        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->


    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            <ul>

                {{-- Dashboard --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Dashboard</h6>
                    <ul>
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="ti ti-dashboard fs-16 me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- Customer Management --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Customer Management</h6>

                    <ul>
                        <li class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.customers.index') }}">
                                <i class="ti ti-users fs-16 me-2"></i>
                                <span>Customers</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.patient-medical-reports.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.patient-medical-reports.index') }}">
                                <i class="ti ti-report-medical fs-16 me-2"></i>
                                <span>Medical Reports</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.insurances.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.insurances.all') }}">
                                <i class="ti ti-shield-check fs-16 me-2"></i>
                                <span>Insurances</span>
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- Hospital Management --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Hospital Management</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.hospitals.index') }}">
                                <i class="ti ti-building-hospital fs-16 me-2"></i>
                                <span>Hospitals</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.doctors.index') }}">
                                <i class="ti ti-stethoscope fs-16 me-2"></i>
                                <span>Doctors</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.specialization-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.specialization-categories.index') }}">
                                <i class="ti ti-category fs-16 me-2"></i>
                                <span>Specialization Categories</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.specializations.index') }}">
                                <i class="ti ti-stethoscope fs-16 me-2"></i>
                                <span>Specializations</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.procedures.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.procedures.index') }}">
                                <i class="ti ti-clipboard-list fs-16 me-2"></i>
                                <span>Procedures</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.surgeries.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.surgeries.index') }}">
                                <i class="ti ti-heart-rate-monitor fs-16 me-2"></i>
                                <span>Surgeries</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.facilities.index') }}">
                                <i class="ti ti-building-community fs-16 me-2"></i>
                                <span>Facilities</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.tieups.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tieups.index') }}">
                                <i class="ti ti-link fs-16 me-2"></i>
                                <span>Tieups</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.book-admissions.index.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.book-admissions.index') }}">
                                <i class="ti ti-clipboard-plus fs-16 me-2"></i>
                                <span>Admission Requests</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.health-insurance-providers.index*') ? 'active' : '' }}">
                            <a href="{{ route('admin.health-insurance-providers.index') }}">
                                <i class="ti ti-shield-plus fs-16 me-2"></i>
                                <span>Health Insurance Providers</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.appointments.index') }}">
                                <i class="ti ti-calendar-check fs-16 me-2"></i>
                                <span>Doctor Appointments</span>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- Health Services --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Health Services</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.healthcheckups.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.healthcheckups.index') }}">
                                <i class="ti ti-heartbeat fs-16 me-2"></i>
                                <span>Health Checkups</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.health-checkup-tests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.health-checkup-tests.index') }}">
                                <i class="ti ti-test-pipe fs-16 me-2"></i>
                                <span>Checkup Tests</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.health-checkup-packages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.health-checkup-packages.index') }}">
                                <i class="ti ti-package fs-16 me-2"></i>
                                <span>Checkup Packages</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.packages.index') }}">
                                <i class="ti ti-box fs-16 me-2"></i>
                                <span>Packages</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.family-member-health-checkups.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.family-member-health-checkups.index') }}">
                                <i class="ti ti-users fs-16 me-2"></i>
                                <span>Family Health Checkups</span>
                            </a>
                        </li>

                        @php
    $isCategoryPage = request()->routeIs('admin.home-visit-service-categories.*');
    $isServicePage = request()->routeIs('admin.home-visit-services.*');
    $homeVisitOpen = $isCategoryPage || $isServicePage;
@endphp

<li class="submenu {{ $homeVisitOpen ? 'subdrop' : '' }}">

    <a href="javascript:void(0);">
        <i class="ti ti-home-heart fs-16 me-2"></i>
        <span>Home Visit Services</span>
        <span class="menu-arrow"></span>
    </a>

    <ul style="{{ $homeVisitOpen ? 'display: block;' : 'display: none;' }}">

        <li class="{{ $isCategoryPage ? 'active' : '' }}">
            <a href="{{ route('admin.home-visit-service-categories.index') }}">
                Categories
            </a>
        </li>

        <li class="{{ $isServicePage ? 'active' : '' }}">
            <a href="{{ route('admin.home-visit-services.index') }}">
                Services
            </a>
        </li>

    </ul>

</li>

                    </ul>
                </li>


                {{-- Pharmacy --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Pharmacy</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.medicines.index') }}">
                                <i class="ti ti-pill fs-16 me-2"></i>
                                <span>Medicines</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.medicine-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.medicine-categories.index') }}">
                                <i class="ti ti-category fs-16 me-2"></i>
                                <span>Medicine Categories</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.medicine-orders.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.medicine-orders.index') }}">
                                <i class="ti ti-shopping-cart fs-16 me-2"></i>
                                <span>Medicine Orders</span>
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


                {{-- Diagnostics --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Diagnostics</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.diagnostics.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.diagnostics.index') }}">
                                <i class="ti ti-microscope fs-16 me-2"></i>
                                <span>Diagnostics</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.lab-tests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.lab-tests.index') }}">
                                <i class="ti ti-test-pipe fs-16 me-2"></i>
                                <span>Lab Tests</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.lab-tests-bookings.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.lab-tests-bookings.index') }}">
                                <i class="ti ti-calendar-event fs-16 me-2"></i>
                                <span>Lab Test Bookings</span>
                            </a>
                        </li>

                    </ul>
                </li>
                 <li class="submenu-open">
                    <h6 class="submenu-hdr">Surgery Quotation</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.surgery-quotation-requests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.surgery-quotation-requests.index') }}">
                                <i class="ti ti-receipt fs-16 me-2"></i>
                                <span>Quotation Requests</span>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- Ambulance --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Ambulance</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.ambulance-types.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.ambulance-types.index') }}">
                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                <span>Ambulance Types</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.ambulances.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.ambulances.index') }}">
                                <i class="ti ti-ambulance fs-16 me-2"></i>
                                <span>Ambulances</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.ambulance-bookings.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.ambulance-bookings.index') }}">
                                <i class="ti ti-map-pin fs-16 me-2"></i>
                                <span>Ambulance Requests</span>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- Marketing --}}
                {{-- <li class="submenu-open">
                    <h6 class="submenu-hdr">Marketing</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.marketing-leads.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.marketing-leads.all') }}">
                                <i class="ti ti-bell fs-16 me-2"></i>
                                <span>Marketing Leads</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.marketing-staff.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.marketing-staff.all') }}">
                                <i class="ti ti-user-check fs-16 me-2"></i>
                                <span>Marketing Staff</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.membership-registrations.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.membership-registrations.all') }}">
                                <i class="ti ti-users fs-16 me-2"></i>
                                <span>Memberships</span>
                            </a>
                        </li>

                    </ul>
                </li> --}}


                {{-- Settings --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>

                    <ul>

                        <li class="{{ request()->routeIs('admin.settings.company.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.company') }}">
                                <i class="ti ti-settings fs-16 me-2"></i>
                                <span>Settings</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.logout') }}">
                                <i class="ti ti-logout fs-16 me-2 text-danger"></i>
                                <span class="text-danger">Logout</span>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>

        </div>
    </div>
</div>
<!-- /Sidebar -->