<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    // Поиск пользователей 
    public function search(Request $r) {

        $search = $r->get("search") ?? "";
        $search = explode(" ", $search);
        
        $result = []; $response = [];

        if($r->get("search")) foreach($search as $data) {
            $users = User::where("first_name", "LIKE", "%$data%")->orWhere("surname", "LIKE", "%$data%")->orWhere("phone", "LIKE", "%$data%")->get();
            foreach($users as $user) if($user->id != $this->user->id) $result[$user->id] = ["id" => $user->id, "first_name" => $user->first_name, "surname" => $user->surname, "phone" => $user->phone];
        }

        // В вид результата
        foreach($result as $photo) $response[] = $photo;

        return response()->json($response, 200);

    }

}
