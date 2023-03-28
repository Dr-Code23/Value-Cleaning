<?php

namespace Modules\Auth\Transformers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Entities\TermsAndConditions;

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
        $termsAndConditions = TermsAndConditions::all();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'photo' => $this->getFirstMediaUrl('avatar'),
            'avatar' => $this->providers,
            'count' => User::count(),
            'type' => $this->type,

        ];
    }
}
