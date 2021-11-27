<?php

use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BasicAmountController;
use App\Http\Controllers\BasicAmountUpdateController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Models\Admin;
use FontLib\Table\Type\name;

Route::get('/', function () {
    return view('signin_pages.client_login');
})->name('user.login')->middleware(['guest:web','guest:admin','guest:employee','guest:super_admin']);


/***** Route with middleware */


Route::prefix('admin')->name('admin.')->group(function()
{
    $limiter = config('fortify.limiters.login');

    Route::get('/login', function () {
        return view('signin_pages.admin_login');
    })->middleware(['guest:web','guest:admin','guest:employee','guest:super_admin'])->name('login');


    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:admin',
            $limiter ? 'throttle:'.$limiter : null,
        ]))->name('check');

        Route::view('/dashboard','dashboard.dashboard')->middleware('auth:admin')->name('dashboard');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

        // =====================user route ======================
        Route::get('/all-user',[UserController::class,'index'])->name('all-user');




            //Basic
            // Route::get('/basic', [BasicAmountController::class, 'basic'])->middleware('auth:admin')->name('basicAmount');

            // Route::get('/basic/showingData', [BasicAmountController::class, 'basicShowDataUpdate'])->middleware('auth:admin');

            // Route::post('/basic/update/{id}', [BasicAmountController::class, 'basicUpdate'])->middleware('auth:admin');





});





/***** Super admin Route with middleware */


Route::prefix('super-admin')->name('super_admin.')->group(function()
{
    $limiter = config('fortify.limiters.login');

    Route::get('/login', function () {
        return view('signin_pages.super_admin_login');
    })->middleware(['guest:web','guest:admin','guest:super_admin','guest:employee'])->name('login');


    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:super_admin',
            $limiter ? 'throttle:'.$limiter : null,
        ]))->name('check');

    Route::view('/dashboard','dashboard.super-admin-dashboard')->middleware('auth:super_admin')->name('dashboard');

    //** installment Routes */
    Route::get('/installment',[InstallmentController::class,'getFileNo'])->middleware('auth:super_admin')->name('installments');
    Route::post('/installment/find',[InstallmentController::class,'findFile'])->middleware('auth:super_admin')->name('installments.find');
    Route::get('/installment/all/{user}',[InstallmentController::class,'allInstallment'])->middleware('auth:super_admin')->name('installments.all');
    Route::get('/installment/edit/{id}',[InstallmentController::class,'editInstallment'])->middleware('auth:super_admin')->name('installments.edit');
    Route::post('/installment/edit/store/{id}',[InstallmentController::class,'storeEditInstallment'])->middleware('auth:super_admin')->name('installments.edit.store');
    Route::get('/installment/create/{user}/{installment_no}/{payment}',[InstallmentController::class,'createNewInstallment'])->middleware('auth:super_admin')->name('installments.create');
    Route::post('/installment/create/store/{user}/{installment_no}/{payment}',[InstallmentController::class,'storeNewInstallment'])->middleware('auth:super_admin')->name('installments.create.store');

    //Basic amounts
    Route::get('/basic', [BasicAmountController::class, 'basic'])->middleware('auth:super_admin')->name('basicAmount');

    Route::get('/add-basic-amount', [BasicAmountController::class, 'addBasicAmountSearch'])->middleware('auth:super_admin')->name('basic_amount.add');
    Route::get('/add-basic-create', [BasicAmountController::class, 'createBasicAmount'])->middleware('auth:super_admin')->name('basic_amount.create');
    Route::post('/add-basic-store/{user}', [BasicAmountController::class, 'basicAmountStore'])
    ->middleware('auth:super_admin')->name('basic_amount.store');



    Route::get('/basic/showingData', [BasicAmountController::class, 'basicShowDataUpdate'])->middleware('auth:super_admin')->name('basic.show.data');

    Route::post('/basic/create/{id}', [BasicAmountController::class, 'basicCreate'])->middleware('auth:super_admin');

    Route::get('/basic/store/{id}', [BasicAmountController::class, 'basicUpdate'])->middleware('auth:super_admin')->name('basic.approve.store');


    //request


    Route::post('/basic/update/{id}', [BasicAmountUpdateController::class, 'basicUpdateRequest'])->middleware('auth:super_admin');



    Route::get('/show/basicrequest',   [BasicAmountUpdateController::class,   'show_request'])->name('show.request')->middleware('auth:super_admin');
    Route::get('/destroy/request/{id}', [BasicAmountUpdateController::class, 'destroy_request'])->name('basic.destroy')->middleware('auth:super_admin');


    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // =====================user route ======================
    Route::get('/all-user',[UserController::class,'index'])->name('all_user');
    Route::get('/add-user',[UserController::class,'create'])->name('add_user');
    Route::post('/store-user',[UserController::class,'store'])->name('user.store');
    Route::get('/edit-user/{user}',[UserController::class,'edit'])->name('user.edit');
    Route::post('/update-user/{user}',[UserController::class,'update'])->name('user.update');
    Route::delete('/delete-user/{user}',[UserController::class,'destroy'])->name('user.delete');

            //pdf
    Route::get('/member/{id}/viewpdf',[PdfController::class,'viewPDF'])->middleware('auth:super_admin');
    Route::get('/member/pdf/{id}',[PdfController::class,'pdfDownload'])->middleware('auth:super_admin');



        //excel
     Route::get('/export-excel/{id}',[ExcelController::class,'exportExcel'])->middleware('auth:super_admin')->name('download.excel');
    Route::get('/export-excel/installment/{id}',[ExcelController::class,'exportExcel'])->middleware('auth:super_admin');


    Route::get('/tables/index/{id}',[TableController::class,'basic'])->middleware('auth:super_admin');
    Route::get('/basic/showingtable',[TableController::class,'basicTable'])->middleware('auth:super_admin')->name('tableshow');
    Route::get('/basic/searchtable',[TableController::class,'basicSearch'])->middleware('auth:super_admin');
});





