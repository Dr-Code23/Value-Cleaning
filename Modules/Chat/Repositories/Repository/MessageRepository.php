<?php

namespace Modules\Chat\Repositories\Repository;

use Carbon\Carbon;
use Modules\Chat\Entities\Message;
use Modules\Chat\Entities\Room;
use Modules\Chat\Events\NewMessage;
use Modules\Chat\Events\NewRoom;
use Modules\Chat\Http\Controllers\MessageResponseTrait;
use Modules\Chat\Repositories\Interfaces\MessageInterface;


class MessageRepository implements MessageInterface
{
    use MessageResponseTrait;

    // get room messages
    public function room($request)
    {
        $roomId = explode(',', $request->input('id'));
        $rooms = Message::whereHas('room', function ($q) use ($roomId) {
            $q->where('id', $roomId);
        })->with('media')->get();
       return $rooms;
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
        return $message;
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
        return $message;
    }

    // delete message
    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
        return $message;
    }

    // Create Room
    public function createRoom($request)
    {
        $room = new Room();
        $room->save();
        $user_1 = auth()->id();
        $user_ids = [$user_1, $request->user_2];
        $room->users()->sync($user_ids);
        event(new NewRoom($room));
        return $room;
    }

    // read Message
    public function readMessage($id)
    {
        $message = Message::find($id);
        $message->seen_at = Carbon::now();
        $message->save();
        return $message;
    }
    public function deleteRoom($request)
    {
        $user_1 = auth()->id();
        $message = Room::where('id', $request->id)->first();
        $message->delete();
        $message->delete_by = auth()->id();
        $message->save();
        return $message;
    }
}

