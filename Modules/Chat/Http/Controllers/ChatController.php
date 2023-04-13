<?php

namespace Modules\Chat\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Events\MakeAgoraCall;
use Modules\Chat\Http\Requests\MessageRequest;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use Modules\Class\AgoraDynamicKey\RtcTokenBuilder;

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
        // $message = $this->message->getRoom($request);
        // if (count($message) == null) {
        $message = $this->message->createRoom($request);  // failure

        return $this->messageResponse(($message), 'Success', 200);
    }

    // get Room of User
    public function getUser()
    {
        return $message = $this->message->getRoomUser();
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


}


