<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        return [
            'number' => 'required_if:payment_method,card|digits_between:12,19',
            'exp_month' => 'required_if:payment_method,card|numeric|between:1,12',
            'exp_year' => 'required_if:payment_method,card|numeric|min:' . date('Y'),
            'cvc' => 'required_if:payment_method,card|numeric|between:100,999',
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
