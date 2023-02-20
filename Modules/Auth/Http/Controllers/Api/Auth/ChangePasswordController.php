<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request)
    {


        $auth = Auth::user();

        // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password))
        {
            return response()->json(['error', "Current Password is Invalid"]);
        }



        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['success', "Password Changed Successfully"]);

    }
}
