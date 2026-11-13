<?php

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FavoriteController as AdminFavoriteController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - EmläkGO Admin
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Login sahypasy
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email ýa-da parol nädogry.',
    ])->onlyInput('email');
})->name('login.post')->middleware('guest');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// Admin panel routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Properties management
        Route::get('/properties',                      [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::patch('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
        Route::patch('/properties/{property}/reject',  [AdminPropertyController::class, 'reject'])->name('properties.reject');
        Route::delete('/properties/{property}',        [AdminPropertyController::class, 'destroy'])->name('properties.destroy');

        // Inquiries management
        Route::get('/inquiries',               [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::delete('/inquiries/{inquiry}',  [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

        // Comments management
        Route::get('/comments',                [AdminCommentController::class, 'index'])->name('comments.index');
        Route::delete('/comments/{comment}',   [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // Favorites management
        Route::get('/favorites',               [AdminFavoriteController::class, 'index'])->name('favorites.index');

        // Users management
        Route::get('/users',              [AdminUserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}',    [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
