<?php

namespace Modules\Chat\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Events\MakeAgoraCall;
use Modules\Class\AgoraDynamicKey\RtcTokenBuilder2;

class VideoCallController extends Controller
{
    use MessageResponseTrait;

    public function generateToken(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');

        $channelName = $request->channelName;
        if (!$channelName) {
            $channelName = "value-clean" . Auth::id();
        }
        $uid = '';
        $role = RtcTokenBuilder2::ROLE_PUBLISHER;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpire = $currentTimestamp + $expireTimeInSeconds;
        $tokenExpire = 3600;
        $token = RtcTokenBuilder2::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $tokenExpire, $privilegeExpire);


        $data = [
            'token' => $token,
            'channel' => $channelName,
            'user_id' => Auth::id()
        ];

        event(new MakeAgoraCall($data));
        // Return the generated token as a JSON response
        return $this->messageResponse(['token' => $token, 'channel' => $channelName], 'token', 200);


//        } catch (Exception $exception) {
//            // Handle exceptions that may occur while generating the token
//            throw new Exception('Failed to generate Agora token');
//        }
    }


}



