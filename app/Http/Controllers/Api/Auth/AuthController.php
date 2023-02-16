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
Illuminate\Contracts\Container\BindingResolutionException: Target [App\Repositories\Interfaces\ServiceRepositoryInterface] is not instantiable while building [App\Http\Controllers\Admin\ServiceController]. in file E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Container\Container.php on line 1103

#0 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Container\Container.php(898): Illuminate\Container\Container-&gt;notInstantiable(&#039;App\\Repositorie...&#039;)
#1 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Container\Container.php(770): Illuminate\Container\Container-&gt;build(&#039;App\\Repositorie...&#039;)
#2 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Foundation\Application.php(856): Illuminate\Container\Container-&gt;resolve(&#039;App\\Repositorie...&#039;, Array, true)
#3 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Container\Container.php(706): Illuminate\Foundation\Application-&gt;resolve(&#039;App\\Repositorie...&#039;, Array)
#4 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Foundation\Application.php(841): Illuminate\Container\Container-&gt;make(&#039;App\\Repositorie...&#039;, Array)
#5 E:\Dc.Code\New folder\value-cleanning\vendor\laravel\framework\src\Illuminate\Container\Container.php(1043): Illuminate\Foundation\Application-&gt;make(&#039;App\\Repositorie...&#039;)
#6 E:\Dc.Code\New folder\valu