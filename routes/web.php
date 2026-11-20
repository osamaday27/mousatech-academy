<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\Admin;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\CourseIndex;
use App\Livewire\CourseShow;
use App\Livewire\Home;
use App\Livewire\MyCourses;
use App\Livewire\MyOrders;
use App\Livewire\OrderShow;
use App\Livewire\Player;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/courses', CourseIndex::class)->name('courses.index');
    Route::get('/courses/{course}', CourseShow::class)->name('courses.show');
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/lang/{locale}', function (string $locale) {
        abort_unless(in_array($locale, ['ar', 'en'], true), 404);
        session(['locale' => $locale]);

        return redirect()->back(fallback: '/');
    })->name('lang');

    Route::middleware('guest')->group(function () {
        Route::get('/login', Login::class)->name('login');
        Route::get('/register', Register::class)->name('register');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        })->name('logout');

        Route::get('/checkout', Checkout::class)->name('checkout');
        Route::get('/orders', MyOrders::class)->name('orders.index');
        Route::get('/orders/{order}', OrderShow::class)->name('orders.show');
        Route::get('/my-courses', MyCourses::class)->name('my-courses');
        Route::get('/learn/{course}/{lesson?}', Player::class)->name('learn');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', Admin\Dashboard::class)->middleware('can:dashboard.view')->name('dashboard');
            Route::get('/orders', Admin\Orders::class)->middleware('can:orders.view')->name('orders');
            Route::get('/courses', Admin\Courses::class)->middleware('can:courses.update')->name('courses');
            Route::get('/courses/{course}/curriculum', Admin\Curriculum::class)->middleware('can:courses.update')->name('curriculum');
            Route::get('/payments/{payment}/proof', function (Payment $payment) {
                abort_unless($payment->proof_path && Storage::disk('local')->exists($payment->proof_path), 404);

                return Storage::disk('local')->response($payment->proof_path);
            })->middleware('can:payments.verify')->name('proof');
        });
    });
});
