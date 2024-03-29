<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{


    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'sender_id' => $this->sender_id,
            "room_id" => $this->room_id,
            'audio' => $this->audio ?? $this->getFirstMediaUrl('audio') ?? 0,
            'photo' => $this->getFirstMediaUrl('messages'),
        ];
    }
}
