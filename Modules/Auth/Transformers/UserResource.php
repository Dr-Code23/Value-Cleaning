<?php

namespace Modules\Auth\Transformers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'photo' => $this->getFirstMediaUrl('avatar'),
            'avatar' => $this->providers,
            'count' => User::count(),
            'type' => $this->type

        ];
    }
}
