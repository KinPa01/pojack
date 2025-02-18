<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;

Route::get('/greeting', function () {
    return 'Hello World';
}); //return ค่าคงที่ #2 returnค่าออกมาเป็น hello ถ้าพิม /greeting  ใช้ postmanได้ (post)(error)
Route::get('/user', [UserController::class, 'index']);  //กำหนดเวลา/user ทำงานที่ index ในเว็ป

Route::get('/user/{id}', function (string $id) {
    return 'User '.$id;
});//เวลาแสงuser ตามด้วย id ในเว็ป
Route::get('/users/{user}', [UserController::class, 'show']);


Route::get('/products', [ProductController::class, 'index'])->middleware(['auth', 'verified'])->name('products.index');//เพิ่มมา
Route::get('/products/{id}', [ProductController::class, 'show'])->middleware(['auth', 'verified']); // /products ตามด้วย id ในเว็ป จะขึ้นเลขidสินค่า

// Add routes for new controllers
Route::get('/categories', [CategoryController::class, 'index'])->middleware(['auth', 'verified'])->name('categories.index');
Route::get('/orders', [OrderController::class, 'index'])->middleware(['auth', 'verified'])->name('orders.index');
Route::get('/payments', [PaymentController::class, 'index'])->middleware(['auth', 'verified'])->name('payments.index');
Route::get('/tables', [TableController::class, 'index'])->middleware(['auth', 'verified'])->name('tables.index');

Route::get('/store/table-management', [TableController::class, 'manage'])->middleware(['auth', 'verified'])->name('store.TableManagement');

Route::get('/', function () {
    return Inertia::render('Store/Index');
})->middleware(['auth', 'verified'])->name('store.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
