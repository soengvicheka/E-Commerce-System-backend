<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

// Sanctum CSRF
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Admin Panel
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Access denied.');
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials.');
    });

    Route::middleware(['web', 'admin'])->group(function () {
        Route::get('/logout', function (\Illuminate\Http\Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('admin.login');
        })->name('logout');

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Admin\DashboardController::class, 'orders'])->name('orders.index');
        Route::get('/users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users.index');

        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });
});

// Vue frontend fallback
Route::fallback(function () {
    return view('app');
});
