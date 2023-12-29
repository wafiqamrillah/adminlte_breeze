<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Route::middleware('auth')->group(function (){
    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard')->middleware('verified');

    // Profile
    Route::view('profile', 'profile')->name('profile');

    // Settings
    Route::prefix('settings')->name('settings')->group(function (){
        Route::view(null, 'settings')->name('.index');
        Route::view('users', 'livewire.settings.user-management')->name('.users');
        Route::view('menus', 'livewire.settings.menu-management')->name('.menus');
    });
});

require __DIR__.'/auth.php';
