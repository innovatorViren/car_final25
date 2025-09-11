<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,CustomerApiController,PlanApiController,EmployeeApiController,TimeApiSlotController,OrderApiController};
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

        //admin Api
        Route::get('/get-customer-list', [CustomerApiController::class, 'getCustomerList']);
        Route::get('/get-customer-detail/{id}', [CustomerApiController::class, 'getCustomerDetail']);
        Route::get('/get-employee-list', [EmployeeApiController::class, 'getEmployeeList']);
        Route::get('/get-employee-detail/{id}', [EmployeeApiController::class, 'getEmployeeDetail']);

        //Customer Api
        Route::get('/get-car-wise-plan', [PlanApiController::class, 'getCarWisePlan']);
        Route::post('/add-customer-address', [CustomerApiController::class, 'addCustomerAddress']);
        Route::post('/default-customer-address', [CustomerApiController::class, 'defaultCustomerAddress']);
        Route::get('/get-customer-wise-address/{customer_id}', [CustomerApiController::class, 'getCustomerWiseAddress']);
        Route::post('/edit-customer-address', [CustomerApiController::class, 'editCustomerAddress']);
        Route::get('/get-frequency', [CommonController::class, 'getFrequency']);
        Route::get('/time-slots', [TimeApiSlotController::class, 'getSlots']);
        Route::get('/generate-slots', [TimeApiSlotController::class, 'generateSlots']);
        Route::post('/add-customer-car', [CustomerApiController::class, 'addCustomerCar']);
        Route::get('/get-customer-wise-car/{customer_id}', [CustomerApiController::class, 'getCustomerWiseCar']);
        Route::post('/customer-car-delete', [CustomerApiController::class, 'customerCardelete']);
        Route::post('/default-customer-car', [CustomerApiController::class, 'defaultCustomerCar']);

        //Order api
        Route::post('/add-order', [OrderApiController::class, 'addOrder']);
        Route::get('/get-order-list', [OrderApiController::class, 'getOrderList']);
        Route::get('/get-order-detail/{order_id}', [OrderApiController::class, 'getOrderDetail']);


        

        // add firebase token after user login
        Route::post('add-firebase-token', [UserApiController::class, 'add_firebase_token']);
        
        // Add Notification Remainder To User Profile
        Route::post('add-notification-remainder', [UserApiController::class, 'add_remainder']);

        // Login user profile change password
        Route::post('change-password', [AuthController::class, 'changePassword']);
        //Customer Api
        Route::get('/get-customer-home-page', [CustomerApiController::class, 'getCustomerHomePage']);
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
