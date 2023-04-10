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

    // get room messages
    public function room($request)
    {
        $message = Message::where('room_id', $request->id)->first();
        if ($message != null) {
            $message->seen_at = Carbon::now();
            $message->save();
        }
        $roomId = explode(',', $request->input('id'));
        $rooms = Message::whereHas('room', function ($q) use ($roomId) {
            $q->where('id', $roomId);
        })->with('media')->get();
        return $rooms;
    }

    // view SoftDelete
    public function getSoft()
    {
        $user = auth()->id();
        $onlySoftDeleted = MessageUser::onlyTrashed()->pluck('delete_by');
        foreach ($onlySoftDeleted as $soft) {
            if ($soft == $user) {
                return "Message Delete";
            }
        }
    }

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
        return $rooms = Message::whereHas('room', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('media')->get();
        //    return  $room = Room::with('message')->where('user_id', $user)->first();
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
        foreach ($message as $i) {
            $latest = Message::where(['room_id' => $i->id])->latest()->first();
            $unRead = Message::where('room_id', $i->id)->where('seen_at', 0)->count();
        }
        $data = [
            'latest' => $latest,
            'unread' => $unRead,
            'room' => $message
        ];
        return $data;
    }

    //  send Message
    public function sendMessage($request)
    {
        $image = $request->photo;
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->room_id = $request->room_id;
        $message->message = $request->message;
        if ($request->photo == !null) {
            $message->addMedia($image)->toMediaCollection('messages');
        }
        $message->save();
        event(new NewMessage($message));
        $user = auth()->id();
        $chat = Room::where('user_id', $user)->get();
        if ($chat == true) {
            $data = [
                'message' => $message,
                'Auth' => 'User',
            ];
            return $data;
        }
        {
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

    // Create Room
    public function createRoom($request)
    {
        $room = new Room();
        $room->save();
        $user_1 = auth()->id();
        //   User::where('type' , 'admin')->first()->id;
        $user_2 = $request->user_2;
        $user_ids = [$user_1, $user_2];
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

    public function deleteMessage($request)
    {
        $message = MessageUser::where('message_id', $request->id)->first();
        $message->delete();
        $message->delete_by = auth()->id();
        $message->save();
        return $message;
    }

    public function allRoom()
    {
        $roomMessage = Room::with('message', 'users')->get();
        return $roomMessage;
    }

    public function messageAdmin($request)
    {
        // TODO: Implement messageAdmin() method.
    }
}

