<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'image' => 'required',  // todo: add custom rule to check if file exists
            'overview_short' => 'required|max:160',
            'overview' => 'required|max:1500',
            'currency' => [
                'required',
                Rule::exists('currencies', 'cc')->where('usable', true)
            ],
            'size' => 'required|numeric|min:1',
            'price' => 'required|integer',
            'amenities.*' => [
                'nullable',
                Rule::exists('amenities', 'id')->where('usable', true)
            ]
        ];
    }
}
