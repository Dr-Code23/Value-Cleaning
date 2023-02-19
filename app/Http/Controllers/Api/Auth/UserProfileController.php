<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
class UserProfileController extends Controller
{


    public function profile()
    {
        $id =Auth::id();
        $user = User::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new UserResource($user)
        ];

    }


    public function UpdateProfile(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'address' => 'required',
            'phone' => 'required',
            "photo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"


        ]);

        $input = $validator;

        $id =auth()->id();

        $user = User::find($id);
        $user->update($input);
        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }
        return ['statusCode' => 200,'status' => true ,
            'message' => 'user updated successfully ',
            'data' => new UserResource($user)
        ];

    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
