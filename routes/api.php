<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['force-json'],
    'prefix' => '/v1',
    'as' => 'v1.',
], function () {
    Route::group([
        'prefix' => '/users',
        'as' => 'users.',
    ], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::get('', [UserController::class, 'index'])->name('index');

        Route::group([
            'middleware' => ['auth:sanctum'],
            'as' => 'tasks.',
        ], function () {
            Route::get('/tasks', [TaskController::class, 'index'])->name('index');
            Route::get('/{user}/tasks', [TaskController::class, 'index'])->name('index.single');
            Route::post('/{user}/tasks', [TaskController::class, 'store'])->name('store');
            Route::get('/{user}/tasks/{task}', [TaskController::class, 'show'])->name('show');
            Route::put('/{user}/tasks/{task}', [TaskController::class, 'update'])->name('update');
            Route::delete('/{user}/tasks/{task}', [TaskController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/tasks/{task}/complete', [TaskController::class, 'complete'])->name('complete');
        });
    });
});
