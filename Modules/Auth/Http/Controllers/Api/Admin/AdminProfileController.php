<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\UpdateRequest;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;

class AdminProfileController extends Controller
{
    private $AdminRepository;

    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }

    public function AdminProfile()
    {
        return $this->AdminRepository->profile();
    }


    public function AdminUpdateProfile(UpdateRequest $request)
    {


        return $this->AdminRepository->updateProfile($request);


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
