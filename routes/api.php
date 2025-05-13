<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password reset routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::put('/user/update', [AuthController::class, 'update'])->name('api.user.update');
    
    // Staff management routes
    Route::post('/register-staff', [AuthController::class, 'registerStaff'])->name('api.register.staff');
    Route::delete('/staff/{id}', [AuthController::class, 'deleteStaff'])->name('api.delete.staff');
    
    // UMKM routes
    Route::post('/umkm', [UmkmController::class, 'store'])->name('api.umkm.store');
    Route::get('/umkm', [UmkmController::class, 'show'])->name('api.umkm.show');
    Route::put('/umkm', [UmkmController::class, 'update'])->name('api.umkm.update');
    
    // Supplier routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('api.suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('api.suppliers.store');
    Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('api.suppliers.show');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('api.suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('api.suppliers.destroy');
    
    // Barang routes
    Route::get('/barang', [BarangController::class, 'index'])->name('api.barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('api.barang.store');
    Route::get('/barang/{id}', [BarangController::class, 'show'])->name('api.barang.show');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('api.barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('api.barang.destroy');
    
    // Kategori routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('api.kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('api.kategori.store');
    Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('api.kategori.show');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('api.kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('api.kategori.destroy');
    
    // Barang Masuk routes
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('api.barang-masuk.index');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('api.barang-masuk.store');
    Route::get('/barang-masuk/{id}', [BarangMasukController::class, 'show'])->name('api.barang-masuk.show');
    Route::put('/barang-masuk/{id}', [BarangMasukController::class, 'update'])->name('api.barang-masuk.update');
    Route::delete('/barang-masuk/{id}', [BarangMasukController::class, 'destroy'])->name('api.barang-masuk.destroy');

    // Barang Keluar routes
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('api.barang-keluar.index');
    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('api.barang-keluar.store');
    Route::get('/barang-keluar/{id}', [BarangKeluarController::class, 'show'])->name('api.barang-keluar.show');
    Route::put('/barang-keluar/{id}', [BarangKeluarController::class, 'update'])->name('api.barang-keluar.update');
    Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy'])->name('api.barang-keluar.destroy');
});

