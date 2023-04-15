<?php

namespace Modules\Chat\Repositories\Repository;

use App\Models\User;
use Carbon\Carbon;
use Modules\Chat\Entities\Message;
use Modules\Chat\Entities\MessageUser;
use Modules\Chat\Entities\Room;
use Modules\Chat\Events\MessageToAdmin;
use Modules\Chat\Events\MessageToUser;
use Modules\Chat\Events\NewMessage;
use Modules\Chat\Events\NewRoom;
use Modules\Chat\Http\Controllers\MessageResponseTrait;
use Modules\Chat\Repositories\Interfaces\MessageInterface;


class MessageRepository implements MessageInterface
{
    use MessageResponseTrait;

    // check user or admin
    public function checkUser()
    {
        $user = auth()->id();
        $users = User::where('id', $user)->where('type', 'admin')->first();
        $userss = User::where('type', 'user')->where('id', $user)->first();
        if ($users == true) {
            return $rooms = Room::all();
        } elseif ($userss == true) {
            return $rooms = Message::whereHas('room', function ($q) {
                $q->where('user_id', auth()->id());
            })->with('media')->get();

        }
    }

    public function getRoomUser()
    {
        $rooms = Message::whereHas('room', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('media')->get();
//        $user = auth()->id();
//        $rooms = Room::with('message','media')->where('user_id', auth()->id());
        return $rooms;
        //    return  $room = Room::with('message')->where('user_id', $user)->first();
    }


    //  send Message
    public function sendMessage($request)
    {
        $user = auth()->user();
        $rooms = Room::where('user_id', auth()->id())->get();
        foreach ($rooms as $room) {
            $roomss = $room->id;
        }

        $image = $request->photo;
        $message = new Message();
        $message->sender_id = auth()->id();
        if ($user->type == 'admin') {
            $message->room_id = $request->room_id;
        } else {
            $message->room_id = $roomss;
        }
        $message->message = $request->message;
        // if ($request->message == !null) {
        //     $message->type_message = 'text';
        // } elseif ($request->photo == !null || $request->photo == 'mimes:jpeg,jpg,png,gif') {
        //     $message->type_message = 'image';
        // } else {
        //     $message->type_message = 'audio';
        // }
        if ($request->photo == !null) {
            $message->addMedia($image)->toMediaCollection('messages');
        }
        $message->save();
        event(new NewMessage($message));

        if ($user->type == 'user') {
            $data = [
                'message' => $message,
                'photo' => $message->getFirstMediaUrl('messages'),
                'Auth' => 'User',
            ];
            return $data;
        } else {
            $data = [
                'message' => $message,
                'Auth' => 'Admin',
            ];
        }
        return $data;
    }

    // delete message
    public function destroy($id)
    {
        $user = auth()->id();
        $messages = Message::where('sender_id', $user)->where('id', $id)->first();
        $messages->delete();
        $messages->media()->delete();
    }

    // latest Message
    public function latest($request)
    {
        $latest = Message::where(['room_id' => $request->id])->first();
        $unread = Message::where('room_id', $request->id)->where('seen_at', 0)->count();
        $data = [
            'latest' => $latest,
            'unread' => $unread,
        ];
        return $data;
    }

    public function allRoom()
    {
        $roomMessage = Room::with('message', 'users')->get();
        return $roomMessage;
    }

}
