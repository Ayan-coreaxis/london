<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AnalyticsController;

// ─── Public Pages ───────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/all-products', [HomeController::class, 'allProducts'])->name('products');
Route::get('/about-us', fn() => view('pages.about'))->name('about');
Route::get('/privacy-policy', fn() => view('pages.privacy'))->name('privacy');
Route::get('/terms-conditions', fn() => view('pages.terms'))->name('terms');

// Blog (FIXED — was pointing to home)
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Search (FIXED — basic search)
Route::get('/search', [HomeController::class, 'allProducts'])->name('search');

// Contact
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'send'])->name('contact.send');

// Product category shortcuts
Route::get('/banners', fn() => redirect()->route('product.show','banners'))->name('banners');
Route::get('/business-cards', fn() => redirect()->route('product.show','business-cards'))->name('business-cards');
Route::get('/brochures', fn() => redirect()->route('product.show','brochures'))->name('brochures');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// ─── Cart / Basket ───────────────────────────────────────────
Route::get('/basket', [CartController::class, 'index'])->name('basket');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/add', fn() => redirect()->route('products'))->name('cart.add.get'); // Prevent GET error
Route::post('/cart/update/{cartKey}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/artwork/{cartKey}', [CartController::class, 'uploadArtwork'])->name('cart.artwork.upload');
Route::delete('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/update/{cartKey}', fn() => redirect()->route('basket'));
Route::get('/cart/remove/{cartKey}', fn() => redirect()->route('basket'));
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// ─── Checkout / Orders ───────────────────────────────────────
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/order/place', [CartController::class, 'placeOrder'])->name('order.place')->middleware('auth');

