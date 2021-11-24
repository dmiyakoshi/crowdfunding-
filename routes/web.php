<?php

use App\Http\Controllers\GiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SupportController;
use App\Policies\FundPolicy;

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

Route::get('plans/{plan}/change/public', [PlanController::class, 'changePublic'])
    ->middleware('auth:users')
    ->name('plans.change.public');

Route::resource('plans', PlanController::class)
    ->only(['show', 'index']);

Route::resource('plans.gifts', GiftController::class)
    ->middleware(['auth:users'])
    ->only(['create', 'store', 'edit', 'update', 'destroy']);

Route::resource('plans.supports', SupportController::class)
    ->only(['destroy'])
    ->middleware(['auth:funds']);

Route::get('plans/{plan}/supports/create/gift/{gift?}', [SupportController::class, 'create'])
    ->middleware(['auth:funds'])
    ->name('supports.create');

Route::post('plans/{plan}/supports/srore/gift/{gift?}', [SupportController::class, 'store'])
    ->middleware(['auth:funds'])
    ->name('supports.store');

require __DIR__ . '/auth.php';
