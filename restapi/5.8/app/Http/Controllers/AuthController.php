<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    // Регистрация
    public function signup(Request $r) {
        
        // Валидация 
        $validate = Validator::make($r->all(), [
            "first_name" => "required",
            "surname" => "required",
            "phone" => "required|unique:users|min:11|max:11",
            "password" => "required"
        ]);

        // Вывод ошибок валидации
        if($validate->fails()) return response()->json($validate->errors(), 422);
        
        // Регистрация нового пользователя
        $user = new User();
        $user->first_name = $r->get("first_name");
        $user->surname = $r->get("surname");
        $user->phone = $r->get("phone");
        $user->api_token = Str::random(80);
        $user->password = $r->get("password");
        $user->save();

        // Вывод успешного результата
        return response()->json(["id" => $user->id], 201);

    }

    // Авторизация
    public function login(Request $r) {

        // Валидация 
        $validate = Validator::make($r->all(), [
            "phone" => "required|min:11|max:11",
            "password" => "required"
        ]);

        // Вывод ошибок валидации
        if($validate->fails()) return response()->json($validate->errors(), 422);

        // Поиск пользователя
        $user = User::where("phone", $r->get("phone"))->where("password", $r->get("password"))->first();
        if(!$user) return response()->json(["login" => "Incorrect login or password"], 404);

        // Успешный результат
        return response()->json(["token" => $user->api_token], 200);

    } 

    // Выход
    public function logout(Request $r) {
        // Сброс токена
        $this->user->api_token = Str::random(80);
        $this->user->save();
        // Вывод результата
        return response()->json("", 200);
    }

}