// ─── User Account ────────────────────────────────────────────
Route::middleware('auth')->prefix('account')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/order/{id}', [UserController::class, 'orderDetail'])->name('order');
    Route::post('/order/{id}/cancel', [UserController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/order/{id}/artwork', [\App\Http\Controllers\ArtworkController::class, 'upload'])->name('order.artwork.upload');
    Route::post('/order/{id}/note', [\App\Http\Controllers\OrderNoteController::class, 'customerStore'])->name('order.note');
    Route::post('/order/{id}/reorder', [UserController::class, 'reorder'])->name('order.reorder');
    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'changePassword'])->name('password.update');
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/notifications', [UserController::class, 'updateNotifications'])->name('profile.notifications');
    // Addresses
    Route::get('/addresses', [\App\Http\Controllers\AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses', [\App\Http\Controllers\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [\App\Http\Controllers\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [\App\Http\Controllers\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/default', [\App\Http\Controllers\AddressController::class, 'setDefault'])->name('addresses.default');
    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Artwork download/delete (auth checked inside controller)
Route::get('/artwork/{id}/download', [\App\Http\Controllers\ArtworkController::class, 'download'])->name('artwork.download');
Route::delete('/artwork/{id}', [\App\Http\Controllers\ArtworkController::class, 'delete'])->name('artwork.delete')->middleware('auth');

// Wishlist toggle (AJAX)
Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle')->middleware('auth');

// Addresses API for checkout autofill
Route::get('/api/addresses', [\App\Http\Controllers\AddressController::class, 'getForCheckout'])->name('api.addresses')->middleware('auth');

// ─── Auth ────────────────────────────────────────────────────
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/checkout-login', fn() => view('auth.checkout-login'))->name('checkout.login')->middleware('guest');
Route::get('/checkout-register', fn() => view('auth.checkout-register'))->name('checkout.register')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');

// Password Reset (FIXED — was just showing login view)
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
    ->name('password.request')->middleware('guest');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
    ->name('password.reset')->middleware('guest');
Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->name('password.store')->middleware('guest');

Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
    ->name('login.post')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
    ->name('register.post')->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')->middleware('auth');

// ─── Admin Auth ──────────────────────────────────────────────
Route::get('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

// ─── Admin Panel ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{id}/artwork', [\App\Http\Controllers\ArtworkController::class, 'adminUpload'])->name('orders.artwork.upload');
    Route::post('/orders/{id}/note', [\App\Http\Controllers\OrderNoteController::class, 'adminStore'])->name('orders.note');
    Route::get('/activity-log', [\App\Http\Controllers\Admin\DashboardController::class, 'activityLog'])->name('activity-log');
    Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UsersController::class, 'show'])->name('users.show');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    // Blog management
    Route::get('/blogs', [\App\Http\Controllers\Admin\BlogAdminController::class,'index'])->name('blogs.index');
    Route::get('/blogs/create', [\App\Http\Controllers\Admin\BlogAdminController::class,'create'])->name('blogs.create');
    Route::post('/blogs', [\App\Http\Controllers\Admin\BlogAdminController::class,'store'])->name('blogs.store');
    Route::get('/blogs/{id}/edit', [\App\Http\Controllers\Admin\BlogAdminController::class,'edit'])->name('blogs.edit');
    Route::post('/blogs/{id}/update', [\App\Http\Controllers\Admin\BlogAdminController::class,'update'])->name('blogs.update');
    Route::delete('/blogs/{id}', [\App\Http\Controllers\Admin\BlogAdminController::class,'destroy'])->name('blogs.destroy');
    // Visual Page Editor
    Route::get('/page-editor/edits', [\App\Http\Controllers\Admin\PageEditorController::class, 'getEdits'])->name('page-editor.get');
    Route::post('/page-editor/save', [\App\Http\Controllers\Admin\PageEditorController::class, 'saveEdits'])->name('page-editor.save');
    Route::post('/page-editor/reset', [\App\Http\Controllers\Admin\PageEditorController::class, 'resetPage'])->name('page-editor.reset');
    // Products
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'adminCreate'])->name('products.create');
    Route::post('/products', [ProductController::class, 'adminStore'])->name('products.store');
    Route::get('/products/{id}/pricing', [ProductController::class, 'adminPricingGet'])->name('products.pricing.get');
    Route::post('/products/{id}/pricing', [ProductController::class, 'adminPricingSave'])->name('products.pricing.save');
    Route::get('/products/{id}/edit', [ProductController::class, 'adminEdit'])->name('products.edit');
    Route::post('/products/{id}/update', [ProductController::class, 'adminUpdate'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'adminDestroy'])->name('products.destroy');

    // ═══ Variation Product Manager (separate system) ═══
    Route::get('/variation-products', [\App\Http\Controllers\Admin\VariationController::class, 'index'])->name('vpm.index');
    Route::get('/variation-products/create', [\App\Http\Controllers\Admin\VariationController::class, 'create'])->name('vpm.create');
    Route::post('/variation-products', [\App\Http\Controllers\Admin\VariationController::class, 'store'])->name('vpm.store');
    Route::get('/variation-products/{id}/edit', [\App\Http\Controllers\Admin\VariationController::class, 'edit'])->name('vpm.edit');
    Route::post('/variation-products/{id}/update', [\App\Http\Controllers\Admin\VariationController::class, 'update'])->name('vpm.update');
    Route::delete('/variation-products/{id}', [\App\Http\Controllers\Admin\VariationController::class, 'destroy'])->name('vpm.destroy');
    Route::get('/variation-products/{id}/data', [\App\Http\Controllers\Admin\VariationController::class, 'getData'])->name('vpm.data');
    Route::post('/variation-products/{id}/data', [\App\Http\Controllers\Admin\VariationController::class, 'saveData'])->name('vpm.save');

    // ═══ Site Management (Delivery, Payment, Footer, Nav, Promos) ═══
    Route::get('/delivery', [\App\Http\Controllers\Admin\SiteManagerController::class, 'deliveryIndex'])->name('delivery.index');
    Route::post('/delivery', [\App\Http\Controllers\Admin\SiteManagerController::class, 'deliverySave'])->name('delivery.save');
    Route::delete('/delivery/zone/{id}', [\App\Http\Controllers\Admin\SiteManagerController::class, 'deliveryDeleteZone'])->name('delivery.zone.delete');
    Route::delete('/delivery/method/{id}', [\App\Http\Controllers\Admin\SiteManagerController::class, 'deliveryDeleteMethod'])->name('delivery.method.delete');

    Route::get('/payment-methods', [\App\Http\Controllers\Admin\SiteManagerController::class, 'paymentIndex'])->name('payment-methods.index');
    Route::post('/payment-methods', [\App\Http\Controllers\Admin\SiteManagerController::class, 'paymentSave'])->name('payment-methods.save');

    Route::get('/footer-links', [\App\Http\Controllers\Admin\SiteManagerController::class, 'footerLinksIndex'])->name('footer-links.index');
    Route::post('/footer-links', [\App\Http\Controllers\Admin\SiteManagerController::class, 'footerLinksSave'])->name('footer-links.save');

    Route::get('/nav-links', [\App\Http\Controllers\Admin\SiteManagerController::class, 'navLinksIndex'])->name('nav-links.index');
    Route::post('/nav-links', [\App\Http\Controllers\Admin\SiteManagerController::class, 'navLinksSave'])->name('nav-links.save');

    Route::get('/promos', [\App\Http\Controllers\Admin\SiteManagerController::class, 'promoIndex'])->name('promos.index');
    Route::post('/promos', [\App\Http\Controllers\Admin\SiteManagerController::class, 'promoStore'])->name('promos.store');
    Route::post('/promos/{id}/toggle', [\App\Http\Controllers\Admin\SiteManagerController::class, 'promoToggle'])->name('promos.toggle');
    Route::delete('/promos/{id}', [\App\Http\Controllers\Admin\SiteManagerController::class, 'promoDelete'])->name('promos.delete');
});

// Variation Frontend API (public)
Route::get('/api/product/{id}/variations', [\App\Http\Controllers\Admin\VariationController::class, 'frontendData'])->name('api.product.variations');

// ─── Payment API (AJAX) ──────────────────────────────────────
Route::post('/api/payment/stripe-intent', [\App\Http\Controllers\PaymentController::class, 'createStripeIntent'])->name('api.payment.stripe')->middleware('auth');
Route::post('/api/payment/paypal-create', [\App\Http\Controllers\PaymentController::class, 'createPaypalOrder'])->name('api.payment.paypal.create')->middleware('auth');
Route::post('/api/payment/paypal-capture', [\App\Http\Controllers\PaymentController::class, 'capturePaypalOrder'])->name('api.payment.paypal.capture')->middleware('auth');
Route::post('/api/promo/validate', [\App\Http\Controllers\PaymentController::class, 'validatePromo'])->name('api.promo.validate');
Route::post('/api/delivery/methods', [\App\Http\Controllers\PaymentController::class, 'getDeliveryMethods'])->name('api.delivery.methods');