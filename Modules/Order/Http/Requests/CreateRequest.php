<?php

namespace Modules\Order\Http\Requests;

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
            'work_area' => 'required',
            'date' => 'required',
            'time' => 'required',
            'day' => 'nullable',
            'address' => 'required',
            'latitude' => '',
            'longitude' => '',
            'repeat' => 'required',
            'service_id' => 'required',
            'sub_service_id' => 'sometimes',
            'requirement_id' => 'sometimes|array',
            'count' => 'sometimes|array',
            'total_price' => 'nullable',
            'gallery' => 'sometimes|array',


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
