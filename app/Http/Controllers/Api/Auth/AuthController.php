<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'address' =>'required',
            'phone' =>'required',
           

        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        //Request is valid, create new user
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),
           
            ]
        ));
return response()->json([
    'success' => true,
    'message' => 'User successfully registered',
    'user' => $user
], 201);


    }



    public function Login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        //Request is validated
        //Crean token
        try {
           
            if (! $token = Auth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
        $user = Auth::user();
                
        return response()->json([
            'success' => true,
            'message' => 'User successfully login',
            'user'    => $user,
            'token' => $token,
        ]);
    }

   
}
 