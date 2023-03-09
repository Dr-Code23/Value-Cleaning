<?php

namespace Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_en' => 'string|max:255',
            'description_en' => 'string',
            'title_sv' => 'string|max:255',
            'description_sv' => 'string',
            'price' => '',
            'gallery.*' => ['image','mimes:jpg,png,jpeg,webp','max:2048'],
            "category_id"=> '',
            "offer_id"=>'max:2048',
            "worker_id"=>'',
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
