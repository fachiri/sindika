<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return redirect()->route('auth.login');
});

Route::get('/login', function () {
  return view('auth.login');
})->name('auth.login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['web', 'auth'])->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
  Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
  Route::post('/profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change_password');

  Route::prefix('master')->name('master.')->middleware(['roles:APOTEKER,OPERATOR'])->group(function () {
    Route::resource('/unit', UnitController::class)->except(['create', 'edit'])->names('unit');
    Route::resource('/type', TypeController::class)->except(['create', 'edit'])->names('type');
    Route::resource('/category', CategoryController::class)->except(['create', 'edit'])->names('category');
    Route::resource('/medicine', MedicineController::class)->names('medicine');
    Route::resource('/supplier', SupplierController::class)->names('supplier');
    Route::resource('/stock', StockController::class)->names('stock');
    Route::resource('/sale', SaleController::class)->names('sale');
    Route::post('/sale/cetak-format', [SaleController::class, 'cetak_format'])->name('sale.cetak-format');
    Route::post('/sale/import', [SaleController::class, 'import'])->name('sale.import');
  });

  Route::prefix('calculation')->name('calculation.')->middleware(['roles:APOTEKER,MANAJER'])->group(function () {
    Route::get('/eoq', [CalculationController::class, 'eoq'])->name('eoq');
    Route::get('/wma', [CalculationController::class, 'wma'])->name('wma');
    Route::get('/wma/calculate', [CalculationController::class, 'calculate_wma'])->name('calculate.wma');
  });

  Route::prefix('excel')->name('excel.')->middleware(['roles:APOTEKER,MANAJER'])->group(function () {
    Route::get('/cetak-wma/{uuid}', [ExcelController::class, 'cetak_wma'])->name('cetak-wma');
    Route::get('/cetak-eoq', [ExcelController::class, 'cetak_eoq'])->name('cetak-eoq');
  });
});

