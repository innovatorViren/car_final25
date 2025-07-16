<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\{
    CountryController,
    BannerController,
    CommonController,
    ReportController,
    DashboardController,
    SettingController,
    StateController,
    CityController,
    YearController,
    CustomerController,
    DepartmentController,
    DesignationController,
    EmployeeController,
    MailTemplateController,
    RoleController,
    UserController,
    SmtpConfigurationController,
    ProfileController,
    PrivacypolicyController,ContactController
};
use App\Http\Controllers\Auth\PasswordController;
use Database\Seeders\SeriesSeeder;
use Illuminate\Support\Facades\Artisan;

Route::get('/user-seed', function () {
    Artisan::call('db:seed');
    echo 'user migration successfully.';
});

Route::get('/db-migration', function () {
    Artisan::call('migrate --force');
    echo 'Migrated successfully.';
});

// Run series seeder
Route::get('/series-seed', function () {
    Artisan::call('db:seed', ['--class' => SeriesSeeder::class]);
    echo 'Seeding successfully.';
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    echo 'All clear done successfully.';
});


Route::get('/', [SessionController::class, 'getLogin'])->name('auth.login.form');
Route::post('/', function () {
    return  ['status' => 'fail', 'message' => 'You are not authorise'];
});
Route::post('login', [SessionController::class, 'postLogin'])->name('auth.login.attempt');
Route::any('logout', [SessionController::class, 'getLogout'])->name('auth.logout');


// Password Reset
Route::get('password/reset/{code}', [PasswordController::class, 'getReset'])->name('auth.password.reset.form');
Route::post('password/reset/{code}', [PasswordController::class, 'postReset'])->name('auth.password.reset.attempt');
Route::get('password/reset', [PasswordController::class, 'getRequest'])->name('auth.password.request.form');
Route::post('password/reset', [PasswordController::class, 'postRequest'])->name('auth.password.request.attempt');


