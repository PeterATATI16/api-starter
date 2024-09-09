<?php


use App\Http\Controllers\ActionLogController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->middleware('log.action:Registration,User registered');
    Route::post('/login', 'login')->middleware('log.action:Login,User logged in');
    Route::post('password/forgot', 'forgotPassword')->middleware('log.action:Password Forgot,User requested password reset');
    Route::post('password/reset', 'resetPassword')->middleware('log.action:Password Reset,User reset password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('log.action:Logout,User logged out');
    Route::apiResource('users', UserController::class)->middleware('log.action:Users Management,User management accessed');

    Route::controller(UserController::class)->group(function () {
        Route::post('/users/imports', 'import');
    });
    Route::controller(LogController::class)->group(function () {
        Route::get('/logs', 'index');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard-items', 'dashboardItems')->middleware('log.action:Dashboard Viewed,User viewed dashboard');
    });

    Route::prefix('roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
            Route::get('/show/{id}', 'show');
            Route::put('/update/{id}', 'update');
            Route::delete('/delete/{id}', 'destroy');
            Route::patch('/{id}/toggle-active', 'toggleActive');
        });
    });

});

Route::controller(CommandController::class)->group(function () {
    Route::get('php/artisan/{cmd}', 'migration_with_seed')->name('migration_with_seed');
});

