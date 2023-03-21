<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{


    public function toArray($request)
    {
        return [
            'id' =>  $this->id,
            'messages'   => $this->messages,
            'sender_id' => auth()->id(),
            "room_id"=> $this->room_id,
            'gallery'  => $this->getFirstMediaUrl('attachments'),
        ];
    }
}
