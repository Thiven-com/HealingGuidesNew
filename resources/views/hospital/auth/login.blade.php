<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hospital Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.07);
            border: 1px solid #edf0f5;
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
            margin-bottom: 7px;
        }

        .auth-subtitle {
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 8px;
        }

        .mobile-input {
            position: relative;
        }

        .mobile-input .input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 18px;
            z-index: 5;
        }

        .mobile-input .form-control {
            height: 52px;
            padding-left: 48px;
            border-radius: 10px;
            border: 1px solid #dfe3e8;
            font-size: 15px;
        }

        .mobile-input .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08);
        }

        .btn-login {
            height: 52px;
            border: none;
            border-radius: 10px;
            background: #0d6efd;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-login:hover {
            background: #0b5ed7;
            color: #ffffff;
        }

        .btn-login:disabled {
            opacity: .7;
        }

        .security-text {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            color: #8a9099;
            font-size: 12px;
            margin-top: 22px;
        }

        .security-text i {
            color: #198754;
        }

        .alert {
            border: 0;
            border-radius: 10px;
            font-size: 14px;
        }

        .footer-text {
            text-align: center;
            color: #9a9fa6;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 575px) {

            .auth-wrapper {
                padding: 20px 15px;
            }

            .auth-card {
                padding: 30px 22px;
            }

            .auth-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <!-- Hospital Icon -->

            <div class="hospital-icon">
                <i class="bi bi-hospital"></i>
            </div>


            <!-- Heading -->

            <h1 class="auth-title">
                Hospital Login
            </h1>

            <p class="auth-subtitle">
                Enter your registered mobile number to continue
            </p>


            <!-- Success -->

            @if(session('success'))

                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-1"></i>

                    {{ session('success') }}
                </div>

            @endif


            <!-- Error -->

            @if(session('error'))

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-1"></i>

                    {{ session('error') }}
                </div>

            @endif


            <!-- Validation Errors -->

            @if($errors->any())

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-1"></i>

                    {{ $errors->first() }}

                </div>

            @endif


            <!-- Login Form -->

            <form method="POST" action="{{ route('hospital.send-otp') }}" id="loginForm">

                @csrf


                <!-- Mobile -->

                <div class="mb-4">

                    <label class="form-label">
                        Mobile Number
                    </label>

                    <div class="mobile-input">

                        <i class="bi bi-phone input-icon"></i>

                        <input type="tel" name="mobile" id="mobile"
                            class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}"
                            placeholder="Enter 10 digit mobile number" maxlength="10" inputmode="numeric"
                            autocomplete="tel" required>

                    </div>

                    @error('mobile')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <!-- Submit -->

                <button type="submit" class="btn btn-login w-100" id="submitBtn">

                    <span id="buttonText">
                        Send OTP
                    </span>

                    <span id="buttonLoader" class="d-none">

                        <span class="spinner-border spinner-border-sm me-2"></span>

                        Sending OTP...

                    </span>

                </button>

            </form>


            <!-- Security -->

            <div class="security-text">

                <i class="bi bi-shield-check"></i>

                Secure OTP based login

            </div>


            <!-- Footer -->

            <div class="footer-text">
                Hospital Management Panel
            </div>

        </div>

    </div>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Allow Numbers Only
        |--------------------------------------------------------------------------
        */

        const mobile = document.getElementById('mobile');

        mobile.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 10);

        });


        /*
        |--------------------------------------------------------------------------
        | Submit Loader
        |--------------------------------------------------------------------------
        */

        const loginForm = document.getElementById('loginForm');

        loginForm.addEventListener('submit', function () {

            const button = document.getElementById('submitBtn');

            document
                .getElementById('buttonText')
                .classList
                .add('d-none');

            document
                .getElementById('buttonLoader')
                .classList
                .remove('d-none');

            button.disabled = true;

        });

    </script>

</body>

</html>