<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use Dotenv\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\API\BaseController as BaseController;


class AuthenticationController extends Controller
{

    public function addUser(RegistrationRequest $request)
    {
       if($request->has('about_me')){
          if(!auth()->check()){
            $message['code'] = 400;
            $message['status'] = "Failed";
            $message['message'] = "please sign in first for update your details";
            $message['data'] = $request->all();
           }
       }
       $addUser = User::addUser($request->all());


            if($addUser){

                $message['code'] = 200;
                $message['status'] = "Success";
                $message['message'] = "user added successfully";
                $message['data'] = $request->all();
            }else{
                $message['code'] = 400;
                $message['status'] = "Failed";
                $message['message'] = "Error while adding user";
                $message['data'] = $request->all();
            }
         return response()->json($message);

    }
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $message['code'] = 400;
            $message['status'] = "Failed";
            $message['message'] = "Something Went wrong";
            $message['data'] = $validator->fails();
        }
        $updateUser = User::EditUser($request->all());


        if($updateUser){

            $message['code'] = 200;
            $message['status'] = "Success";
            $message['message'] = "data added successfully";
            $message['data'] = $request->all();
        }else{
            $message['code'] = 400;
            $message['status'] = "Failed";
            $message['message'] = "Something Went wrong";
            $message['data'] = $request->all();
        }
     return response()->json($message);

    }
    //use this method to signin users
    public function signin(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }
        $user = Auth::user();
        $message['token'] =  $user->createToken('tokens')->plainTextToken;
        $message['code'] = 200;
        $message['status'] = "Success";
        $message['message'] = "user login successfully";
        $message['data'] = $request->all();
        return response()->json($message);

    }

    // this method signs out users by removing tokens
    public function signout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
    //function for destroy user
    public function destroy($id)
    {
       $user=User::find($id);
       $delete_user=$user->delete();
       if($delete_user){

        $message['code'] = 200;
        $message['status'] = "Success";
        $message['message'] = "user data deleted successfully";
        $message['data'] = $user;
    }else{
        $message['code'] = 400;
        $message['status'] = "Failed";
        $message['message'] = "Error while adding user";
        $message['data'] = $user;
    }
    return response()->json($message);
    }
    //unothorized user json response
    public function logIn(){
        $message['code'] = 400;
        $message['status'] = "Failed";
        $message['message'] = "Unauthorized.";
        return response()->json($message);
    }

}
