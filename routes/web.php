<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ColumnController;

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
Route::post('/task/store', [TaskController::class, 'store'])->middleware(['auth'])->name('store');
Route::post('/manage', [TaskController::class, 'manage'])->middleware(['auth'])->name('manage');

Route::post('/column/store', [ColumnController::class, 'store'])->middleware(['auth'])->name('column.store');
Route::post('/column/sort', [ColumnController::class, 'sort'])->middleware(['auth'])->name('column.sort');

// TODO Route inutile pour corriger bug à la connexion
Route::get('/dashboard', function(){return redirect()->route('dashboard');});

require __DIR__.'/auth.php';
