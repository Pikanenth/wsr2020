<?php

use Illuminate\Http\Request;

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

// Выход, Авторизация
Route::post("signup", "AuthController@signup");
Route::post("login", "AuthController@login");

// Только для авторизованных
Route::middleware(['auth:api'])->group(function () {
    
    Route::post("logout", "AuthController@logout"); // Выход
    
    Route::prefix("photo")->group(function() {
        Route::post("/", "PhotosController@upload"); // Загрузка фотографий
        Route::match(["POST", "PATCH"], "{id}", "PhotosController@edit"); // Редактирование фотографии
        Route::get("/", "PhotosController@photos"); // Получение всех фотографий
        Route::get("{id}", "PhotosController@photo"); // Получение одной фотографии
        Route::delete("{id}", "PhotosController@delete"); // Удаление фотографии
    });

    Route::prefix("user")->group(function() {
        Route::post("{user}/share", "PhotosController@share"); // Шаринг фотографий
        Route::get("/", "UserController@search"); // Поиск пользователей
    });
    
});