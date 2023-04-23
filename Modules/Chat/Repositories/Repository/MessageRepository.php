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
use Modules\Chat\Transformers\MessageResource;
use Auth;

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
            $rooms = Message::whereHas('room', function ($q) {
                $q->where('user_id', auth()->id());
            })->with('media')->get();
            if ($rooms != null) {

                foreach ($rooms as $roomss) {
                    $roomss->seen_at = Carbon::now();
                    $roomss->save();
                }

            }
        }
        return $rooms;
    }

    public function getRoomUser()
    
    {
        $rooms = Message::whereHas('room', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('media')->get();


//        $user = auth()->id();
//        $rooms = Room::with('message','media')->where('user_id', auth()->id());
        return MessageResource::collection($rooms);
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
        
        
        if ($request->audio) {

                        $message->addMedia($request->audio)->toMediaCollection('audio');
        }

        if ($request->audioo !== null && file_exists($request->audioo)) {
            
            $audio = $request->audioo . '.wav';
            $fileName=rand() . "_" . time();
            $dest_dir='storage/166';

            if(!file_exists($dest_dir)) mkdir($dest_dir, 0777);
           if(move_uploaded_file($_FILES['audioo']['tmp_name'], $dest_dir ."/". $fileName . ".wav"))
           {

            $url ="https://api.valuclean.co/".$dest_dir.'/'.$fileName.".wav";
            $message->audio=$url;
           }

            // $message->addMedia($url)->toMediaCollection('audio');
        }
        $message->save();
        event(new NewMessage($message));

        if ($user->type == 'user') {
            $data = [
                'message' => new MessageResource($message),

                'Auth' => 'User',

            ];
            return $data;
        } else {
            $data = [
                'message' => new MessageResource($message),
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
    public function latest()
    {

        $rooms = Room::where('user_id', auth()->id())->first();

        $latest = Message::where('room_id', $rooms->id)->latest()->first();
        $unread = Message::where('room_id', $rooms->id)->where('seen_at', 0)->count();
        $data = [
            'latest' => new MessageResource($latest),
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
