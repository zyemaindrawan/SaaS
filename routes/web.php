<?php

use App\Http\Controllers\PreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Template routes
Route::get('/templates', [TemplateController::class, 'index'])
    ->name('templates.index');

Route::get('/templates/{template:slug}', [TemplateController::class, 'show'])
    ->name('templates.show');

// Route::get('/templates/{template:slug}/preview', [TemplateController::class, 'preview'])
//     ->name('templates.preview');

// Route untuk order (butuh auth)
Route::middleware(['auth'])->group(function () {
    Route::post('/templates/{template:slug}/order', [OrderController::class, 'create'])
        ->name('templates.order');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public preview routes (no auth required)
Route::get('/preview/{slug}', [PreviewController::class, 'showBySlug'])
    ->name('templates.preview')
    ->where('slug', '[a-zA-Z0-9-_]+');

Route::get('/preview/{slug}/render', [PreviewController::class, 'renderBySlug'])
    ->name('preview.render.slug')
    ->where('slug', '[a-zA-Z0-9-_]+');

Route::get('/preview/{slug}/data', [PreviewController::class, 'dataBySlug'])
    ->name('preview.data.slug')
    ->where('slug', '[a-zA-Z0-9-_]+');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Main preview page (Inertia) - by ID
    Route::get('/preview/{websiteContent}', [PreviewController::class, 'show'])
        ->name('preview.show')
        ->where('websiteContent', '[0-9]+');
    
    // Render preview (Blade template for iframe) - by ID
    Route::get('/preview/{websiteContent}/render', [PreviewController::class, 'render'])
        ->name('preview.render')
        ->where('websiteContent', '[0-9]+');
    
    // Get preview data as JSON
    Route::get('/preview/{websiteContent}/data', [PreviewController::class, 'data'])
        ->name('preview.data')
        ->where('websiteContent', '[0-9]+');
    
    // Update preview content (for real-time editing)
    Route::put('/preview/{websiteContent}/update', [PreviewController::class, 'update'])
        ->name('preview.update')
        ->where('websiteContent', '[0-9]+');

    // Checkout
    Route::get('/templates/{template:slug}/checkout', [CheckoutController::class, 'show'])
        ->name('templates.checkout');
    
    Route::post('/templates/{template:slug}/checkout', [CheckoutController::class, 'process'])
        ->name('templates.checkout.process');

    // Payment routes
    Route::post('/website/{websiteContent}/checkout', [PaymentController::class, 'checkout'])
        ->name('payment.checkout');
    
    Route::get('/payment/{paymentCode}', [PaymentController::class, 'show'])
        ->name('payment.show');
    
    Route::get('/payment/{paymentCode}/finish', [PaymentController::class, 'finish'])
        ->name('payment.finish');
    
    Route::get('/payment/{paymentCode}/unfinish', [PaymentController::class, 'unfinish'])
        ->name('payment.unfinish');
    
    Route::get('/payment/{paymentCode}/error', [PaymentController::class, 'error'])
        ->name('payment.error');
});

// Midtrans notification (no auth required)
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

require __DIR__.'/auth.php';