//-------------------Start Profile---------------
Route::group(['prefix' => 'profile'], function () {
    Route::get('/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

Route::get('forcefully-change-password',[ProfileController::class, 'forcefullyChangePassword'])->name('forcefully-change-password');
Route::resource('settings', SettingController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('dashboardadmin', [DashboardController::class, 'index'])->name('dashboardadmin');
Route::post('user-logout', [DashboardController::class, 'userLogout'])->name('userLogout');
Route::get('/master-page', [DashboardController::class, 'masterPages'])->name('masterPages');

//comman route


// CommonController
Route::post('/change-status/{id}', [CommonController::class, 'changeStatus'])->name('common.change-status');
Route::get('/get-category-rawmaterial', [CommonController::class, 'getRawMaterialByCategory'])->name('common.get-category-rawmaterial');
Route::match(['post', 'put'], 'get-states', [CommonController::class, 'getStates'])->name('get-states');
Route::match(['post', 'put'], 'get-cities', [CommonController::class, 'getCities'])->name('get-cities');
Route::match(['get', 'post'], 'get-info', [CommonController::class, 'getInfoData'])->name('get-info');
//Country module routes
Route::resource('country', CountryController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::get('check-country-duplicate-name/{id?}', [CountryController::class, 'checkUniqueName'])->name('country.checkUniqueName');

//state module route
Route::resource('state', StateController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::get('check-state-duplicate-name/{id?}', [StateController::class, 'checkUniqueName'])->name('state.checkUniqueName');

//city module route
Route::resource('city', CityController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::match(['post', 'put'], 'get-cities', [CommonController::class, 'getCities'])->name('get-cities');
//Year module route

Route::resource('year', YearController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::post('/change-default/{id}', [CommonController::class, 'changeDefault'])->name('common.change-default');
Route::post('/change-display/{id}', [YearController::class, 'changeDisplay'])->name('common.change-displayed');
Route::match(['get', 'post'], 'years/changeYear/{id}', [YearController::class, 'changeYear'])->name('years.changeYear');

//Supplier module
Route::resource('supplier', SupplierController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('check-supplier-duplicate-name/{id?}', [SupplierController::class, 'checkUniqueName'])->name('supplier.checkUniqueName');
Route::get('supplier-export', [SupplierController::class, 'supplierExport'])->name('supplierExport');



// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports');


//Employee module routes
Route::resource('employee', EmployeeController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

Route::get('employee-export', [EmployeeController::class, 'employeeExport'])->name('employeeExport');
Route::get('/get-Appointee', [EmployeeController::class, 'getAppointee'])->name('getAppointee');
Route::get('/get-Designation', [EmployeeController::class, 'getDesignation'])->name('getDesignation');
// checkDuplicateAdhar
Route::get('check-duplicate-adhar/{id?}', [EmployeeController::class, 'checkDuplicateAdhar'])->name('checkDuplicateAdhar');
Route::get('check-employee-duplicate-mobile-no/{id?}', [EmployeeController::class, 'checkDuplicateMobile'])
    ->name('checkEmployeeDuplicateMobileNo');

Route::get('check-employee-duplicate-email/{id?}', [EmployeeController::class, 'checkDuplicateEmail'])
    ->name('checkEmployeeDuplicateEmail');

//Department module routes
Route::post('department/employee-department', [DepartmentController::class, 'employeeDepartmentDataList'])->name('employee-department.list');
Route::resource('department', DepartmentController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::get('check-department-duplicate-name/{id?}', [DepartmentController::class, 'checkUniqueName'])->name('department.checkUniqueName');

//Designation module routes
Route::get('designation/grade/unique', [DesignationController::class, 'checkGradeExists'])->name('grade.exits');
Route::post('department/employee-designation', [DesignationController::class, 'employeeDesignationDataList'])->name('employee-designation.list');
Route::resource('designation', DesignationController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::get('employee-list-export', [DesignationController::class, 'employeeListExport'])
    ->name('employeeListExport');
Route::get('check-designation-duplicate-name/{id?}', [DesignationController::class, 'checkUniqueName'])->name('designation.checkUniqueName');

// Customer module routes
Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('check-customer-duplicate-name/{id?}', [CustomerController::class, 'checkUniqueName'])->name('customer.checkUniqueName');

//Customer
Route::get('check-customer-duplicate-mobile-no/{id?}', [CustomerController::class, 'checkDuplicateMobile'])
    ->name('checkCustomerDuplicateMobileNo');

Route::get('check-customer-duplicate-email/{id?}', [CustomerController::class, 'checkDuplicateEmail'])
    ->name('checkCustomerDuplicateEmail');

Route::get('check-customer-duplicate-company-name/{id?}', [CustomerController::class, 'checkDuplicateCompanyName'])
    ->name('checkCustomerDuplicateCompanyName');

// Users
Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::post('/change-status/{id}', [CommonController::class, 'changeStatus'])->name('common.change-status');
Route::get('/get-employee', [UserController::class, 'getEmployeeData'])->name('getEmployeeData');
Route::get('/check-pincode', [CommonController::class, 'checkPincode'])->name('checkPincode');

// Role
Route::resource('roles', RoleController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
Route::get('/get-role-permission', [RoleController::class, 'getRolePermission'])->name('getRolePermissions');
Route::get('/get-users-list', [RoleController::class, 'getUsersList'])->name('role.getUsersList');
Route::get('/view-permission', [RoleController::class, 'viewPermission'])->name('role.viewPermission');


Route::resource('smtp-configuration', SmtpConfigurationController::class)
    ->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);

Route::resource('mail-template', MailTemplateController::class)
    ->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);
// Banner Master
Route::resource('banner', BannerController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy', 'edit']);

Route::get('privacy-policy', [PrivacypolicyController::class, 'index'])->name('privacy-policy');
Route::get('contact-us', [ContactController::class, 'index'])->name('contact-us');
