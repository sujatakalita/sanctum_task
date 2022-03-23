<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function allUser()
    {
        try {
            $user = User::select('first_name', 'last_name', 'email', 'about_me')->get();
            if ($user->count() > 0) {
                $message['code'] = 200;
                $message['status'] = "Success";
                $message['message'] = "all users display successfully";
                $message['data'] = $user;
                return response()->json($message);
            } else {
                $message['code'] = 200;
                $message['status'] = "Success";
                $message['message'] = "No data found";

                return response()->json($message);
            }
        } catch (\Throwable $th) {
            $message['code'] = 400;
            $message['status'] = "Failed";
            $message['message'] = "Error while adding user";
            return response()->json($message);
        }
    }
}
