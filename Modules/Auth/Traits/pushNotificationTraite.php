<?php

namespace Modules\Auth\Traits;


use App\Models\User;
use Modules\Auth\Entities\SendNotification;
use mysql_xdevapi\Exception;


trait pushNotificationTraite
{

    public function Notification($req)
    {

        try {
            SendNotification::create([
                'title' => $req['title'],
                'body' => $req['body'],
                'user_id' => $req['user_id'] ?? 3

            ]);

            $url = 'https://fcm.googleapis.com/fcm/send';

            if (isset($req['user_id'])) {
                $FcmToken = [User::whereNotNull('device_token')->where('id', $req['user_id'])->pluck('device_token')->first()];
            } else {
                $FcmToken = User::whereNotNull('device_token')->get('device_token');
            }
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => $req['title'],
                    "body" => $req['body'],
                    'sound' => 'default',
                ]
            ];
            $fields = json_encode($data);
            $headers = array(
                'Authorization: key=' . "AAAAOvzNBmI:APA91bH8YV406jne-9DRYAas2JZq6xcv6HCgWg1aMBkL2y6T6EhG1qCmBx_7zt2YZUiyHVQSXNfVbq7EWYzbxQaYo-CTH6_Ze7R53f8cOz6pZuQHrjbnxeB_5Rze6d7F5SE6MjJv7akX",
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $result = curl_exec($ch);
            curl_close($ch);
            $failure = json_decode($result);
            if ($failure->failure == 1) {
                return ['stuts' => 400, 'mesage' => 'send failure ', 'result' => $failure];

            }
            return ['stuts' => 200, 'mesage' => 'Notification Send successfully', 'result' => $result];


        } catch (\Exception) {
            return ['stuts' => 400, 'mesage' => 'can not to send Notification ', 'result' => []];

        }
    }


}

