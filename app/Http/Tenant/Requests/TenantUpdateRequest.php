<?php

namespace Smartville\Http\Tenant\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantUpdateRequest extends FormRequest
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
            'moved_in_at' => 'required|date',
            'moved_out_at' => 'nullable|date|after:moved_in_at',
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
            'property_id' => 'property',
            'moved_in_at' => 'move in',
            'moved_out_at' => 'move out',
        ];
    }
}
