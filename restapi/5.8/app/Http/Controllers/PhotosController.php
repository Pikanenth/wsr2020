<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Photo;
use Storage;
use App\Share;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhotosController extends Controller
{
    
    // Загрузка фотографии
    public function upload(Request $r) {

        // Валидация 
        $validate = Validator::make($r->all(), [
            "photo" => "required|mimes:jpeg,jpg,png|max:5000"
        ]);

        // Вывод ошибок валидации
        if($validate->fails()) return response()->json($validate->errors(), 422);

        // Загрузка фотографии 
        $path = Str::random(15).".png";
        Storage::disk("photos")->putFileAs("photos", $r->file("photo"), $path);
        
        // Добавление в базу данных
        $photo = new Photo();
        $photo->owner_id = $this->user->id;
        $photo->name = $r->get("name") ?? "Untitled";
        $photo->url = env("APP_URL")."/photos/".$path;
        $photo->save();
        
        // Вывод результатов
        return response()->json([
            "id" => $photo->id,
            "name" => $photo->name,
            "url" => $photo->url
        ], 201);
 
    }

    // Редактирование фотографии
    public function edit($id, Request $r) {

        // Проверка на метод 
        if($r->isMethod("POST")) return response()->json(["_method" => ["patch method required!"]], 422);

        // Поиск фото
        $photo = Photo::find($id);
        if(!$photo) return response()->json(["photo" => "not found"], 404);
        if($photo->owner_id != $this->user->id) return response()->json("", 403);

        $photo->name = $r->get("name") ?? $photo->name ?? "Untitled";

        if($r->get("photo")) {
            $file = explode("base64,", $r->get("photo"));
            if($file[1]) {
                // Загрузка фотографии 
                $path = Str::random(15).".png";
                Storage::disk("photos")->put("photos/".$path, base64_decode($file[1]));
                $photo->url = env("APP_URL")."/photos/".$path;
            }
        }

        $photo->save();

        // Вывод результатов
        return response()->json([
            "id" => $photo->id,
            "name" => $photo->name,
            "url" => $photo->url
        ], 200);

    }

    // Получение всех фотографий
    public function photos(Request $r) {

        // Поиск..
        $me = Photo::where("owner_id", $this->user->id)->get();
        $shares = Share::where("user_id", $this->user->id)->get();
        $photos = []; $response = [];
        
        // Мои фотографии
        foreach($me as $photo) $photos[$photo->id] = [
            "id" => $photo->id,
            "name" => $photo->name,
            "url" => $photo->url,
            "owner_id" => $photo->owner_id,
            "users" => Share::where('photo_id', $photo->id)->pluck('user_id')
        ];
        
        // Расшаренные для меня фотографии
        foreach($shares as $photo) $photos[$photo->photo->id] = [
            "id" => $photo->photo->id,
            "name" => $photo->photo->name,
            "url" => $photo->photo->url,
            "owner_id" => $photo->photo->owner_id,
            "users" => Share::where('photo_id', $photo->photo->id)->pluck('user_id')
        ]; 

        // В вид результата
        foreach($photos as $photo) $response[] = $photo;
        
        return response()->json($response, 200);
    }

    // Получение одной фотографии
    public function photo($id, Request $r) {
        
        $success = false;

        // Поиск фото
        $photo = Photo::find($id);
        if(!$photo) return response()->json(["photo" => "not found"], 404);
        if($photo->owner_id == $this->user->id) $success = true;
        else if(Share::where('user_id', $this->user->id)->where('photo_id', $photo->id)->first()) $success = true;

        if(!$success) return response()->json("", 403);
        
        // Вывод
        return response()->json([
            "id" => $photo->id,
            "name" => $photo->name,
            "url" => $photo->url,
            "owner_id" => $photo->owner_id,
            "users" => Share::where('photo_id', $photo->id)->pluck('user_id')
        ], 200);

    }

    // Удаление фотографии
    public function delete($id, Request $r) {
        // Поиск фотографии
        $photo = Photo::find($id);
        if(!$photo) return response()->json(["photo" => "not found"], 404);
        if($photo->owner_id != $this->user->id) return response()->json("", 403);
        // Шаринг
        Share::where("photo_id", $photo->id)->delete();
        $photo->delete();
        // Удаление
        return response()->json("", 204);
    }

    // Шаринг фотографии
    public function share($user, Request $r) {
        
        // Поиск пользователя
        $user = User::find($user);
        if(!$user) return response()->json(["user" => "not found"], 404);

        // Валидация 
        $validate = Validator::make($r->all(), [
            "photos" => "required|array"
        ]);

        // Вывод ошибок валидации
        if($validate->fails()) return response()->json($validate->errors(), 422);        

        $existing_photos = []; $response = [];
        
        foreach($r->get("photos") as $id) {

            // Поиск фото
            $photo = Photo::find($id);
            if(!$photo) continue;
            if($photo->owner_id != $this->user->id) continue;
            $existing_photos[$photo->id] = $photo->id;
            if(Share::where('photo_id', $photo->id)->where('user_id', $user->id)->first()) continue;

            // Добавляем в шаринг
            $share = new Share();
            $share->user_id = $user->id;
            $share->photo_id = $photo->id;
            $share->save();

        };

        // В вид результата
        foreach($existing_photos as $photo) $response[] = $photo;

        return response()->json(["existing_photos" => $response], 201);
        
    }

}
