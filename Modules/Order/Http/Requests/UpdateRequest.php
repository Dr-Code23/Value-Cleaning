<?php

namespace Modules\Order\Http\Requests;

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
            'worke_aera'=> 'required',
            'date' => 'required',
            'time' => 'required',
            'address' => 'required',
            'repeat' => 'required',
            'service_id' => 'required',
            'total_price' => 'required',
            'gallery'  =>'required',

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
