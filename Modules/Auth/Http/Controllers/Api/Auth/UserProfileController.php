<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Modules\Auth\Http\Requests\UpdateRequest;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use function Symfony\Component\Translation\t;

class UserProfileController extends Controller
{
    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function profile()
    {
        return $this->UserRepository->profile();

    }


    public function updateProfile(UpdateRequest $request)
    {


      return $this->UserRepository->updateProfile($request);

    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }



    public function deleteAccount()
    {
        return $this->UserRepository->deleteAccount();
    }
}
