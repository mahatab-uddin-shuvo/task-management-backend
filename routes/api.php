<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Task\TaskAssignmentController;
use App\Http\Controllers\Task\TaskCreationController;
use Illuminate\Http\Request;
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
//user registration api
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
//user Login api
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::group(['middleware' => 'auth:api'], function () {
    //logout api
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

    //user search api
    Route::get('user/search/{term}', [AuthController::class, 'search'])->name('task.search');

    //user search and get api
    Route::get('user/get/{id}', [AuthController::class, 'details'])->name('task.details');

//Task creation api
    Route::prefix('task')->group(function () {
        Route::post('create', [TaskCreationController::class, 'create'])->name('task.create');
        Route::get('list', [TaskCreationController::class, 'listAll'])->name('task.listAll');
        Route::get('get/{id}', [TaskCreationController::class, 'details'])->name('task.details');
        Route::post('update/{id}', [TaskCreationController::class, 'update'])->name('task.update');
        Route::get('search/{term}', [TaskCreationController::class, 'search'])->name('task.search');
        Route::get('searchAll/{term}', [TaskCreationController::class, 'searchAll'])->name('task.searchAll');
        Route::post('status/{id}', [TaskCreationController::class, 'status'])->name('task.status');
    });

//Task assign api
    Route::prefix('task-assignment')->group(function () {
        Route::post('assign', [TaskAssignmentController::class, 'assignTask'])->name('task.assign');
        Route::get('list', [TaskAssignmentController::class, 'listAll'])->name('task.assign.listAll');
        Route::get('searchAll/{term}', [TaskAssignmentController::class, 'searchAll'])->name('task.assign.searchAll');

    });
});
