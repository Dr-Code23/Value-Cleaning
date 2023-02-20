<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use App\Models\User;
use Auth;
use Modules\Auth\Http\Requests\CreateRequest;
use Modules\Auth\Http\Requests\loginRequest;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    private $AdminRepository;

    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }

    public function AdminRegister(CreateRequest $request)
    {
        return  $this->AdminRepository->register($request);


    }


    public function AdminLogin(loginRequest $request)
    {
        return  $this->AdminRepository->login($request);

    }
}
