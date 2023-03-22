<?php

namespace Modules\Chat\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Entities\Message;
use Modules\Chat\Entities\Room;
use Illuminate\Support\Facades\Storage;
use Modules\Chat\Events\NewMessage;
use Modules\Chat\Events\NewRoom;
use Modules\Chat\Http\Controllers\MessageResponseTrait;
use Modules\Chat\Repositories\Interfaces\MessageInterface;
use Modules\Chat\Transformers\MessageResource;
use Spatie\Permission\Models\Role;


class MessageRepository implements MessageInterface
{
    use MessageResponseTrait;

       // get room messages
    public function room($request){
     $roomId =  explode(',',$request->input('id'));
     $rooms = Message::whereHas('room',function($q) use ($roomId) {
         $q->where('id',$roomId );
     })->with('media')->get();
        if ($rooms) {
            return $this->messageResponse(($rooms), 'The message found', 201);
        }
        return $this->messageResponse(null, 'The message Not found', 400);
     }

       // get Room
    public function getRoom($request)
    {
        $user_1 = auth()->id();
        $user_ids = [$user_1, $request->user_2];
        $message = Room::has('users', '=', count($user_ids))
            ->whereHas('users', function ($query) use ($user_ids) {
                $query->whereIn('user_id', $user_ids);
            }, '=', count($user_ids))->get('id');
        if ($message) {
            return $this->messageResponse(($message), 'The room found', 201);
        }
        return $this->messageResponse(null, 'The room Not found', 400);

    }

    //  send Message
    public function sendMessage($request)
    {
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->room_id = $request->room_id;
        $message->message = $request->message;
        $message->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('messages');
        });
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
        if (!$message) {
            return $this->messageResponse(null, 'The Message Not Found', 404);
        }
        $message->delete($id);
        if ($message) {
            return $this->messageResponse(null, 'The Message deleted', 200);
        }
    }

     // Create Room
    public  function createRoom ($request){
         $room = new Room();
         $room->save();
         $user_1 = auth()->id();
         $user_ids = [$user_1, $request->user_2];
         $room->users()->sync($user_ids);
            event(new NewRoom($room));
            if ($room) {
                return $this->messageResponse(($room), 'The Room Save', 201);
            }
            return $this->messageResponse(null, 'The Room Not Save', 400);
        }

    // read Message
    public function readMessage($id)
    {
        $message = Message::find($id);
        $message->seen_at = true;
        $message->save();
        if ($message) {
            return $this->messageResponse(($message), 'The Message Seen', 201);
        }
        return $this->messageResponse(null, 'The Message Not Seen', 400);
    }
}

