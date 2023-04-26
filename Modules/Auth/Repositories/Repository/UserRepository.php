<?php

namespace Modules\Auth\Repositories\Repository;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\EventMail;
use Modules\Auth\Entities\SendNotification;
use Modules\Auth\Entities\TermsAndConditions;
use Modules\Auth\Events\NewCompany;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Modules\Auth\Transformers\CompanyResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Chat\Entities\Room;
use Modules\Chat\Events\NewRoom;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    private $userModel;
    private $notificationModel;

    public function __construct(User $user, SendNotification $notification)
    {
        $this->userModel = $user;
        $this->notificationModel = $notification;
    }

    public function register($data)
    {
        $user = $this->userModel->create([
            'name' => $data->name,
            'email' => $data->email,
            'address' => json_encode($data['address']),
            'latitude' => $data->latitude,
            'longitude' => $data->latitude,
            'phone' => $data->phone,
            'password' => hash::make($data->password),

        ]);
        $usere = User::latest()->first()->id;
        $room = new Room();
        $room['user_id'] = $user->id;
        $room->save();
        event(new NewRoom($room));
        $user->assignRole('user');
        Auth::login($user);

        return ['statusCode' => 200, 'status' => true,
            'message' => 'User successfully registered ',
            'data' => new UserResource($user)
        ];

    }

    /**
     * @param $data
     * @return array|JsonResponse
     */
    public function Login($data)
    {
        $credentials = $data->only('email', 'password');
        //Create token

        try {

            if ($token = Auth::attempt($credentials)) {


                $user = Auth::user();

                if (!$user->approved) {
                    Auth::logout();
                    return response()
                        ->json([
                            'error' => 'Account not approved yet'
                        ], 401);
                }
                if ($user->type == 'company') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Company successfully logged in.',
                        'data' => new CompanyResource($user),
                        'token' => $token
                    ]);
                } elseif ($user->type == 'user') {
                    $user['device_token'] = $data->device_token;
                    $user->update();

                    return response()->json([
                        'success' => true,
                        'message' => 'User successfully logged in.',
                        'data' => new UserResource($user),
                        'token' => $token
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);


            } else {
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


    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function forgotPassword($data)
    {
        $user = $this->userModel->where('email', $data->email)->first();
        if ($user) {
            // 1 generate verification code
            $user->reset_verification_code = rand(100000, 999999);
            $user->save();
            // 2 send email
            Mail::to($user->email)->send(new EventMail($user));
            return response()->json(['status' => true, 'message' => 'check your inbox']);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again'], 400);
        }
    }

    public function checkCode($data)
    {
        $user = $this->userModel->where('email', $data->email)->first();
        if ($user) {
            if ($user->reset_verification_code == $data->code) {
                return response()->json(['status' => true, 'message' => 'you will be redirected to set new password']);
            }
            return response()->json(['status' => false, 'message' => 'code is invalid, try again'], 400);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again'], 400);
        }
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function reset($data)
    {
        $user = $this->userModel->where('email', $data->email)->first();
        if ($user) {
            $user->password = Hash::make($data->password);
            $user->save();
            return response()->json([$user->password, 'status' => true, 'message' => 'password has been updated']);

        } else {
            return response()->json(['status' => false, 'message' => 'email not found, try again'], 400);
        }
    }

    /**
     * @return array
     */
    public function profile()
    {
        $id = Auth::id();
        $user = $this->userModel->find($id);
        $termsAndConditions = TermsAndConditions::all();
        return [
            'statusCode' => 200,
            'status' => true,
            'data' => new UserResource($user),
            'termsAndConditions' => $termsAndConditions ?? [],
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function updateProfile($data)
    {
        $id = Auth::id();
        $user = $this->userModel->find($id);
        $data['address'] = json_encode($data['address']);
        $user->update($data->all());
        if ($data->hasFile('photo')) {
            $user->media()->delete();
            $user->addMediaFromRequest('photo')->toMediaCollection('avatar');
        }
        return ['statusCode' => 200, 'status' => true,
            'message' => 'user updated successfully ',
            'data' => new UserResource($user)
        ];
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function changePassword($data)
    {
        $auth = Auth::user();

        // The passwords matches
        if (!Hash::check($data->get('current_password'), $auth->password)) {
            return response()->json(['statusCode' => 400, 'status' => false, 'message' => "Current Password is Invalid"], 400);
        }

        $user = $this->userModel->find($auth->id);
        $user->password = Hash::make($data->new_password);
        $user->save();
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => "Password Changed Successfully"]);
    }

    /**
     * @return JsonResponse
     */
    public function deleteAccount()
    {

        $userID = Auth::id();
        try {
            $user = $this->userModel->find($userID);
            $user->delete();
            return response()->json(["messages" => "deleted successfully", "status" => 200]);
        } catch (Exception $ex) {
            return response()->json(["messages" => $ex->getError()->message, "status" => 500]);
        }

    }

    /**
     * @return array
     */
    public function notification()
    {
        $notifications = $this->notificationModel
            ->query()
            ->where('user_id', Auth::id())
            ->get();
        foreach ($notifications as $notification) {
            $notification->markAsRead();


        }

        return ['statusCode' => 200, 'status' => true,
            'data' => $notifications
        ];
    }

    /**
     * @return array
     */

    public function unreadNotification()
    {
        $notification = $this->notificationModel
            ->query()
            ->where(['user_id' => Auth::id(), 'is_read' => false])
            ->get();
        return ['statusCode' => 200, 'status' => true,
            'data' => $notification
        ];

    }

    /**
     * @param $id
     * @return array
     */
    public function deleteNotification($id)
    {
        $this->notificationModel->where('id', $id);

        return ['statusCode' => 200, 'status' => true,
            'message' => 'delete'
        ];

    }

    /**
     * @param $data
     * @return array
     */
    public function companyRegister($data)
    {
        $user = $this->userModel->create([
            'name' => $data->name,
            'email' => $data->email,
            'address' => $data->address,
            'phone' => $data->phone,
            'companyId' => $data->companyId,
            'password' => hash::make($data->password),
            'approved' => false,
            'type' => 'company',

        ]);
        $user->assignRole('company');

        event(new NewCompany($user));


        return ['statusCode' => 200, 'status' => true,
            'message' => 'Your account is pending approval.',
            'data' => new CompanyResource($user)
        ];
    }

    /**
     * @return JsonResponse
     */
    public function allCompanies($data): JsonResponse
    {
        if ($data->q) {
            $data = $this->userModel->where("email", "like", "%$$data->q%")
                ->orwhere("name", "like", "%$$data->q%")->orderBy('id', 'DESC')->get();
            return response()->json([
                'success' => true,
                'data' => CompanyResource::collection($data)
            ], 201);
        }
        $companies = $this->userModel->query()->where(['type' => 'company'])->latest()->get();

        return response()->json(['statusCode' => 200, 'status' => true,
            'data' => CompanyResource::collection($companies)
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function ShowCompany($id): JsonResponse
    {
        $company = $this->userModel->query()->where(['type' => 'company', 'id' => $id])->first();
        if ($company) {
            return response()->json(['statusCode' => 200, 'status' => true,
                'data' => new CompanyResource($company)
            ]);
        }
        return response()->json(['status' => 400, 'msg' => 'invalid id']);

    }

    public function allCompaniesUnapproved(): array
    {
        $companies = $this->userModel->query()->where(['type' => 'company', 'approved' => 0])->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => CompanyResource::collection($companies)
        ];
    }

}
