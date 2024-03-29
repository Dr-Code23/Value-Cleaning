<?php

namespace Modules\Auth\Repositories\Repository;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\EventMail;
use Modules\Auth\Entities\Notification;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;
use Modules\Auth\Traits\pushNotificationTraite;
use Modules\Auth\Transformers\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminRepository implements AdminRepositoryInterface
{
    use pushNotificationTraite;

    private $userModel;
    private $notificationModel;

    public function __construct(User $user, Notification $notification)
    {
        $this->userModel = $user;
        $this->notificationModel = $notification;
    }

    public function register($data)
    {
        $user = $this->userModel->create([
            'name' => $data->name,
            'email' => $data->email,
            'address' => $data->address,
            'phone' => $data->phone,
            'password' => hash::make($data->password),
            'type' => 'admin',
        ]);

        $user->syncRoles(['admin']);

        Auth::login($user);

        return ['statusCode' => 200, 'status' => true,
            'message' => 'admin successfully registered ',
            'data' => new UserResource($user)
        ];

    }

    public function Login($data)
    {
        $credentials = $data->only('email', 'password');
        //Create token
        try {

            if (!$token = JWTAuth::attempt($credentials)) {
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


        if (Auth::user()->type == 'admin') {

            $user = auth()->user();

            return ['statusCode' => 200, 'status' => true,
                'message' => 'Admin successfully registered ',
                'data' => new UserResource($user),
                'token' => $token
            ];


        } else {
            return response()->json(['statusCode' => 401, 'status' => false, 'error' => 'UnAuthorised'], 401);
        }


    }

    public function profile()
    {
        $id = Auth::id();
        $user = $this->userModel->find($id);
        return ['statusCode' => 200, 'status' => true,
            'data' => new UserResource($user)
        ];
    }

    public function updateProfile($data)
    {

        $id = auth()->id();
        $user = $this->userModel->find($id);
        $user->update($data->all());
        if ($data->hasFile('photo')) {
            $user->media()->delete();
            $user->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Admin updated successfully ',
            'data' => new UserResource($user)
        ];
    }

    public function changePassword($data)
    {
        $auth = Auth::user();

        // The passwords matches
        if (!Hash::check($data->get('current_password'), $auth->password)) {
            return response()->json(['error', "Current Password is Invalid"]);
        }

        $user = $this->userModel->find($auth->id);
        $user->password = Hash::make($data->new_password);
        $user->save();
        return response()->json(['success', "Password Changed Successfully"]);
    }

    public function pushNotification($data)
    {
        return $this->Notification($data);
    }

}
