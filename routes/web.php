<?php

use App\Http\Controllers\UsersController;
use App\Models\User;
use Illuminate\Foundation\Application;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Index', [
        'statistics' => [
            'users_count' => User::query()->count('*'),
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified'], 'as' => 'users.', 'prefix' => '/users'], function () {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::post('/datatable', [UsersController::class, 'datatable'])->name('datatable');
});


require __DIR__ . '/auth.php';
