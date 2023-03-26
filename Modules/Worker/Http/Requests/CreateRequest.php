<?php

namespace Modules\Worker\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {


        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:workers',
            'address' => 'required',
            'phone' => 'required',
            'NIN' => 'required|unique:workers',
            'latitude' => '',
            'longitude' => '',
            'photo' => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],


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
