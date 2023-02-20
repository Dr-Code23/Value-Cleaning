<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminChangePasswordAController extends Controller
{
    public function AdminchangePassword(ChangePasswordRequest $request)
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
