@extends('layouts.app')


@section('title', 'Terms & Conditions | Healing Guides')

@section(
    'meta_description',
    'Terms and Conditions for Healing Guides Wellness Services and its healthcare applications.'
)


@push('styles')

<style>

    .terms-section {
        padding: 75px 0 90px;
        background: #faf9fc;
    }

    .terms-layout {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 30px;
        align-items: start;
    }


    /* =========================================================
       SIDEBAR
    ========================================================= */

    .terms-sidebar {
        position: sticky;
        top: 110px;

        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;

        padding: 22px;

        box-shadow:
            0 12px 35px
            rgba(57,27,75,.06);
    }

    .terms-sidebar h5 {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 17px;
    }

    .terms-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .terms-sidebar li {
        margin-bottom: 4px;
    }

    .terms-sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;

        color: #6a6470;

        padding: 10px 12px;

        border-radius: 9px;

        font-size: 13px;
        font-weight: 600;

        transition: .3s;
    }

    .terms-sidebar a i {
        width: 18px;
        color: #a59eaa;
        text-align: center;
    }

    .terms-sidebar a:hover {
        background: var(--primary-light);
        color: var(--primary);
    }

    .terms-sidebar a:hover i {
        color: var(--primary);
    }


    /* =========================================================
       CONTENT CARD
    ========================================================= */

    .terms-card {
        background: #fff;

        border: 1px solid var(--border);
        border-radius: 24px;

        padding: 42px;

        box-shadow:
            0 15px 45px
            rgba(50,24,65,.06);
    }


    /* =========================================================
       INTRO
    ========================================================= */

    .terms-intro {
        padding: 23px;

        border-radius: 16px;

        background:
            linear-gradient(
                135deg,
                #fbf6ff,
                #fff9f7
            );

        border: 1px solid #eee4f3;

        color: #68616f;

        line-height: 1.8;

        margin-bottom: 38px;
    }

    .terms-intro strong {
        color: var(--primary);
    }


    /* =========================================================
       TERMS ITEM
    ========================================================= */

    .terms-item {
        padding-bottom: 34px;
        margin-bottom: 34px;

        border-bottom:
            1px solid #eee9f1;

        scroll-margin-top: 120px;
    }

    .terms-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .terms-title {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 18px;
    }

    .terms-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;

        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--primary-light);
        color: var(--primary);

        font-size: 18px;
    }

    .terms-title h2 {
        font-size: 21px;
        font-weight: 800;
        margin: 0;
        color: var(--dark);
    }

    .terms-item p {
        color: #716b77;
        font-size: 15px;
        line-height: 1.85;
        margin-bottom: 14px;
    }

    .terms-item p:last-child {
        margin-bottom: 0;
    }

    .terms-item strong {
        color: #fff;
    }


    /* =========================================================
       LIST
    ========================================================= */

    .terms-list {
        list-style: none;
        padding: 0;
        margin: 18px 0 0;
    }

    .terms-list li {
        display: flex;
        align-items: flex-start;
        gap: 11px;

        color: #68616f;

        font-size: 14px;
        line-height: 1.7;

        margin-bottom: 11px;
    }

    .terms-list li i {
        color: var(--success);
        margin-top: 5px;
        font-size: 13px;
    }


    /* =========================================================
       APPLICATIONS
    ========================================================= */

    .terms-app-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);

        gap: 13px;

        margin-top: 20px;
    }

    .terms-app-item {
        display: flex;
        align-items: center;
        gap: 13px;

        padding: 15px;

        background: #fff;

        border: 1px solid #eee8f3;
        border-radius: 13px;

        color: #58515e;

        font-size: 14px;
        font-weight: 650;
    }

    .terms-app-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        color: #fff;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                #a52bd0
            );
    }

    .terms-app-item:nth-child(even)
    .terms-app-icon {
        background:
            linear-gradient(
                135deg,
                #ff8d21,
                #ff5138
            );
    }


    /* =========================================================
       NOTICE
    ========================================================= */

    .terms-note {
        position: relative;
        overflow: hidden;

        margin-top: 20px;

        padding: 20px;

        background: #fbf7fd;

        border: 1px solid #eee2f5;
        border-radius: 14px;

        color: #6d6573;

        font-size: 14px;
        line-height: 1.75;
    }

    .terms-note::before {
        content: "";

        position: absolute;

        left: 0;
        top: 0;
        bottom: 0;

        width: 4px;

        background:
            linear-gradient(
                var(--primary),
                var(--secondary)
            );
    }

    .terms-warning {
        margin-top: 20px;

        padding: 20px;

        background: #fff9f4;

        border: 1px solid #f8e3d5;
        border-radius: 14px;

        color: #776259;

        font-size: 14px;
        line-height: 1.75;
    }

    .terms-warning i {
        color: var(--secondary);
        margin-right: 8px;
    }


    /* =========================================================
       CONTACT
    ========================================================= */

    .terms-contact {
        margin-top: 22px;

        border-radius: 20px;

        padding: 28px;

        color: #fff;

        position: relative;
        overflow: hidden;

        background:
            linear-gradient(
                145deg,
                #5d0b95,
                #8116ad
            );
    }

    .terms-contact::after {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        border-radius: 50%;

        background:
            rgba(255,255,255,.05);

        right: -80px;
        bottom: -120px;
    }

    .terms-contact-item {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        gap: 13px;

        margin-bottom: 18px;
    }

    .terms-contact-item:last-child {
        margin-bottom: 0;
    }

    .terms-contact-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background:
            rgba(255,255,255,.13);
    }

    .terms-contact small {
        display: block;

        color:
            rgba(255,255,255,.65);

        margin-bottom: 2px;
    }

    .terms-contact strong {
        font-size: 14px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 991px) {

        .terms-layout {
            grid-template-columns: 1fr;
        }

        .terms-sidebar {
            position: static;
        }

        .terms-sidebar ul {
            display: flex;
            overflow-x: auto;
            gap: 5px;
            padding-bottom: 5px;
        }

        .terms-sidebar li {
            min-width: max-content;
        }

    }

    @media(max-width: 767px) {

        .terms-section {
            padding: 45px 0 65px;
        }

        .terms-card {
            padding: 27px 20px;
            border-radius: 18px;
        }

        .terms-app-grid {
            grid-template-columns: 1fr;
        }

        .terms-title {
            align-items: flex-start;
        }

        .terms-title h2 {
            font-size: 19px;
            padding-top: 8px;
        }

    }

