<?php

namespace Modules\Worker\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|between:2,100',
            'email' => 'string|email|max:100',
            'address' => '',
            'phone' => '',
            'NIN' => '',
            'latitude' => '',
            'longitude' => '',
            'photo' => '',


        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
