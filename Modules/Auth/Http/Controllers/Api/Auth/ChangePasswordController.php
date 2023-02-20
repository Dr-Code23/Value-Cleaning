<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

class ChangePasswordController extends Controller
{
    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
    public function changePassword(ChangePasswordRequest $request)
    {

        $change= $this->UserRepository->changePassword($request);

        return $change;

    }
}
