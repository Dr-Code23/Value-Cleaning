<?php

namespace Modules\Chat\Transformers;
use Modules\Auth\Transformers\UserReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Chat\Entities\Message;
class RoomResource extends JsonResource
{


    public function toArray($request)
    {
        $latest = Message::where('room_id', $this->id)->latest()->first();
        $unread = Message::where('room_id', $this->id)->where('seen_at', 0)->count();
        return [
            'room_id' => $this->id,
            'message' => $latest->message??'',
            'sender_id' => $this->users->id,
            'unread' =>$unread ,
            'user' => new UserReviewResource($this->users) ,
        ];
    }
}
