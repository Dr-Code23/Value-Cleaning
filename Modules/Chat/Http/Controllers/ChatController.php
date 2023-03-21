<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Entities\Message;
use App\Models\User;
use Modules\Chat\Action\MessageSend;
use Modules\Chat\Events\NewMessage;
use Modules\Chat\Http\Requests\MessageRequest;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use Modules\Chat\Transformers\MessageResource;

class ChatController extends Controller
{
    protected $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    // get All Rooms
    public function index()
    {
//        return $message = $this->message->getRooms();
    }

    // get All Rooms
    public function all()
    {
        return  $message = $this->message->getRooms();
    }
    // get room with message
    public function room(Request $request)
    {
        return $message = $this->message->room($request);
    }

    // check Room
    public function check(Request $request)
    {
        return $message = $this->message->checkRoom($request);
    }


    // read Message
    public function read($id)
    {
        return $message = $this->message->readMessage($id);
    }

    //  send Message
    public function store(MessageRequest $request)
    {
         return $message = $this->message->sendMessage($request);
    }


    // delete message
    public function destroy($id)
    {
        return $message = $this->message->destroy($id);
    }

}
