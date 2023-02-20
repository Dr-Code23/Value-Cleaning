<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Models\User;
use Auth;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{


    public function AdminRegister(CreateRequest $request)
    {
        //Validate data

        if ($request->fails()) {
            return response()->json($request->errors()->toJson(), 400);
        }

        //Request is valid, create new user
        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password),

            ]
        ));
        $user->syncRoles(['admin']);




        return ['statusCode' => 200, 'status' => true,
            'message' => 'User successfully registered ',
            'data' => new UserResource($user)
        ];


    }


    public function AdminLogin(loginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential

$user= User::whereEmail($request->email)->first();
if(!$user->hasRole('admin')){

    return response()->json(['error' => 'not allowed'], 400);



}

        //Send failed response if request is not valid
        if ($request->fails()) {
            return response()->json(['error' => $request->messages()], 400);
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

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'admin successfully login',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
