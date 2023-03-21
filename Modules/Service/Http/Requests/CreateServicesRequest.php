<?php

namespace Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServicesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_en' => 'required|string|max:255',
            'description_en' => 'required|string',
            'title_sv' => 'required|string|max:255',
            'description_sv' => 'required|string',
            'price' => 'required|numeric',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> 'required',
            "offer_id"=>'max:2048',
            "worker_id"=>'required',
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
