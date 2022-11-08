<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

use Validator;

class AuthController extends Controller
{
    public function index(){
        return User::all();
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname' =>'required',
            'lastname' =>'required',
            'password' =>'required|min:6',
            'email' => 'required|unique:users|email',
        ]);
        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message,400);
        }

        $user = User::create([
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>"user"
        ]);

        if($user){
            return response()->json($user, 200);
        }
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
           return response(["message"=>"Invalid email-password"],401);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie=cookie('jwt',$token,60*24);
        return response(['data'=>$user])->withCookie($cookie);
    }
    
    public function logout(Request $request){
        $cookie = Cookie::forget('jwt');
        $request->user()->tokens()->delete();
        return response([
            'message'=>'success'
        ])->withCookie($cookie);
    }

    public function user (){
        return Auth::user();
    }
}
