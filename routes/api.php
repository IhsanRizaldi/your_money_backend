<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1'], function () {
    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::post('/login',[AuthController::class,'login'])->name('login');

    Route::group(['middleware' => ['auth:sanctum']], function() {

        Route::get('user', [AuthController::class, 'getUser'])->name('user.get');
        Route::put('user/update', [AuthController::class, 'update'])->name('user.update');

        Route::prefix('pemasukan')->group(function () {
            Route::get('/', [PemasukanController::class, 'index'])->name('pemasukan.index');
            Route::post('/', [PemasukanController::class, 'store'])->name('pemasukan.store');
            Route::put('/{pemasukan}', [PemasukanController::class, 'update'])->name('pemasukan.update');
            Route::delete('/{pemasukan}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
        });

        Route::prefix('pengeluaran')->group(function () {
            Route::get('/', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
            Route::post('/', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
            Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
            Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
        });

        Route::prefix('history')->group(function () {
            Route::get('/', [HistoryController::class, 'index'])->name('history.index');
        });

        Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    });
});

