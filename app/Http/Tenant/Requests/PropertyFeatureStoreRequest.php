<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyFeatureStoreRequest extends FormRequest
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
            'name' => 'required|max:160',
            'count' => 'required|numeric',
            'details' => 'nullable|max:300',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'count' => 'total',
        ];
    }
}
