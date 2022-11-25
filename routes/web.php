<?php

use App\Http\Controllers\TicketsController;
use App\Http\Controllers\UsersController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Website/Welcome');
});

Route::get('/after-auth', function () {
    return Redirect::to(Auth::user()->role == 'ADMIN' ? '/admin/dashboard' : '/users/welcome');
});

Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['authorization:ADMIN', 'auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Index', [
            'statistics' => [
                'users_count' => User::query()->count('*'),
            ]
        ]);
    })
        ->name('dashboard');

    Route::group(['as' => 'users.', 'prefix' => '/users'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::post('/datatable', [UsersController::class, 'datatable'])->name('datatable');
    });

    Route::group(['prefix' => '/tickets', 'as' => 'tickets.'], function () {
        Route::get('/', [TicketsController::class, 'adminIndex']);
        Route::post('/datatable', [TicketsController::class, 'adminDatatable'])->name('datatable');
    });
});

Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => ['authorization:USER', 'auth', 'verified']], function () {
    Route::get('/welcome', function () {
        return Inertia::render('Users/Welcome');
    })
        ->name('welcome');

    Route::group(['prefix' => '/tickets', 'as' => 'tickets.'], function () {
        Route::get('/', [TicketsController::class, 'index'])->name('index');
        Route::post('/datatable', [TicketsController::class, 'datatable'])->name('datatable');
        Route::post('/create', [TicketsController::class, 'create'])->name('create');
    });
});


require __DIR__ . '/auth.php';
