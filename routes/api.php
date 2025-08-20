<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,CustomerApiController,PlanApiController};
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::namespace('Api')->group(function () {

    /**
     * Public routes can be accessed without login
     */
    Route::post('login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

    Route::get('get-countries', [CommonController::class, 'getCountries']);
    Route::get('get-states', [CommonController::class, 'getStates']);
    Route::get('get-city', [CommonController::class, 'getCity']);
    Route::get('get-car-brand', [CommonController::class, 'getCarBrand']);
    Route::get('get-car-model', [CommonController::class, 'getCarModel']);
    Route::get('get-banner', [CommonController::class, 'getBanner']);

    Route::post('/get-app-info-data',[AuthController::class, 'getAppInfoData']);


    /**
     * Protected routes requires login to access
     */
    Route::middleware('auth:api')->group(function () {
        // logout user
        Route::get('/logout', [AuthController::class, 'logout']);

        Route::get('/get-car-wise-plan', [PlanApiController::class, 'getCarWisePlan']);

        Route::get('/get-customer-detail', [SalesmenApiController::class, 'getCustomerDetail']);
        

        // add firebase token after user login
        Route::post('add-firebase-token', [UserApiController::class, 'add_firebase_token']);
        
        // Add Notification Remainder To User Profile
        Route::post('add-notification-remainder', [UserApiController::class, 'add_remainder']);

        // Login user profile change password
        Route::post('change-password', [AuthController::class, 'changePassword']);
        //Customer Api
        Route::get('/get-customer-home-page', [CustomerApiController::class, 'getCustomerHomePage']);
        Route::get('/get-customer-product', [CustomerApiController::class, 'getCustomerProduct']);
        Route::get('/edit-customer', [CustomerApiController::class, 'editCustomer']);
    });


    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/login', function () {
        $this->response_json['message'] = 'Unauthorized';
        $this->response_json['status'] = 0;
        return response()->json($this->response_json, 403);
    })->name('login');


});
