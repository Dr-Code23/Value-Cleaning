<?php

namespace Modules\Auth\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;

class RestePasswordController extends Controller
{
    private $UserRepository;

    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([

            'email' => 'required|string|email',

        ]);
       return  $this->UserRepository->forgotPassword($request);


    }



    public function checkCode(Request $request)
    {
      return $this->UserRepository->checkCode($request);

    }

    public function reset(Request $request)
    {


        return $this->UserRepository->reset($request);

    }}
