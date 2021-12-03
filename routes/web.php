<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Route::get('/', [TaskController::class, 'index'])->name('dashboard');
Route::post('/store', [TaskController::class, 'store'])->middleware(['auth'])->name('store');
Route::post('/manage', [TaskController::class, 'manage'])->middleware(['auth'])->name('manage');

// TODO Route inutile pour corriger bug à la connexion
Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

require __DIR__.'/auth.php';
