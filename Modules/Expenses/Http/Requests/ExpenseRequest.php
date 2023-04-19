<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name' => 'required|max:255',
            'type_id' => 'required',
            'money' => 'required',
            'date' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'name required',
            'money.required' => 'money required ',
            'type_id.required' => 'type required',
            'date' => 'required ',
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
