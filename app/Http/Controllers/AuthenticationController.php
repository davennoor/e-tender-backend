<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:50',
            'email'=>'required|email:rfc|unique:users,email',
            'password'=>'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=>'invalid field',
                'error'=>$validator->errors()
            ], 422);
        }
        
        try{
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'email_verified_at'=>now(),
                'password'=>bcrypt($request->password)
            ]);

            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'status'=>'success',
                'message'=>'Registration Succesfull',
                'data'=>[
                    'name'=>$user->name,
                    'email'=>$user->email,
                    'password'=>$user->password,
                    'token'=>$token
                ]
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Server error',
                'error'=>$ex->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email:rfc',
            'password'=>'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=>'email or password incorrect',
                'error'=>$validator->errors()
            ],401);
        }

        try{
        $credentials = $request->only('email','password');
        if(!Auth::attempt($credentials)){
            return response()->json([
                'status'=>'error',
                'message'=>'email or password incorrect'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'email or password incorrect'
            ], 401);
        }
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'password'=>$user->password,
            'token'=>$token
        ], 200);   
        }
        catch(Exception $ex){
            return response()->json([
            'status' => 'error',
            'message' => 'Server error',
            'error' => $ex->getMessage()
        ], 500);
        } 
    }

    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();
            return response()->json([
                'status'=>'succes',
                'message'=>'Logout Succesfull'
            ], 200);
        }
        catch(Exception $ex){
            return response()->json([
                'status'=>'error',
                'message'=>'Unauthenticathed',
                'error'=>$ex->getMessage()
            ], 401);
        }
    }
}
