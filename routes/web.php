<?php

use App\Http\Controllers\GiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SupportController;

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
    return view('welcome');
})->name('welcome');

// Route::get('/', [PlanController::class, 'index'])
//     ->name('root');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::resource('plans', PlanController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth:users');

Route::resource('plans', PlanController::class)
    ->only(['show', 'index']);

Route::resource('plans.gifts', GiftController::class)
    ->middleware(['auth:users'])
    ->only(['create', 'store', 'edit', 'update', 'destroy']);

Route::resource('plans.supports', SupportController::class)
    ->only(['store', 'create', 'destroy'])
    ->middleware(['auth:funds']);

require __DIR__ . '/auth.php';
