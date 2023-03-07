<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Routing\Controller;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class SendNotificationController extends Controller
{
    private $AdminRepository;

    public function __construct(AdminRepositoryInterface $AdminRepository)
    {
        $this->AdminRepository = $AdminRepository;
    }


    public function sendNotification(Request $request)
    {
        return  $this->AdminRepository->pushNotification($request->all());

    }
}
