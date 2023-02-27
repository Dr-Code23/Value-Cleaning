<?php

namespace Modules\Auth\Repositories\Repository;

use App\Mail\EventMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    public function register($data)
    {



       // 'email' => 'sometimes|email|unique:users,email,'. $request->user_id

        //Request is valid, create new user
        $user = User::create([
            'name'=> $data->name,
            'email'=> $data->email,
            'address'=> $data->address,
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
        $user = User::where('email', $data->email)->first();
        if ($user) {
            // 1 generate verification code
            $user->reset_verification_code = rand(100000, 999999);
            $user->save();
            // 2 send email
            Mail::to($user->email)->send(new EventMail($user));
            return response()->json(['status' => true, 'message' => 'check your inbox']);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again']);
        }
    }
    public function checkCode($data)
    {
        $user = User::where('email', $data->email)->first();
        if ($user) {
            if ($user->reset_verification_code == $data->code) {
                return response()->json(['status' => true, 'message' => 'you will be redirected to set new password']);
            }
            return response()->json(['status' => false, 'message' => 'code is invalid, try again']);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again']);
        }
    }
    public function reset($data)
    {
        $user = User::where('email', $data->email)->first();
        if ($user) {
            $user->password = Hash::make($data->password);
            $user->save();
            return response()->json([$user->password,'status' => true, 'message' => 'password has been updated']);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again']);
        }
    }
    public function profile()
    {
        $id =Auth::id();
        $user = User::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new UserResource($user)
        ];
    }


    public function updateProfile($data)
    {
        $input = $data;

        $id =auth()->id();

        $user = User::find($id);
        $user->update($input);
        if ($data->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }
        return ['statusCode' => 200,'status' => true ,
            'message' => 'user updated successfully ',
            'data' => new UserResource($user)
        ];
    }
    public function changePassword($data)
    {
        $auth = Auth::user();

        // The passwords matches
        if (!Hash::check($data->get('current_password'), $auth->password))
        {
            return response()->json(['error', "Current Password is Invalid"]);
        }



        $user =  User::find($auth->id);
        $user->password =  Hash::make($data->new_password);
        $user->save();
        return  response()->json(['success', "Password Changed Successfully"]);
    }

}
