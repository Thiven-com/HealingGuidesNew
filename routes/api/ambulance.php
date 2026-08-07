<?php



use App\Http\Controllers\AmbulanceApp\AccountController;
use App\Http\Controllers\AmbulanceApp\ProfileController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('login', [AccountController::class,'login']);
Route::post('verifyMobile', [AccountController::class,'verifyMobile']);
Route::post('resendOtp', [AccountController::class,'resendOtp']);


Route::group(['middleware' => ['ambulancetokenCheck']], function () {
    //Profile
    Route::get('profile', [ProfileController::class, 'profile']);
});