</style>

@endpush



@section('content')


{{-- ============================================================
     HERO
============================================================ --}}

<section class="page-hero">

    <div class="container">

        <div class="page-hero-content text-center">

            <div class="page-badge">

                <span></span>

                Legal Information

            </div>


            <h1>

                Terms &
                <span>Conditions</span>

            </h1>


            <p>

                Please read these Terms & Conditions carefully
                before accessing or using Healing Guides Wellness
                Services, our applications and healthcare platform.

            </p>


            <div class="mt-3 text-muted">

                <i class="fa-regular fa-calendar-check me-2"
                   style="color:var(--primary)"></i>

                Last Updated: August 2026

            </div>

        </div>

    </div>

</section>



{{-- ============================================================
     TERMS CONTENT
============================================================ --}}

<section class="terms-section">

    <div class="container">

        <div class="terms-layout">


            {{-- ====================================================
                 SIDEBAR
            ==================================================== --}}

            <aside class="terms-sidebar">

                <h5>

                    <i class="fa-solid fa-list me-2"
                       style="color:var(--primary)"></i>

                    Terms Contents

                </h5>


                <ul>

                    <li>
                        <a href="#acceptance">
                            <i class="fa-solid fa-circle-check"></i>
                            Acceptance
                        </a>
                    </li>

                    <li>
                        <a href="#services">
                            <i class="fa-solid fa-mobile-screen"></i>
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="#accounts">
                            <i class="fa-solid fa-user"></i>
                            User Accounts
                        </a>
                    </li>

                    <li>
                        <a href="#healthcare">
                            <i class="fa-solid fa-heart-pulse"></i>
                            Healthcare
                        </a>
                    </li>

                    <li>
                        <a href="#appointments">
                            <i class="fa-solid fa-calendar-check"></i>
                            Appointments
                        </a>
                    </li>

                    <li>
                        <a href="#medicines">
                            <i class="fa-solid fa-pills"></i>
                            Medicines
                        </a>
                    </li>

                    <li>
                        <a href="#diagnostics">
                            <i class="fa-solid fa-flask"></i>
                            Diagnostics
                        </a>
                    </li>

                    <li>
                        <a href="#ambulance">
                            <i class="fa-solid fa-truck-medical"></i>
                            Ambulance
                        </a>
                    </li>

                    <li>
                        <a href="#payments">
                            <i class="fa-solid fa-credit-card"></i>
                            Payments
                        </a>
                    </li>

                    <li>
                        <a href="#cancellation">
                            <i class="fa-solid fa-ban"></i>
                            Cancellations
                        </a>
                    </li>

                    <li>
                        <a href="#contact">
                            <i class="fa-solid fa-envelope"></i>
                            Contact
                        </a>
                    </li>

                </ul>

            </aside>



            {{-- ====================================================
                 MAIN CARD
            ==================================================== --}}

            <main class="terms-card">


                <div class="terms-intro">

                    These Terms & Conditions govern your access to and
                    use of the services provided through
                    <strong>Healing Guides Wellness Services</strong>,
                    including our website, mobile applications and
                    connected healthcare services.

                    By registering, accessing or using our services,
                    you agree to these Terms & Conditions.

                </div>



                {{-- 1. ACCEPTANCE --}}

                <div class="terms-item"
                     id="acceptance">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-file-signature"></i>

                        </div>

                        <h2>
                            1. Acceptance of Terms
                        </h2>

                    </div>


                    <p>

                        By accessing, registering for, downloading or
                        using any Healing Guides application or service,
                        you acknowledge that you have read and understood
                        these Terms & Conditions and agree to be bound by
                        them.

                    </p>


                    <p>

                        If you do not agree with these terms, you should
                        not use the platform or its services.

                    </p>

                </div>



                {{-- 2. SERVICES --}}

                <div class="terms-item"
                     id="services">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-layer-group"></i>

                        </div>

                        <h2>
                            2. Our Platform & Services
                        </h2>

                    </div>


                    <p>

                        Healing Guides provides a digital platform that
                        may connect customers with hospitals, doctors,
                        diagnostic providers, medicine services,
                        ambulance providers and other healthcare-related
                        service providers.

                    </p>


                    <div class="terms-app-grid">

                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            Customer Application

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-hospital"></i>
                            </div>

                            Hospital Application

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>

                            Doctor & Appointment Services

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-flask"></i>
                            </div>

                            Diagnostic Services

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-pills"></i>
                            </div>

                            Medicine Services

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-truck-medical"></i>
                            </div>

                            Ambulance Services

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-people-group"></i>
                            </div>

                            Marketing Application

                        </div>


                        <div class="terms-app-item">

                            <div class="terms-app-icon">
                                <i class="fa-solid fa-laptop-medical"></i>
                            </div>

                            Administrative Services

                        </div>

                    </div>

                </div>



                {{-- 3. ELIGIBILITY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-user-check"></i>

                        </div>

                        <h2>
                            3. Eligibility
                        </h2>

                    </div>


                    <p>

                        You must be legally capable of entering into
                        an agreement to create and operate an account
                        on the platform.

                    </p>


                    <p>

                        Where healthcare services are requested for a
                        minor or another family member, the person
                        submitting the information must have appropriate
                        authority to do so.

                    </p>

                </div>



                {{-- 4. ACCOUNTS --}}

                <div class="terms-item"
                     id="accounts">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-user-lock"></i>

                        </div>

                        <h2>
                            4. User Accounts
                        </h2>

                    </div>


                    <p>

                        Certain services require registration using
                        information such as your name, mobile number,
                        email address or other requested information.

                    </p>


                    <ul class="terms-list">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            You must provide accurate and current
                            information.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            You are responsible for activities performed
                            through your account.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            OTPs, passwords and other authentication
                            credentials must not be shared with
                            unauthorized persons.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            You should notify us if you believe your
                            account has been accessed without
                            authorization.
                        </li>

                    </ul>

                </div>



                {{-- 5. HEALTHCARE --}}

                <div class="terms-item"
                     id="healthcare">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-heart-pulse"></i>

                        </div>

                        <h2>
                            5. Healthcare Services
                        </h2>

                    </div>


                    <p>

                        Healing Guides provides technology that helps
                        users discover, request, book or manage
                        healthcare-related services.

                    </p>


                    <p>

                        Medical advice, diagnosis, treatment and other
                        professional healthcare decisions are the
                        responsibility of the relevant qualified
                        healthcare professional or healthcare provider.

                    </p>


                    <div class="terms-warning">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <strong>Important:</strong>

                        The platform should not be treated as a substitute
                        for emergency medical care. In a medical emergency,
                        seek appropriate emergency assistance immediately.

                    </div>

                </div>



                {{-- 6. APPOINTMENTS --}}

                <div class="terms-item"
                     id="appointments">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-calendar-check"></i>

                        </div>

                        <h2>
                            6. Doctor Appointments
                        </h2>

                    </div>


                    <p>

                        Users may be able to search for doctors and
                        request appointments through the platform.

                    </p>


                    <ul class="terms-list">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Appointment availability depends on the
                            relevant doctor or hospital.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Appointment times may be changed or cancelled
                            due to doctor or hospital availability.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Users should provide accurate information
                            while making an appointment.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Consultation fees may vary by doctor,
                            consultation type and healthcare provider.
                        </li>

                    </ul>

                </div>



                {{-- 7. MEDICINES --}}

                <div class="terms-item"
                     id="medicines">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-pills"></i>

                        </div>

                        <h2>
                            7. Medicine Orders
                        </h2>

                    </div>


                    <p>

                        Medicine availability, pricing and fulfilment
                        may depend on the hospital, pharmacy or other
                        authorized provider fulfilling the order.

                    </p>


                    <p>

                        Certain medicines may require a valid
                        prescription before an order can be accepted
                        or fulfilled.

                    </p>


                    <ul class="terms-list">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Medicine availability is not guaranteed.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Prices may vary between providers.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Prescription requirements must be followed.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            Orders may be rejected if fulfilment is not
                            possible.
                        </li>

                    </ul>

                </div>



                {{-- 8. DIAGNOSTICS --}}

                <div class="terms-item"
                     id="diagnostics">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-flask-vial"></i>

                        </div>

                        <h2>
                            8. Diagnostic & Laboratory Services
                        </h2>

                    </div>


                    <p>

                        Diagnostic test information, prices, home
                        collection availability, preparation instructions
                        and estimated report times may be provided by the
                        relevant diagnostic service provider.

                    </p>


                    <p>

                        Actual report delivery times may vary depending
                        on the test, sample condition, laboratory process
                        and other operational factors.

                    </p>

                </div>



                {{-- 9. AMBULANCE --}}

                <div class="terms-item"
                     id="ambulance">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-truck-medical"></i>

                        </div>

                        <h2>
                            9. Ambulance Services
                        </h2>

                    </div>


                    <p>

                        Ambulance bookings depend on vehicle availability,
                        service area, pickup location, traffic, driver
                        availability and other operational conditions.

                    </p>


                    <p>

                        Estimated arrival times, trip distances and fares
                        may change due to actual travel conditions.

                    </p>


                    <div class="terms-note">

                        Ambulance availability shown through the platform
                        does not guarantee that a particular ambulance
                        will remain available until the booking has been
                        accepted and assigned.

                    </div>

                </div>



                {{-- 10. PAYMENTS --}}

                <div class="terms-item"
                     id="payments">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-credit-card"></i>

                        </div>

                        <h2>
                            10. Payments
                        </h2>

                    </div>


                    <p>

                        Where online payments are available, transactions
                        may be processed through third-party payment
                        service providers.

                    </p>


                    <p>

                        Users are responsible for ensuring that payment
                        information provided to the payment service
                        provider is valid and authorized.

                    </p>


                    <p>

                        Payment status may depend on confirmation received
                        from the applicable payment provider, bank or
                        financial institution.

                    </p>

                </div>



                {{-- 11. FEES --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-indian-rupee-sign"></i>

                        </div>

                        <h2>
                            11. Prices, Fees & Charges
                        </h2>

                    </div>


                    <p>

                        Prices and fees displayed on the platform may
                        include consultation fees, medicine prices,
                        diagnostic charges, ambulance charges, delivery
                        fees, taxes or other applicable charges.

                    </p>


                    <p>

                        Final charges may depend on the selected service,
                        provider, distance, applicable taxes, discounts
                        and other relevant factors.

                    </p>

                </div>



                {{-- 12. CANCELLATIONS --}}

                <div class="terms-item"
                     id="cancellation">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-ban"></i>

                        </div>

                        <h2>
                            12. Cancellation, Rejection & Refunds
                        </h2>

                    </div>


                    <p>

                        Cancellation, rejection and refund eligibility
                        may vary depending on the type of service and
                        the stage at which the request is cancelled.

                    </p>


                    <p>

                        A doctor, hospital, diagnostic provider,
                        medicine provider or ambulance provider may
                        reject or cancel a request where the service
                        cannot reasonably be provided.

                    </p>


                    <p>

                        Where a refund is applicable, processing time
                        may depend on the payment provider, bank or
                        financial institution involved in the transaction.

                    </p>

                </div>



                {{-- 13. USER RESPONSIBILITY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-user-shield"></i>

                        </div>

                        <h2>
                            13. User Responsibilities
                        </h2>

                    </div>


                    <p>
                        Users agree not to misuse the platform.
                    </p>


                    <ul class="terms-list">

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Do not provide intentionally false or
                            misleading information.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Do not impersonate another person.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Do not attempt unauthorized access to accounts,
                            APIs, systems or data.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Do not interfere with platform operation or
                            security.
                        </li>

                        <li>
                            <i class="fa-solid fa-circle-check"></i>

                            Do not use the platform for unlawful,
                            fraudulent or abusive purposes.
                        </li>

                    </ul>

                </div>



                {{-- 14. HOSPITALS --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-hospital-user"></i>

                        </div>

                        <h2>
                            14. Hospitals & Service Providers
                        </h2>

                    </div>


                    <p>

                        Hospitals and other service providers using
                        Healing Guides are responsible for maintaining
                        accurate information regarding their services,
                        staff, doctors, fees, availability, medicines,
                        diagnostic services and ambulances.

                    </p>


                    <p>

                        Providers are responsible for maintaining
                        licenses, registrations, permissions and
                        professional requirements applicable to the
                        services they provide.

                    </p>

                </div>



                {{-- 15. MARKETING --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-people-group"></i>

                        </div>

                        <h2>
                            15. Marketing Application
                        </h2>

                    </div>


                    <p>

                        The Marketing Application is intended for
                        authorized staff and may be used for lead
                        management, field visits, follow-ups and
                        provider onboarding activities.

                    </p>


                    <p>

                        Marketing staff must use information available
                        through the application only for authorized
                        business purposes.

                    </p>

                </div>



                {{-- 16. PRIVACY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-shield-halved"></i>

                        </div>

                        <h2>
                            16. Privacy
                        </h2>

                    </div>


                    <p>

                        Use of personal information through Healing
                        Guides is also governed by our Privacy Policy.

                    </p>


                    <p>

                        Please review the

                        <a href="{{ route('privacy-policy') }}"
                           style="color:var(--primary);font-weight:700;">

                            Privacy Policy

                        </a>

                        to understand how information may be collected,
                        used, stored and shared.

                    </p>

                </div>



                {{-- 17. THIRD PARTY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-link"></i>

                        </div>

                        <h2>
                            17. Third-Party Services
                        </h2>

                    </div>


                    <p>

                        The platform may rely on third-party services
                        for payments, maps, cloud infrastructure,
                        notifications, SMS communication and other
                        technical functionality.

                    </p>


                    <p>

                        Use of third-party services may also be subject
                        to their respective terms and policies.

                    </p>

                </div>



                {{-- 18. INTELLECTUAL PROPERTY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-copyright"></i>

                        </div>

                        <h2>
                            18. Intellectual Property
                        </h2>

                    </div>


                    <p>

                        The Healing Guides name, platform design,
                        software, application interfaces, graphics,
                        branding and related materials may be protected
                        by applicable intellectual property rights.

                    </p>


                    <p>

                        Users may not copy, reproduce, modify,
                        distribute or commercially exploit protected
                        platform materials without appropriate
                        authorization.

                    </p>

                </div>



                {{-- 19. AVAILABILITY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-server"></i>

                        </div>

                        <h2>
                            19. Platform Availability
                        </h2>

                    </div>


                    <p>

                        We aim to maintain reliable access to the
                        platform, but uninterrupted or error-free
                        availability cannot be guaranteed.

                    </p>


                    <p>

                        Services may be temporarily unavailable because
                        of maintenance, technical issues, internet
                        connectivity, third-party services or events
                        outside reasonable control.

                    </p>

                </div>



                {{-- 20. LIABILITY --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-scale-balanced"></i>

                        </div>

                        <h2>
                            20. Limitation of Liability
                        </h2>

                    </div>


                    <p>

                        To the extent permitted by applicable law,
                        Healing Guides will not be responsible for
                        losses resulting solely from circumstances
                        outside its reasonable control, including
                        service-provider actions, third-party system
                        failures, network outages or incorrect
                        information provided by users or independent
                        providers.

                    </p>


                    <p>

                        Nothing in these Terms is intended to exclude
                        rights or liabilities that cannot legally be
                        excluded.

                    </p>

                </div>



                {{-- 21. SUSPENSION --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-user-slash"></i>

                        </div>

                        <h2>
                            21. Account Suspension & Termination
                        </h2>

                    </div>


                    <p>

                        Access to an account or service may be restricted
                        or suspended where reasonably necessary because
                        of suspected fraud, misuse, security concerns,
                        violation of these Terms or applicable legal
                        requirements.

                    </p>

                </div>



                {{-- 22. CHANGES --}}

                <div class="terms-item">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-file-circle-check"></i>

                        </div>

                        <h2>
                            22. Changes to These Terms
                        </h2>

                    </div>


                    <p>

                        We may update these Terms & Conditions from time
                        to time to reflect changes to our applications,
                        services, business practices or applicable
                        requirements.

                    </p>


                    <p>

                        Updated Terms will be published on this page
                        with a revised "Last Updated" date.

                    </p>

                </div>



                {{-- 23. CONTACT --}}

                <div class="terms-item"
                     id="contact">

                    <div class="terms-title">

                        <div class="terms-icon">

                            <i class="fa-solid fa-headset"></i>

                        </div>

                        <h2>
                            23. Contact Us
                        </h2>

                    </div>


                    <p>

                        If you have questions regarding these Terms &
                        Conditions, please contact us.

                    </p>


                    <div class="terms-contact">


                        <div class="terms-contact-item">

                            <div class="terms-contact-icon">

                                <i class="fa-solid fa-building"></i>

                            </div>

                            <div>

                                <small>Company</small>

                                <strong>
                                    Healing Guides Wellness Services
                                </strong>

                            </div>

                        </div>


                        <div class="terms-contact-item">

                            <div class="terms-contact-icon">

                                <i class="fa-solid fa-envelope"></i>

                            </div>

                            <div>

                                <small>Email Address</small>

                                <strong>
                                    info@healingguides.com
                                </strong>

                            </div>

                        </div>


                        <div class="terms-contact-item">

                            <div class="terms-contact-icon">

                                <i class="fa-solid fa-phone"></i>

                            </div>

                            <div>

                                <small>Phone Number</small>

                                <strong>
                                    +91 98765 43210
                                </strong>

                            </div>

                        </div>


                        <div class="terms-contact-item">

                            <div class="terms-contact-icon">

                                <i class="fa-solid fa-location-dot"></i>

                            </div>

                            <div>

                                <small>Registered Location</small>

                                <strong>
                                    H NO - 37 - 103/2
SREE COLONEY NEREDMET X ROADS
SECUNDERABAD. 50006. TELANGANA
                                </strong>

                            </div>

                        </div>


                    </div>

                </div>


            </main>

        </div>

    </div>

</section>


@endsection