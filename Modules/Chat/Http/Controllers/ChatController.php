<?php

namespace Modules\Chat\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Events\MakeAgoraCall;
use Modules\Chat\Http\Requests\MessageRequest;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use TomatoPHP\LaravelAgora\Services\Agora;

class ChatController extends Controller
{
    use MessageResponseTrait;

    protected $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    // get room with message
    public function index()
    {
        $rooms = $this->message->allRoom();
        if ($rooms) {
            return $this->messageResponse(($rooms), 'Room Found', 200);
        }
        return $this->messageResponse(null, 'Room Not Found', 400);
    }


    // get Room of User
    public function getUser()
    {
       return $message = $this->message->getRoomUser();
    }


    public function store(MessageRequest $request)
    {
        $message = $this->message->sendMessage($request);
        if ($message) {
            return $this->messageResponse(($message), 'The Message Saved', 200);
        }
        return $this->messageResponse(null, 'The Message Not Save', 400);
    }

    // delete message
    public function destroy($id)
    {
        $message = $this->message->destroy($id);
        if ($message) {
            return $this->messageResponse(($message), 'The Message delete', 200);
        }
        return $this->messageResponse(null, 'The Message Not delete', 400);
    }
        // check user or admin
    public function checkRoom()
    {
        $message = $this->message->checkUser();
        return $this->messageResponse($message, 'found', 200);
    }
    public function latestMessage(Request $request)
    {
        $message = $this->message->latest($request);
        return $this->messageResponse($message, 'all', 200);
    }

    public function generateToken(Request $request)
    {
//        try {
        // Get the Agora credentials from environment variables
        $appId = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');

// Get the channel name and user ID from the request parameters
        $channelName = "value-clean" . Auth::id();
        $uid = Auth::id();

// Ensure that $uid is set to a non-null string value
        if (!$uid) {
            $uid = (string)rand(999, 1999);
        }

// Create an instance of the Agora class with the Agora app ID and app certificate
        $agora = Agora::make($appId, $appCertificate);

// Set the UID of the token to the specified user ID
        $agora->uId($uid);

// Specify the channel name for the token
        $agora->channel($channelName);
        // Generate an Agora token for the specified channel name and UID
        $token = $agora->token();
        $data = [
            'token' => $token,
            'channel' => $channelName,
            'user_id' => $uid
        ];

//        broadcast(new MakeAgoraCall($data))->toOthers();

        // Return the generated token as a JSON response
        return $this->messageResponse(['token' => $token, 'channel' => $channelName], 'token', 200);


//        } catch (Exception $exception) {
//            // Handle exceptions that may occur while generating the token
//            throw new Exception('Failed to generate Agora token');
//        }
    }


}
