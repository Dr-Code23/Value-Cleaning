<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
    public function room(Request $request)
    {
        $message = $this->message->getRoom($request);
        if ($message) {
            return $this->messageResponse(($message), 'Room Found', 200);
        }
        return $this->messageResponse(null, 'Room Not Found', 400);
    }

    // check Room
    public function check(Request $request)
    {
        $message = $this->message->getRoom($request);
        if (count($message) == null) {
            $message = $this->message->createRoom($request);  // failure
        }
        return $this->messageResponse(($message), 'Success', 201);
    }

    // read Message
    public function read($id)
    {
        $message = $this->message->readMessage($id);
        if ($message) {
            return $this->messageResponse(($message), 'The Message Seen', 200);
        }
        return $this->messageResponse(null, 'The Room Not Save', 400);
    }

    // delete room
    public function delete(Request $request)
    {
        $message = $this->message->deleteMessage($request);
        if ($message) {
            return $this->messageResponse(($message), 'The room delete', 200);
        }
        return $this->messageResponse(null, 'The Room Not delete', 400);
    }


    // read Message
    public function message(Request $request)
    {
        $message = $this->message->room($request);
        if ($message) {
            return $this->messageResponse(($message), 'The room message', 200);
        }
        return $this->messageResponse(null, 'The Room Not found', 400);
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

    //  get SoftDeletes
    public function getMessage()
    {
        return $message = $this->message->getSoft();
    }

    public function latestMessage(Request $request)
    {
        $message = $this->message->latest($request);
        return $this->messageResponse($message, 'all', 200);
    }

    public function generateToken(Request $request)
    {
        // Get the Agora credentials from environment variables
        $appId = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');

// Get the channel name and user ID from the request parameters
        $channelName = $request->input('channelName');
        $uid = $request->input('uid');

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
        // Return the generated token as a JSON response
        return $this->messageResponse($token, 'token', 200);

    }

}
