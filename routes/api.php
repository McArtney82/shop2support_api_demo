<?php

use App\Http\Controllers\BenificiaryController;
use App\Http\Controllers\RetailerController;
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

Route::group(['middleware' => 'client'], function () {

    //Route User Login, Register and Reset
    Route::post('/user/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/user/register', 'App\Http\Controllers\AuthController@register');

    //Retailer Routes
    Route::resource('retailer', RetailerController::class)->except([
        'index','create','edit'
    ]);


    Route::resource('benificiary', BenificiaryController::class)->except([
        'index','create','edit'
    ]);

    Route::get('/benificiary/{id}/retailers',
        [BenificiaryController::class,
            'getRetailersByBenificiaryId'
        ]
    );

    Route::post('benificiary/{benificiary}/retailers',
        [BenificiaryController::class,
            'addRetailers'
        ]
    )->name('benificiary.addRetailers');

});



