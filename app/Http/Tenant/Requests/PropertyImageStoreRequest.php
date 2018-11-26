<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyImageStoreRequest extends FormRequest
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
            'upload' => 'required|image|dimensions:min_width=1024,min_height=768'
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
            'upload' => 'image',
        ];
    }
}
