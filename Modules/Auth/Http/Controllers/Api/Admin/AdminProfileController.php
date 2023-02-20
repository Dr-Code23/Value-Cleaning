<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Modules\Auth\Http\Requests\UpdateRequest;

class AdminProfileController extends Controller
{


    public function AdminProfile()
    {
        $id =Auth::id();
        $user = User::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new UserResource($user)
        ];

    }


    public function AdminUpdateProfile(UpdateRequest $request)
    {


        $input = $request;

        $id =auth()->id();

        $user = User::find($id);
        $user->update($input);
        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }
        return ['statusCode' => 200,'status' => true ,
            'message' => 'admin updated successfully ',
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
