<?php

namespace Modules\Chat\Repositories\Repository;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\Image;
use Modules\Chat\Entities\Message;
use Modules\Chat\Entities\Room;
use Illuminate\Support\Facades\Storage;
use Modules\Chat\Events\NewMessage;
use Modules\Chat\Events\NewRoom;
use Modules\Chat\Http\Controllers\MessageResponseTrait;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use Modules\Chat\Transformers\MessageResource;

class MessageRepository implements MessageInterface
{
    use MessageResponseTrait;

    // get All Rooms
    public function getRooms()
    {
        $user = auth()->id();
        $room = Room::where('user_1', $user)->get();
        $rooms = Room::where('user_2', $user)->get();
        if ($room == true) {
            return $room;
        } else {
            return $rooms;
        }
    }
    // get Room
    public function room($request)
    {
        $room = Room::with(['message'])->where('id', $request->id)->get();
        if ($room) {
            return $this->messageResponse(($room), 'success', 201);
        }
        return $this->messageResponse(null, 'success', 400);
    }

    //  send Message
    public function sendMessage($request)
    {
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->room_id = $request->room_id;
        $message->message = $request->message;
        if ($request->image != '') {
            $file = $request->file('image');
            $name = 'messages/'.uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('image')->move("messages", $name);
            $message->image = $name;
        }
        $message->save();
        event(new NewMessage($message));
        if ($message) {
            return $this->messageResponse(($message), 'The message Save', 201);
        }
        return $this->messageResponse(null, 'The message Not Save', 400);
    }

    // delete message
    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
        if($message->image !== null) {
            unlink("$message->image");
        }
        if (!$message) {
            return $this->messageResponse(null, 'The Message Not Found', 404);
        }
        $message->delete($id);
        if ($message) {
            return $this->messageResponse(null, 'The Message deleted', 200);
        }
    }
    function checkRoom($request)
    {
        $room = Room::where('id', $request->room_id)->first();
        if ($room != null) {
            return $room;
        } else {
        $rooms = new Room();
        $rooms->user_1 = auth()->id();
        $rooms->user_2 = $request->user_2;
        $rooms->save();
        event(new NewRoom($rooms));
        if ($rooms) {
         return $this->messageResponse(($rooms), 'The Room Save', 201);
        }
        return $this->messageResponse(null, 'The Room Not Save', 400);
        }
    }
    function readMessage($id)
    {
        $message = Message::find($id);
        $message->read_at = true;
        $message->save();
        if ($message) {
            return $this->messageResponse(($message), 'The Message Read', 201);
        }
        return $this->messageResponse(null, 'The Message Not Read', 400);
    }
}
