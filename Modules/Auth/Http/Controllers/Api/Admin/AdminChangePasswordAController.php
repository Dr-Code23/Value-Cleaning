<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;

class AdminChangePasswordAController extends Controller
{ private $AdminRepository;

    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }
    public function AdminchangePassword(ChangePasswordRequest $request)
    {
        return $this->AdminRepository->changePassword($request);


    }
}
