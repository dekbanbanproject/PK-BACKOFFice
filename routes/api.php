<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//********************* */ AuthenMobile  ***********************************
Route::get('/test',function(Request $request){
    return 'Authenticated';
});
Route::match(['get','post'],'gleave_register',[App\Http\Controllers\Api\MobileController::class, 'gleave_register'])->name('mo.gleave_register');//
Route::match(['get','post'],'getfire/{firenum}',[App\Http\Controllers\Api\MobileController::class, 'getfire'])->name('mo.getfire');//

Route::match(['get','post'],'getmobile',[App\Http\Controllers\AuthenmobileController::class, 'getmobile'])->name('mo.getmobile');//
Route::match(['get','post'],'getmobile_api',[App\Http\Controllers\AuthenmobileController::class, 'getmobile_api'])->name('mo.getmobile_api');//

Route::get('authen_spsch', [App\Http\Controllers\ApiController::class, 'authen_spsch'])->name('app.authen_spsch');
Route::get('pull_hosapi', [App\Http\Controllers\ApiController::class, 'pull_hosapi'])->name('app.pull_hosapi');
Route::get('fdh_mini_auth', [App\Http\Controllers\ApiController::class, 'fdh_mini_auth'])->name('app.fdh_mini_auth');
Route::get('fdh_mini_pullhosinv', [App\Http\Controllers\ApiController::class, 'fdh_mini_pullhosinv'])->name('app.fdh_mini_pullhosinv');
Route::get('fdh_minipullhosnoinv', [App\Http\Controllers\ApiController::class, 'fdh_minipullhosnoinv'])->name('app.fdh_minipullhosnoinv');
Route::get('fdh_mini_pidsit', [App\Http\Controllers\ApiController::class, 'fdh_mini_pidsit'])->name('app.fdh_mini_pidsit');
Route::get('fdh_mini_pullbookid', [App\Http\Controllers\ApiController::class, 'fdh_mini_pullbookid'])->name('app.fdh_mini_pullbookid');

Route::get('fdh_countvn', [App\Http\Controllers\ApiController::class, 'fdh_countvn'])->name('app.fdh_countvn');
Route::get('fdh_sumincome', [App\Http\Controllers\ApiController::class, 'fdh_sumincome'])->name('app.fdh_sumincome');
Route::get('fdh_countpidsit', [App\Http\Controllers\ApiController::class, 'fdh_countpidsit'])->name('app.fdh_countpidsit');
Route::get('fdh_countbookid', [App\Http\Controllers\ApiController::class, 'fdh_countbookid'])->name('app.fdh_countbookid');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('authencode', [App\Http\Controllers\AuthencodeController::class, 'authencode'])->name('authencode');
Route::get('smartcard_readonly', [App\Http\Controllers\ApiController::class, 'smartcard_readonly'])->name('smartcard_readonly');
Route::get('patient_readonly', [App\Http\Controllers\ApiController::class, 'patient_readonly'])->name('patient_readonly');
Route::get('ovst_key', [App\Http\Controllers\ApiController::class, 'ovst_key'])->name('ovst_key');
// Route::match(['get','post'],'getfire/{firenum}',[App\Http\Controllers\ApiController::class, 'getfire'])->name('mo.getfire');//
Route::get('home_rpst', [App\Http\Controllers\ApiController::class, 'home_rpst'])->name('home_rpst');

Route::get('pimc', [App\Http\Controllers\ApiController::class, 'pimc'])->name('pimc');
Route::get('adp', [App\Http\Controllers\ApiController::class, 'adp'])->name('adp');
Route::get('ucep', [App\Http\Controllers\ApiController::class, 'ucep'])->name('ucep');