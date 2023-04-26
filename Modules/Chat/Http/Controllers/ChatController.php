<?php

namespace Modules\Chat\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use Modules\Chat\Transformers\MessageResource;
use Modules\Chat\Http\Requests\MessageRequest;

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

    public function getUserToAmind(Request $request)
    {
        return $message = $this->message->getRoomUserToAdmin($request);
        
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
        return $this->messageResponse(MessageResource::collection($message), 'found', 200);
    }

    public function latestMessage()
    {
        $message = $this->message->latest();
        return $this->messageResponse($message, 'all', 200);
    }


}
