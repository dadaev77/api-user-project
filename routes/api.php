<?php

use App\Http\Controllers\api\v1\UserController;
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

Route::prefix('v1')->group(function () {

    Route::post('/register', [UserController::class, 'store']); // Создать нового пользователя
    Route::get('/user/{id}', [UserController::class, 'show']); // Получить пользователя по ID
    Route::put('/{id}', [UserController::class, 'update']); // Обновить пользователя
    Route::get('/users', [UserController::class, 'index']); // Получить всех пользователей
    Route::delete('/user/{id}', [UserController::class, 'destroy']); // Удалить пользователя
});
