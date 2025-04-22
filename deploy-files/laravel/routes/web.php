<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Weather Routes
Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
Route::get('/weather/data', [WeatherController::class, 'getWeatherData'])->name('weather.data');
Route::middleware('auth')->group(function () {
    Route::post('/weather/favorite', [WeatherController::class, 'toggleFavorite'])->name('weather.favorite');
    Route::get('/weather/history', [WeatherController::class, 'getHistory'])->name('weather.history');
});

// Maps Routes
Route::get('/maps', [MapController::class, 'index'])->name('maps.index');
Route::get('/markers', [MapController::class, 'getMarkers'])->name('markers.index');
Route::post('/markers', [MapController::class, 'store'])->name('markers.store');
Route::put('/markers/{marker}', [MapController::class, 'update'])->name('markers.update');
Route::delete('/markers/{marker}', [MapController::class, 'destroy'])->name('markers.destroy');

// Blog Routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
});

// Public blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Comment routes
Route::middleware('auth')->group(function () {
    Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Shop Routes
Route::middleware(['auth', \App\Http\Middleware\ActivityLogMiddleware::class])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add/{product}', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update/{id}', [ShopController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [ShopController::class, 'processCheckout'])->name('checkout.process')->middleware('web');
    Route::get('/order/{order}/success', [ShopController::class, 'orderSuccess'])->name('shop.order.success');
});

// Subjects Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.toggle-admin');
        Route::get('/users/{user}/activity', [AdminController::class, 'userActivity'])->name('admin.user-activity');
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
        Route::post('/backup', [AdminController::class, 'backup'])->name('admin.backup');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/api/subjects', [SubjectController::class, 'index']);
Route::get('/api/test', function() {
    return response()->json([
        'message' => 'API is working'
    ]);
});

require __DIR__.'/auth.php';
