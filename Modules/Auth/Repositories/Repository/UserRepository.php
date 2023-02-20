<?php

namespace Modules\Auth\Repositories\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    public function register($data)
    {
if(!$data->rules()){


    return response()->json([
        'success' => false,
        'message' => 'user credentials are invalid.',
    ], 400);


}
        //Request is valid, create new user
        $user = User::create([
            'name'=> $data->name,
            'email'=> $data->email,
            'address'=> $data->adress,
            'phone'=> $data->phone,
            'password'=> hash::make($data->password),



        ]);

        $user->assignRole('user');

        Auth::login($user);
        return ['statusCode' => 200, 'status' => true,
            'message' => 'User successfully registered ',
            'data' => new UserResource($user)
        ];

    }
    public function Login($data)
    {
        $credentials = $data->only('email', 'password');



        //Send failed response if request is not valid


        //Request is validated
        //Create token
        try {

            if (!$token = Auth::attempt($credentials)) {
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
        if(!auth()->user()->hasRole('user')){

            return response()->json(['error' => 'not allowed'], 400);



        }

        $user=  auth()->user();

        return ['statusCode' => 200, 'status' => true,
            'message' => 'User successfully registered ',
            'data' => new UserResource($user),
            'token'=>$token
        ];


    }

    public function forgotPassword($data)
    {

    }
    public function checkCode($data)
    {

    }
    public function reset($data)
    {

    }
    public function profile(){

    }
    public function Logout()
    {


    }

    public function UpdateProfile($data)
    {

    }
    public function changePassword($data)
    {

    }

}
