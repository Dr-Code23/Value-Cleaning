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
            'worke_aera'=> 'required',
            'date' => 'required',
            'time' => 'required',
            'address' => 'required',
            'repeat' => 'nullable',
            'service_id' => 'required',
            'sub_service_id' => 'nullable',
            'total_price' => 'nullable',
            'delivery_price'=> 'nullable',
            'gallery'  =>'nullable',


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