/***** Employee Route with middleware */


Route::prefix('employee')->name('employee.')->group(function()
{
    $limiter = config('fortify.limiters.login');

    Route::get('/login', function () {
        return view('signin_pages.employee_login');
    })->middleware(['guest:web','guest:admin','guest:super_admin','guest:employee'])->name('login');


    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:employee',
            $limiter ? 'throttle:'.$limiter : null,
        ]))->name('check');

        Route::view('/dashboard','dashboard.employee-dashboard')->middleware('auth:employee')->name('dashboard');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

        // =====================user route ======================
        Route::get('/all-user',[UserController::class,'index'])->name('all-user');
});




//basic amount routes








/***** Route with middleware */




Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/view', function () {
    return view('admin.index');
});
Route::get('/views', function () {
    return view('projects.add_project');
});
Route::get('/project/views', function () {
    return view('projects.view_project');
});
Route::get('/project/all/views', function () {
    return view('projects.all_project');
});
Route::get('/clients', function () {
    return view('ourclients.clients');
});





//pdf routes for basic info
// Route::get('/member/{id}/viewpdf',[PdfController::class,'viewPDF'])->middleware('auth:super_admin');
// Route::get('/member/pdf/{id}',[PdfController::class,'pdfDownload'])->middleware('auth:super_admin');



// //excel
// Route::get('/export-excel/{id}',[ExcelController::class,'exportExcel'])->middleware('auth:super_admin')->name('download.excel');
// Route::get('/export-excel/installment/{id}',[ExcelController::class,'exportExcel'])->middleware('auth:super_admin');


// Route::get('/tables/index/{id}',[TableController::class,'basic'])->middleware('auth:super_admin');
// Route::get('/basic/showingtable',[TableController::class,'basicTable'])->middleware('auth:super_admin')->name('tableshow');
// Route::get('/basic/searchtable',[TableController::class,'basicSearch'])->middleware('auth:super_admin');




Route::get('/data/{id}', [BasicAmountUpdateController::class, 'getBasic_data'])->name('getData')->middleware('auth:super_admin');
Route::get('/fetchData/{id}', [BasicAmountUpdateController::class, 'fetch_data'])->middleware('auth:super_admin');
