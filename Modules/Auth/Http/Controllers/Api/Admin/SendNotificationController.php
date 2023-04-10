<?php

namespace Modules\Auth\Http\Controllers\Api\Admin;

use Illuminate\Routing\Controller;
use Modules\Auth\Entities\SendNotification;
use Modules\Auth\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class SendNotificationController extends Controller
{
    private $AdminRepository;
    private $notificationModel;

    public function __construct(AdminRepositoryInterface $AdminRepository, SendNotification $notification)
    {
        $this->AdminRepository = $AdminRepository;
        $this->notificationModel = $notification;
    }


    public function sendNotification(Request $request)
    {
        return $this->AdminRepository->pushNotification($request->all());

    }

    public function Notification(Request $request)
    {
        $notifications = $this->notificationModel
            ->query()
            ->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => $notifications
        ];

    }

    public function deleteNotification($id)
    {
        $notifications = $this->notificationModel
            ->query()
            ->where('id', $id)
            ->first()->delete();

        return ['statusCode' => 200, 'status' => true,
            'message' => 'deleted successfully'
        ];
    }
}
