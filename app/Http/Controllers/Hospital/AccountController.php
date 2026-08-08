<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        if (Auth::guard('hospital')->check()) {
            return redirect()->route('hospital.dashboard');
        }

        return view('hospital.auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Send OTP
    |--------------------------------------------------------------------------
    */

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/'
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Find Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'mobile',
            $request->mobile
        )->first();


        if (!$hospital) {
            return back()
                ->with('error', 'Mobile Number Not Registered')
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        if (!$hospital->status) {
            return back()
                ->with(
                    'error',
                    'Your Hospital Account Is Inactive. Please Contact Administrator.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        // For development
        $otp = 1234;

        // For production:
        // $otp = random_int(1000, 9999);


        /*
        |--------------------------------------------------------------------------
        | Save OTP
        |--------------------------------------------------------------------------
        */

        $hospital->otp = $otp;
        $hospital->save();


        /*
        |--------------------------------------------------------------------------
        | Store Mobile Temporarily
        |--------------------------------------------------------------------------
        */

        session([
            'hospital_otp_mobile' => $hospital->mobile
        ]);


        /*
        |--------------------------------------------------------------------------
        | Send SMS
        |--------------------------------------------------------------------------
        |
        | Add your MSG91 / SMS service here.
        |
        */


        return redirect()
            ->route('hospital.verify-otp')
            ->with(
                'success',
                'OTP Sent Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OTP Verification Page
    |--------------------------------------------------------------------------
    */

    public function verifyOtpPage()
    {
        /*
        |--------------------------------------------------------------------------
        | Already Logged In
        |--------------------------------------------------------------------------
        */

        if (Auth::guard('hospital')->check()) {
            return redirect()
                ->route('hospital.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Check Login Mobile
        |--------------------------------------------------------------------------
        */

        $mobile = session('hospital_otp_mobile');


        if (!$mobile) {
            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Please Enter Your Mobile Number'
                );
        }


        return view(
            'hospital.auth.verify-otp',
            compact('mobile')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify OTP
    |--------------------------------------------------------------------------
    */

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => [
                'required',
                'digits:4'
            ],
        ]);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Mobile From Session
        |--------------------------------------------------------------------------
        */

        $mobile = session('hospital_otp_mobile');


        if (!$mobile) {
            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Login Session Expired. Please Login Again.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Find Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'mobile',
            $mobile
        )->first();


        if (!$hospital) {

            session()->forget(
                'hospital_otp_mobile'
            );

            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Hospital Account Not Found'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        if (!$hospital->status) {

            session()->forget(
                'hospital_otp_mobile'
            );

            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Your Hospital Account Is Inactive'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check OTP
        |--------------------------------------------------------------------------
        */

        if (
            (string) $hospital->otp !==
            (string) $request->otp
        ) {

            return back()->with(
                'error',
                'Invalid OTP'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Clear OTP
        |--------------------------------------------------------------------------
        */

        $hospital->otp = null;
        $hospital->save();


        /*
        |--------------------------------------------------------------------------
        | Login Hospital
        |--------------------------------------------------------------------------
        */

        Auth::guard('hospital')->login(
            $hospital
        );


        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Remove Temporary OTP Session
        |--------------------------------------------------------------------------
        */

        session()->forget(
            'hospital_otp_mobile'
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hospital.dashboard')
            ->with(
                'success',
                'Login Successful'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Resend OTP
    |--------------------------------------------------------------------------
    */

    public function resendOtp(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Mobile
        |--------------------------------------------------------------------------
        */

        $mobile = session(
            'hospital_otp_mobile'
        );


        if (!$mobile) {
            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Login Session Expired. Please Login Again.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Find Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'mobile',
            $mobile
        )->first();


        if (!$hospital) {

            session()->forget(
                'hospital_otp_mobile'
            );

            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Hospital Account Not Found'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        if (!$hospital->status) {

            return redirect()
                ->route('hospital.login')
                ->with(
                    'error',
                    'Your Hospital Account Is Inactive'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        // Development
        $otp = 1234;

        // Production
        // $otp = random_int(1000, 9999);


        /*
        |--------------------------------------------------------------------------
        | Update OTP
        |--------------------------------------------------------------------------
        */

        $hospital->otp = $otp;
        $hospital->save();


        /*
        |--------------------------------------------------------------------------
        | Send SMS
        |--------------------------------------------------------------------------
        |
        | Add MSG91 / SMS service here.
        |
        */


        return back()->with(
            'success',
            'OTP Resent Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logout Hospital Guard
        |--------------------------------------------------------------------------
        */

        Auth::guard('hospital')->logout();


        /*
        |--------------------------------------------------------------------------
        | Invalidate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | Redirect Login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hospital.login')
            ->with(
                'success',
                'Logged Out Successfully'
            );
    }
}