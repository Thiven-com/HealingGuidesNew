<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify OTP | Hospital Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f5f7fb;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .auth-card {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border-radius: 18px;
            padding: 38px;
            border: 1px solid #edf0f5;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.07);
        }

        .hospital-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf4ff;
            color: #0d6efd;
            font-size: 34px;
        }

        .auth-title {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            color: #20252b;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .mobile-number {
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            color: #20252b;
            margin-bottom: 28px;
        }

        .otp-wrapper {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .otp-input {
            width: 60px;
            height: 60px;
            text-align: center;
            border: 1px solid #dfe3e8;
            border-radius: 12px;
            font-size: 23px;
            font-weight: 700;
            outline: none;
            transition: .2s;
        }

        .otp-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .08);
        }

        .btn-verify {
            height: 52px;
            border: none;
            border-radius: 10px;
            background: #0d6efd;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-verify:hover {
            background: #0b5ed7;
            color: #ffffff;
        }

        .resend-section {
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }

        .btn-resend {
            border: none;
            background: transparent;
            color: #0d6efd;
            font-weight: 600;
            padding: 0;
        }

        .btn-resend:disabled {
            color: #adb5bd;
        }

        .change-mobile {
            margin-top: 18px;
            text-align: center;
        }

        .change-mobile a {
            font-size: 13px;
            color: #6c757d;
            text-decoration: none;
        }

        .change-mobile a:hover {
            color: #0d6efd;
        }

        .security-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #8a9099;
            font-size: 12px;
            margin-top: 25px;
        }

        .security-text i {
            color: #198754;
        }

        .alert {
            border: none;
            border-radius: 10px;
            font-size: 14px;
        }

        @media (max-width: 575px) {
            .auth-card {
                padding: 30px 20px;
            }

            .otp-wrapper {
                gap: 8px;
            }

            .otp-input {
                width: 55px;
                height: 55px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="hospital-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <h1 class="auth-title">
                Verify OTP
            </h1>

            <p class="auth-subtitle">
                We have sent a 4 digit OTP to
            </p>

            <div class="mobile-number">
                +91 {{ $mobile ?? session('hospital_otp_mobile') }}
            </div>


            {{-- Success Message --}}

            @if(session('success'))

                <div class="alert alert-success">

                    <i class="bi bi-check-circle me-1"></i>

                    {{ session('success') }}

                </div>

            @endif


            {{-- Error Message --}}

            @if(session('error'))

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-1"></i>

                    {{ session('error') }}

                </div>

            @endif


            {{-- Validation Error --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-1"></i>

                    {{ $errors->first() }}

                </div>

            @endif


            {{-- Verify Form --}}

            <form method="POST" action="{{ route('hospital.verify-otp.submit') }}" id="otpForm">

                @csrf


                {{-- Hidden Final OTP --}}

                <input type="hidden" name="otp" id="finalOtp">


                {{-- OTP Inputs --}}

                <div class="otp-wrapper">

                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code"
                        autofocus>

                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric">

                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric">

                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric">

                </div>


                <button type="submit" class="btn btn-verify w-100" id="verifyBtn">

                    <span id="verifyText">
                        Verify & Login
                    </span>

                    <span id="verifyLoader" class="d-none">

                        <span class="spinner-border spinner-border-sm me-2"></span>

                        Verifying...

                    </span>

                </button>

            </form>


            {{-- Resend OTP --}}

            <div class="resend-section">

                Didn't receive OTP?

                <form method="POST" action="{{ route('hospital.resend-otp') }}" class="d-inline">

                    @csrf

                    <button type="submit" class="btn-resend" id="resendBtn" disabled>

                        Resend OTP
                        <span id="timer">
                            (30s)
                        </span>

                    </button>

                </form>

            </div>


            {{-- Change Mobile --}}

            <div class="change-mobile">

                <a href="{{ route('hospital.login') }}">

                    <i class="bi bi-arrow-left me-1"></i>

                    Change Mobile Number

                </a>

            </div>


            <div class="security-text">

                <i class="bi bi-shield-check"></i>

                Secure OTP based authentication

            </div>

        </div>

    </div>


    <script>

        /*
        |--------------------------------------------------------------------------
        | OTP Inputs
        |--------------------------------------------------------------------------
        */

        const otpInputs =
            document.querySelectorAll('.otp-input');

        const finalOtp =
            document.getElementById('finalOtp');


        function updateOtp() {
            let otp = '';

            otpInputs.forEach(function (input) {
                otp += input.value;
            });

            finalOtp.value = otp;
        }


        otpInputs.forEach(function (input, index) {

            input.addEventListener('input', function () {

                this.value =
                    this.value.replace(/\D/g, '');

                if (
                    this.value &&
                    index < otpInputs.length - 1
                ) {
                    otpInputs[index + 1].focus();
                }

                updateOtp();

            });


            input.addEventListener('keydown', function (event) {

                if (
                    event.key === 'Backspace' &&
                    !this.value &&
                    index > 0
                ) {
                    otpInputs[index - 1].focus();
                }

            });


            /*
            |--------------------------------------------------------------------------
            | Paste OTP
            |--------------------------------------------------------------------------
            */

            input.addEventListener('paste', function (event) {

                event.preventDefault();

                const pasted =
                    event.clipboardData
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, 4);

                pasted
                    .split('')
                    .forEach(function (number, i) {

                        if (otpInputs[i]) {
                            otpInputs[i].value = number;
                        }

                    });

                updateOtp();

                if (pasted.length === 4) {
                    otpInputs[3].focus();
                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Form Submit
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('otpForm')
            .addEventListener('submit', function (event) {

                updateOtp();

                if (finalOtp.value.length !== 4) {

                    event.preventDefault();

                    alert('Please Enter Complete 4 Digit OTP');

                    return;
                }


                document
                    .getElementById('verifyText')
                    .classList
                    .add('d-none');


                document
                    .getElementById('verifyLoader')
                    .classList
                    .remove('d-none');


                document
                    .getElementById('verifyBtn')
                    .disabled = true;

            });


        /*
        |--------------------------------------------------------------------------
        | Resend Timer
        |--------------------------------------------------------------------------
        */

        let seconds = 30;

        const resendBtn =
            document.getElementById('resendBtn');

        const timer =
            document.getElementById('timer');


        const countdown = setInterval(function () {

            seconds--;

            timer.innerText =
                '(' + seconds + 's)';


            if (seconds <= 0) {

                clearInterval(countdown);

                resendBtn.disabled = false;

                timer.innerText = '';

            }

        }, 1000);

    </script>

</body>

</html>