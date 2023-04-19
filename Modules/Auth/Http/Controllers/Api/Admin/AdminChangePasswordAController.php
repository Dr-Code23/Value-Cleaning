<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;

class AdminChangePasswordAController extends Controller
{
    private $AdminRepository;

    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }

    public function adminChangePassword(ChangePasswordRequest $request)
    {
        return $this->AdminRepository->changePassword($request);


    }
}
