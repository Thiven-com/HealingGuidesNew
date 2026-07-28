<?php $page = 'signin-2'; ?>
@extends('layout.mainlayout')

@section('content')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F4F7FC;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .reset-card {
            width: 460px;
            max-width: 100%;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, .05),
                0 25px 60px rgba(0, 0, 0, .08);
        }

        .reset-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 5px;
            background: #4F46E5;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            height: 75px;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h2 {
            color: #111827;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .heading p {
            color: #6B7280;
            font-size: 15px;
            line-height: 24px;
        }

        .form-label {
            display: block;
            color: #374151;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .pass-group {
            position: relative;
            margin-bottom: 20px;
        }

        .pass-group .form-control {
            height: 56px;
            border: 1px solid #D1D5DB;
            border-radius: 12px;
            background: #FFFFFF;
            color: #111827;
            padding-left: 16px;
            padding-right: 50px;
            font-size: 15px;
            transition: .3s;
        }

        .pass-group .form-control::placeholder {
            color: #9CA3AF;
        }

        .pass-group .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
            background: #FFFFFF;
            color: #111827;
        }

        .pass-group .toggle-password {
            position: absolute;
            right: 18px;
            top: 18px;
            cursor: pointer;
            color: #9CA3AF;
            font-size: 20px;
            transition: .3s;
        }

        .pass-group .toggle-password:hover {
            color: #4F46E5;
        }

        .btn-reset {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 12px;
            background: #4F46E5;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .btn-reset:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, .20);
        }

        .btn-reset:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .back-login {
            text-align: center;
            margin-top: 25px;
        }

        .back-login a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .back-login a:hover {
            color: #4338CA;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .text-danger {
            font-size: 13px;
        }

        .invalid-feedback {
            display: block;
            margin-top: 5px;
        }

        @media(max-width:576px) {

            .login-wrapper {
                padding: 20px;
            }

            .reset-card {
                padding: 30px 22px;
                border-radius: 18px;
            }

            .logo img {
                height: 60px;
            }

            .heading h2 {
                font-size: 24px;
            }

            .heading p {
                font-size: 14px;
            }

        }
    </style>

    <div class="login-wrapper">

        <div class="reset-card">

            <div class="logo">
                <img src="{{ asset($site->site_logo) }}" alt="Logo">
            </div>

            <div class="heading">
                <h2>Reset Password 🔒</h2>
                <p>Create a new secure password for your account.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.resetOtp') }}">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label class="form-label">New Password</label>

                    <div class="pass-group">

                        <input type="password" name="password"
                            class="form-control pass-input @error('password') is-invalid @enderror"
                            placeholder="Enter New Password" required>

                        <span class="ti ti-eye-off toggle-password"></span>

                    </div>

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Confirm Password</label>

                    <div class="pass-group">

                        <input type="password" name="password_confirmation" class="form-control pass-input"
                            placeholder="Confirm Password" required>

                        <span class="ti ti-eye-off toggle-password"></span>

                    </div>

                </div>

                <button type="submit" class="btn-reset">
                    Reset Password
                </button>

                <div class="back-login">

                    <a href="{{ route('admin.login') }}">
                        <i class="ti ti-arrow-left"></i>
                        Back to Login
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection