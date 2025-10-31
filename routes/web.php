<?php

use App\Http\Controllers\PreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Api\WebsiteBuilderFileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home/Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        // 'laravelVersion' => Application::VERSION,
        // 'phpVersion' => PHP_VERSION,
        'heroBackground' => resolveAssetPath('website-assets/hero-backgrounds/bg_hero_001.webp'),
        'testimonialImage' => resolveAssetPath('website-assets/testimonials/blank.webp'),
    ]);
})->name('home');

Route::get('/{page}', fn () => inertia('404/Index'))
     ->where('page', 'settings|pricing|about|features|help|contact|docs|terms|privacy')
     ->name('static');

Route::get('/templates', [TemplateController::class, 'index'])
    ->name('templates.index');

Route::get('/templates/{template:slug}', [TemplateController::class, 'show'])
    ->name('templates.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/templates/{template:slug}/order', [OrderController::class, 'create'])
        ->name('templates.order');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public preview routes (no auth required)
Route::get('/preview/{slug}', [PreviewController::class, 'showBySlug'])
    ->name('templates.preview')
    ->where('slug', '^(?![0-9]+$)[A-Za-z0-9_-]+$');

Route::get('/preview/{slug}/render', [PreviewController::class, 'renderBySlug'])
    ->name('preview.render.slug')
    ->where('slug', '^(?![0-9]+$)[A-Za-z0-9_-]+$');

Route::get('/preview/{slug}/data', [PreviewController::class, 'dataBySlug'])
    ->name('preview.data.slug')
    ->where('slug', '^(?![0-9]+$)[A-Za-z0-9_-]+$');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Main preview page by ID
    Route::get('/preview/{websiteContent}', [PreviewController::class, 'show'])
        ->name('preview.show')
        ->where('websiteContent', '[0-9]+');

    // Render preview (iframe)
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

    // Website Builder routes
    Route::get('/website-builder/create', [WebsiteBuilderController::class, 'create'])
        ->name('website-builder.create');

    Route::post('/website-builder', [WebsiteBuilderController::class, 'store'])
        ->name('website-builder.store');

    Route::get('/website-builder/{websiteContent}/edit', [WebsiteBuilderController::class, 'edit'])
        ->name('website-builder.edit');

    Route::put('/website-builder/{websiteContent}', [WebsiteBuilderController::class, 'update'])
        ->name('website-builder.update');

    Route::get('/website-builder/{websiteContent}/preview', [WebsiteBuilderController::class, 'preview'])
        ->name('website-builder.preview');

    // Website Builder File Upload - back in web routes with proper JSON handling
    Route::post('/api/website-builder/upload-file', [WebsiteBuilderFileController::class, 'uploadFile'])
        ->name('website-builder.upload-file');

    // Alias for content.edit (used in CheckoutController)
    Route::get('/content/{websiteContent}/edit', [WebsiteBuilderController::class, 'edit'])
        ->name('content.edit');

    // Checkout
    Route::get('/templates/{template:slug}/checkout', [CheckoutController::class, 'show'])
        ->name('templates.checkout');

    Route::post('/templates/{template:slug}/checkout', [CheckoutController::class, 'process'])
        ->name('templates.checkout.process');

    // Draft management routes
    Route::get('/drafts', [App\Http\Controllers\DraftController::class, 'index'])
        ->name('drafts.index');

    Route::get('/drafts/{websiteContent}', [App\Http\Controllers\DraftController::class, 'show'])
        ->name('drafts.show');

    Route::post('/drafts/{websiteContent}/confirm-payment', [CheckoutController::class, 'confirmPayment'])
        ->name('drafts.confirm-payment');

    Route::patch('/drafts/{websiteContent}/status', [App\Http\Controllers\DraftController::class, 'updateStatus'])
        ->name('drafts.update-status');

    // Invoice routes
    Route::get('/invoice/{code}', [InvoiceController::class, 'show'])
        ->name('invoice.show');

    Route::get('/invoice/{code}/pay', [InvoiceController::class, 'pay'])
        ->name('invoice.pay');

    // Payment routes (New)
    Route::get('/payment/{code}/finish', [PaymentController::class, 'finish'])
        ->name('payment.finish');

    Route::get('/payment/{code}/unfinish', [PaymentController::class, 'unfinish'])
        ->name('payment.unfinish');

    Route::get('/payment/{code}/error', [PaymentController::class, 'error'])
        ->name('payment.error');

    Route::get('/payment/{code}/pending', [PaymentController::class, 'pending'])
        ->name('payment.pending');
});

// Midtrans notification
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

// Voucher API routes
Route::prefix('api')->group(function () {
    Route::prefix('vouchers')->group(function () {
        Route::post('/check', [VoucherController::class, 'check']);
        Route::post('/validate', [VoucherController::class, 'check']);
        Route::get('/', [VoucherController::class, 'index']);
        Route::get('/{code}', [VoucherController::class, 'show']);
    });
    
    // Payment API routes
    Route::prefix('payments')->group(function () {
        Route::get('/by-website/{websiteContentId}', [PaymentController::class, 'getByWebsiteContentId'])
            ->middleware('auth');
    });
});

require __DIR__.'/auth.php';
