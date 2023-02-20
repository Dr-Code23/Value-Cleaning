<?php

namespace Modules\Auth\Repositories\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    public function register($data)
    {
        if ($data->fails()) {
            return response()->json($data->errors()->toJson(), 400);
        }

        //Request is valid, create new user
        $user = User::create(array_merge(
            $data->validated(),
            ['password' => bcrypt($data->password),

            ]
        ));
        $user->assignRole('user');
        Auth::login($user);
        return $user ;

    }
    public function Login($data)
    {
        $credentials = $data->only('email', 'password');



        //Send failed response if request is not valid
        if ($data->fails()) {
            return response()->json(['error' => $data->messages()], 400);
        }

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
        return [auth()->user(),$token];


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
