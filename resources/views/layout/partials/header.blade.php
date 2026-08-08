@if(!Route::is(['pos', 'pos-2', 'pos-3', 'pos-4', 'pos-5']))

    @php

        /*
        |--------------------------------------------------------------------------
        | Detect Current Panel
        |--------------------------------------------------------------------------
        */

        $isHospital = Auth::guard('hospital')->check();


        /*
        |--------------------------------------------------------------------------
        | Hospital User
        |--------------------------------------------------------------------------
        */

        if ($isHospital) {

            $headerUser = Auth::guard('hospital')->user();

            $headerName =
                $headerUser->hospital_name
                ?? $headerUser->name
                ?? 'Hospital';

            $headerHome =
                route('hospital.dashboard');

            $headerProfile =
                route('hospital.profile');


        /*
        |--------------------------------------------------------------------------
        | Admin User
        |--------------------------------------------------------------------------
        */

        } else {

            $headerUser = Auth::guard('admin')->user();

            $headerName =
                $headerUser->name
                ?? 'Admin';

            $headerHome =
                url('admin/dashboard');

            $headerProfile = '#';
        }

    @endphp


    <div class="header">

        <div class="main-header">


            <!-- Logo -->
            <div class="header-left active">


                <!-- Normal Logo -->
                <a href="{{ $headerHome }}"
                   class="logo logo-normal d-flex align-items-center justify-content-center w-100">

                    <img
                        src="{{ asset($site->site_logo) }}"
                        alt="Logo"
                        style="width:100px; margin-right:-10px;"
                    >

                </a>


                <!-- White Logo -->
                <a href="{{ $headerHome }}"
                   class="logo logo-white">

                    <img
                        src="{{ asset($site->site_logo) }}"
                        alt="Logo"
                        style="width:100px; margin-right:-10px;"
                    >

                </a>


                <!-- Small Logo -->
                <a href="{{ $headerHome }}"
                   class="logo-small">

                    <img
                        src="{{ asset($site->site_logo) }}"
                        alt="Logo"
                        style="width:100px; margin-right:-10px;"
                    >

                </a>

            </div>
            <!-- /Logo -->



            <!-- Mobile Button -->
            <a id="mobile_btn"
               class="mobile_btn"
               href="#sidebar">

                <span class="bar-icon">

                    <span></span>
                    <span></span>
                    <span></span>

                </span>

            </a>
            <!-- /Mobile Button -->



            <!-- Header Menu -->
            <ul class="nav user-menu">


                <!-- Search -->
                <li class="nav-item nav-searchinputs">

                </li>
                <!-- /Search -->



                <!-- User Profile -->
                <li class="nav-item dropdown has-arrow main-drop profile-nav">


                    <!-- Profile Icon -->
                    <a href="javascript:void(0);"
                       class="nav-link userset"
                       data-bs-toggle="dropdown">

                        <span class="user-info p-0">

                            <span class="user-letter">

                                <img
                                    src="{{ URL::asset('build/img/profiles/avator1.jpg') }}"
                                    alt="User"
                                    class="img-fluid"
                                >

                            </span>

                        </span>

                    </a>
                    <!-- /Profile Icon -->



                    <!-- Dropdown -->
                    <div class="dropdown-menu menu-drop-user">


                        <!-- User Information -->
                        <div class="profileset d-flex align-items-center">


                            <span class="user-img me-2">

                                <img
                                    src="{{ URL::asset('build/img/profiles/avator1.jpg') }}"
                                    alt="User"
                                    class="img-fluid"
                                >

                            </span>


                            <div>

                                <p class="mb-0">
                                    {{ $headerName }}
                                </p>

                                @if($isHospital)

                                    <small class="text-muted">
                                        Hospital
                                    </small>

                                @else

                                    <small class="text-muted">
                                        Administrator
                                    </small>

                                @endif

                            </div>


                        </div>
                        <!-- /User Information -->


                        <hr class="my-2">



                        <!-- Hospital Menu -->
                        @if($isHospital)


                            <!-- Profile -->
                            <a class="dropdown-item"
                               href="{{ $headerProfile }}">

                                <i class="ti ti-user-circle me-2"></i>

                                My Profile

                            </a>


                            <hr class="my-2">


                            <!-- Hospital Logout -->
                            <form
                                action="{{ route('hospital.logout') }}"
                                method="POST"
                                class="m-0"
                            >

                                @csrf


                                <button
                                    type="submit"
                                    class="dropdown-item logout pb-0 border-0 bg-transparent w-100 text-start"
                                >

                                    <i class="ti ti-logout me-2"></i>

                                    Logout

                                </button>

                            </form>



                        <!-- Admin Menu -->
                        @else


                            <!-- Admin Logout -->
                            <a
                                class="dropdown-item logout pb-0"
                                href="{{ route('admin.logout') }}"
                            >

                                <i class="ti ti-logout me-2"></i>

                                Logout

                            </a>


                        @endif


                    </div>
                    <!-- /Dropdown -->


                </li>
                <!-- /User Profile -->


            </ul>
            <!-- /Header Menu -->



            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">


                <a
                    href="javascript:void(0);"
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >

                    <i class="fa fa-ellipsis-v"></i>

                </a>



                <div class="dropdown-menu dropdown-menu-right">


                    @if($isHospital)


                        <!-- Hospital Profile -->
                        <a
                            class="dropdown-item"
                            href="{{ route('hospital.profile') }}"
                        >

                            My Profile

                        </a>


                        <!-- Hospital Logout -->
                        <form
                            action="{{ route('hospital.logout') }}"
                            method="POST"
                            class="m-0"
                        >

                            @csrf


                            <button
                                type="submit"
                                class="dropdown-item border-0 bg-transparent w-100 text-start"
                            >

                                Logout

                            </button>

                        </form>


                    @else


                        <!-- Admin Logout -->
                        <a
                            class="dropdown-item"
                            href="{{ route('admin.logout') }}"
                        >

                            Logout

                        </a>


                    @endif


                </div>


            </div>
            <!-- /Mobile Menu -->


        </div>

    </div>

@endif