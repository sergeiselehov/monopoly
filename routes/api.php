<?php

use App\Http\Controllers\Api\V1\MerchantController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\WithdrawalPaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CreditBalanceController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
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
Route::group(['prefix' => 'v1'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);


    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('user/auth', [AuthController::class, 'auth']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('account/{user}', [AuthController::class, 'update']);


        Route::get('user',[UserController::class,'index']);
        Route::post('user',[UserController::class,'store']);
        Route::patch('user/{user}',[UserController::class,'update']);
        Route::delete('user',[UserController::class,'destroy']);

        Route::resources([
            'role' => RoleController::class,
            'permission' => PermissionController::class
        ], ['except' => ['create', 'edit', 'show']]);
        Route::post('add-and-attach',[UserController::class,'addAndAttach']);


        Route::post('merchant',[MerchantController::class,'store']);
        Route::get('merchant',[MerchantController::class,'list']);
        Route::get('merchant/{uuid}',[MerchantController::class,'show']);

        Route::post('transactions/export',[PaymentController::class,'export']);
        Route::get('transactions/chart',[PaymentController::class,'chart']);
        Route::get('transactions/conversion',[PaymentController::class,'conversion']);
        Route::get('transactions/conversion/{userId}',[PaymentController::class,'conversionUser']);
        Route::post('transactions',[PaymentController::class,'list']);
        Route::get('transactions/total-count', [TransactionController::class,'getTotalCount']);

        Route::get('support-requests/categories', [SupportRequestController::class, 'getCategory']);
        Route::post('support-requests', [SupportRequestController::class, 'store']);
        Route::get('support-requests', [SupportRequestController::class, 'list']);

        Route::post('withdrawal',[WithdrawalController::class,'store']);
        Route::get('withdrawal',[WithdrawalController::class,'list']);
        Route::get('withdrawal/total-count',[WithdrawalPaymentController::class,'getTotalCount']);

        Route::get('balances-list',[BalanceController::class,'getList']);
        Route::get('balance-info',[BalanceController::class,'getBalance']);
        Route::get('credit-balance-info',[CreditBalanceController::class,'get']);
        Route::get('summary-balance-info',[BalanceController::class,'getSummary']);
        Route::get('withdrawals-sum',[WithdrawalController::class,'getWithdrawalsSum']);

        Route::post('withdrawal',[WithdrawalController::class,'store']);
        Route::post('withdrawal-payments/export',[WithdrawalPaymentController::class,'export']);
        Route::get('withdrawal-payments',[WithdrawalPaymentController::class,'list']);

        Route::get('disputes', [DisputeController::class, 'list']);
        Route::get('disputes/total-count', [\App\Http\Controllers\Api\V1\DisputeController::class, 'getTotalCount']);

    });
});
