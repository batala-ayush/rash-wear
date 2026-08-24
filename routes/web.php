<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserAdminController;

Route::prefix('api')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::get('/auth/current', [AuthController::class, 'current']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::prefix('api')->middleware(['auth', 'role:owner,shop_manager'])->group(function () {
    Route::get('/admin/catalog', [CatalogController::class, 'manage']);
    Route::post('/categories', [CatalogController::class, 'storeCategory']);
    Route::post('/subcategories', [CatalogController::class, 'storeSubcategory']);
    Route::post('/products', [CatalogController::class, 'storeProduct']);
    Route::patch('/products/{product}', [CatalogController::class, 'updateProduct']);
    Route::patch('/inventory/{variant}', [CatalogController::class, 'updateStock']);
    Route::patch('/products/{product}/inventory', [CatalogController::class, 'adjustProductStock']);
    Route::delete('/categories/{category}', [CatalogController::class, 'deleteCategory']);
    Route::delete('/subcategories/{subcategory}', [CatalogController::class, 'deleteSubcategory']);
});

Route::get('/api/catalog', [CatalogController::class, 'index']);

Route::prefix('api')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/admin/users', [UserAdminController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'show']);
    Route::patch('/settings', [SettingsController::class, 'update']);
});

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

Route::get('/product', function () {
    return view('product');
})->name('product');

Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/account', function () {
    return view('account');
})->name('account');

Route::redirect('/login', '/account')->name('login');

Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth', 'role:owner,shop_manager'])->name('admin');
